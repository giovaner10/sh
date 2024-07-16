"use strict";
var __extends = (this && this.__extends) || (function () {
    var extendStatics = function (d, b) {
        extendStatics = Object.setPrototypeOf ||
            ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
            function (d, b) { for (var p in b) if (Object.prototype.hasOwnProperty.call(b, p)) d[p] = b[p]; };
        return extendStatics(d, b);
    };
    return function (d, b) {
        if (typeof b !== "function" && b !== null)
            throw new TypeError("Class extends value " + String(b) + " is not a constructor or null");
        extendStatics(d, b);
        function __() { this.constructor = d; }
        d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
    };
})();
Object.defineProperty(exports, "__esModule", { value: true });
var util_js_1 = require("../util/util.js");
var base_js_1 = require("../core/base.js");
var loader_js_1 = require("../slide/loader.js");
/**
 * @template T
 * @typedef {import('../types.js').Type<T>} Type<T>
 */
/** @typedef {import('../photoswipe.js').default} PhotoSwipe */
/** @typedef {import('../photoswipe.js').PhotoSwipeOptions} PhotoSwipeOptions */
/** @typedef {import('../photoswipe.js').DataSource} DataSource */
/** @typedef {import('../photoswipe.js').Point} Point */
/** @typedef {import('../slide/content.js').default} Content */
/** @typedef {import('../core/eventable.js').PhotoSwipeEventsMap} PhotoSwipeEventsMap */
/** @typedef {import('../core/eventable.js').PhotoSwipeFiltersMap} PhotoSwipeFiltersMap */
/**
 * @template {keyof PhotoSwipeEventsMap} T
 * @typedef {import('../core/eventable.js').EventCallback<T>} EventCallback<T>
 */
/**
 * PhotoSwipe Lightbox
 *
 * - If user has unsupported browser it falls back to default browser action (just opens URL)
 * - Binds click event to links that should open PhotoSwipe
 * - parses DOM strcture for PhotoSwipe (retrieves large image URLs and sizes)
 * - Initializes PhotoSwipe
 *
 *
 * Loader options use the same object as PhotoSwipe, and supports such options:
 *
 * gallery - Element | Element[] | NodeList | string selector for the gallery element
 * children - Element | Element[] | NodeList | string selector for the gallery children
 *
 */
var PhotoSwipeLightbox = /** @class */ (function (_super) {
    __extends(PhotoSwipeLightbox, _super);
    /**
     * @param {PhotoSwipeOptions} [options]
     */
    function PhotoSwipeLightbox(options) {
        var _this = _super.call(this) || this;
        /** @type {PhotoSwipeOptions} */
        _this.options = options || {};
        _this._uid = 0;
        _this.shouldOpen = false;
        /**
         * @private
         * @type {Content | undefined}
         */
        _this._preloadedContent = undefined;
        _this.onThumbnailsClick = _this.onThumbnailsClick.bind(_this);
        return _this;
    }
    /**
     * Initialize lightbox, should be called only once.
     * It's not included in the main constructor, so you may bind events before it.
     */
    PhotoSwipeLightbox.prototype.init = function () {
        var _this = this;
        // Bind click events to each gallery
        (0, util_js_1.getElementsFromOption)(this.options.gallery, this.options.gallerySelector)
            .forEach(function (galleryElement) {
            galleryElement.addEventListener('click', _this.onThumbnailsClick, false);
        });
    };
    /**
     * @param {MouseEvent} e
     */
    PhotoSwipeLightbox.prototype.onThumbnailsClick = function (e) {
        // Exit and allow default browser action if:
        if ((0, util_js_1.specialKeyUsed)(e) // ... if clicked with a special key (ctrl/cmd...)
            || window.pswp) { // ... if PhotoSwipe is already open
            return;
        }
        // If both clientX and clientY are 0 or not defined,
        // the event is likely triggered by keyboard,
        // so we do not pass the initialPoint
        //
        // Note that some screen readers emulate the mouse position,
        // so it's not the ideal way to detect them.
        //
        /** @type {Point | null} */
        var initialPoint = { x: e.clientX, y: e.clientY };
        if (!initialPoint.x && !initialPoint.y) {
            initialPoint = null;
        }
        var clickedIndex = this.getClickedIndex(e);
        clickedIndex = this.applyFilters('clickedIndex', clickedIndex, e, this);
        /** @type {DataSource} */
        var dataSource = {
            gallery: /** @type {HTMLElement} */ (e.currentTarget)
        };
        if (clickedIndex >= 0) {
            e.preventDefault();
            this.loadAndOpen(clickedIndex, dataSource, initialPoint);
        }
    };
    /**
     * Get index of gallery item that was clicked.
     *
     * @param {MouseEvent} e click event
     * @returns {number}
     */
    PhotoSwipeLightbox.prototype.getClickedIndex = function (e) {
        // legacy option
        if (this.options.getClickedIndexFn) {
            return this.options.getClickedIndexFn.call(this, e);
        }
        var clickedTarget = /** @type {HTMLElement} */ (e.target);
        var childElements = (0, util_js_1.getElementsFromOption)(this.options.children, this.options.childSelector, 
        /** @type {HTMLElement} */ (e.currentTarget));
        var clickedChildIndex = childElements.findIndex(function (child) { return child === clickedTarget || child.contains(clickedTarget); });
        if (clickedChildIndex !== -1) {
            return clickedChildIndex;
        }
        else if (this.options.children || this.options.childSelector) {
            // click wasn't on a child element
            return -1;
        }
        // There is only one item (which is the gallery)
        return 0;
    };
    /**
     * Load and open PhotoSwipe
     *
     * @param {number} index
     * @param {DataSource} [dataSource]
     * @param {Point | null} [initialPoint]
     * @returns {boolean}
     */
    PhotoSwipeLightbox.prototype.loadAndOpen = function (index, dataSource, initialPoint) {
        // Check if the gallery is already open
        if (window.pswp || !this.options) {
            return false;
        }
        // Use the first gallery element if dataSource is not provided
        if (!dataSource && this.options.gallery && this.options.children) {
            var galleryElements = (0, util_js_1.getElementsFromOption)(this.options.gallery);
            if (galleryElements[0]) {
                dataSource = {
                    gallery: galleryElements[0]
                };
            }
        }
        // set initial index
        this.options.index = index;
        // define options for PhotoSwipe constructor
        this.options.initialPointerPos = initialPoint;
        this.shouldOpen = true;
        this.preload(index, dataSource);
        return true;
    };
    /**
     * Load the main module and the slide content by index
     *
     * @param {number} index
     * @param {DataSource} [dataSource]
     */
    PhotoSwipeLightbox.prototype.preload = function (index, dataSource) {
        var _this = this;
        var options = this.options;
        if (dataSource) {
            options.dataSource = dataSource;
        }
        // Add the main module
        /** @type {Promise<Type<PhotoSwipe>>[]} */
        var promiseArray = [];
        var pswpModuleType = typeof options.pswpModule;
        if ((0, util_js_1.isPswpClass)(options.pswpModule)) {
            promiseArray.push(Promise.resolve(/** @type {Type<PhotoSwipe>} */ (options.pswpModule)));
        }
        else if (pswpModuleType === 'string') {
            throw new Error('pswpModule as string is no longer supported');
        }
        else if (pswpModuleType === 'function') {
            promiseArray.push(/** @type {() => Promise<Type<PhotoSwipe>>} */ (options.pswpModule)());
        }
        else {
            throw new Error('pswpModule is not valid');
        }
        // Add custom-defined promise, if any
        if (typeof options.openPromise === 'function') {
            // allow developers to perform some task before opening
            promiseArray.push(options.openPromise());
        }
        if (options.preloadFirstSlide !== false && index >= 0) {
            this._preloadedContent = (0, loader_js_1.lazyLoadSlide)(index, this);
        }
        // Wait till all promises resolve and open PhotoSwipe
        var uid = ++this._uid;
        Promise.all(promiseArray).then(function (iterableModules) {
            if (_this.shouldOpen) {
                var mainModule = iterableModules[0];
                _this._openPhotoswipe(mainModule, uid);
            }
        });
    };
    /**
     * @private
     * @param {Type<PhotoSwipe> | { default: Type<PhotoSwipe> }} module
     * @param {number} uid
     */
    PhotoSwipeLightbox.prototype._openPhotoswipe = function (module, uid) {
        var _this = this;
        // Cancel opening if UID doesn't match the current one
        // (if user clicked on another gallery item before current was loaded).
        //
        // Or if shouldOpen flag is set to false
        // (developer may modify it via public API)
        if (uid !== this._uid && this.shouldOpen) {
            return;
        }
        this.shouldOpen = false;
        // PhotoSwipe is already open
        if (window.pswp) {
            return;
        }
        /**
         * Pass data to PhotoSwipe and open init
         *
         * @type {PhotoSwipe}
         */
        var pswp = typeof module === 'object'
            ? new module.default(this.options) // eslint-disable-line
            : new module(this.options); // eslint-disable-line
        this.pswp = pswp;
        window.pswp = pswp;
        // map listeners from Lightbox to PhotoSwipe Core
        /** @type {(keyof PhotoSwipeEventsMap)[]} */
        (Object.keys(this._listeners)).forEach(function (name) {
            var _a;
            (_a = _this._listeners[name]) === null || _a === void 0 ? void 0 : _a.forEach(function (fn) {
                pswp.on(name, /** @type {EventCallback<typeof name>} */ (fn));
            });
        });
        // same with filters
        /** @type {(keyof PhotoSwipeFiltersMap)[]} */
        (Object.keys(this._filters)).forEach(function (name) {
            var _a;
            (_a = _this._filters[name]) === null || _a === void 0 ? void 0 : _a.forEach(function (filter) {
                pswp.addFilter(name, filter.fn, filter.priority);
            });
        });
        if (this._preloadedContent) {
            pswp.contentLoader.addToCache(this._preloadedContent);
            this._preloadedContent = undefined;
        }
        pswp.on('destroy', function () {
            // clean up public variables
            _this.pswp = undefined;
            delete window.pswp;
        });
        pswp.init();
    };
    /**
     * Unbinds all events, closes PhotoSwipe if it's open.
     */
    PhotoSwipeLightbox.prototype.destroy = function () {
        var _this = this;
        var _a;
        (_a = this.pswp) === null || _a === void 0 ? void 0 : _a.destroy();
        this.shouldOpen = false;
        this._listeners = {};
        (0, util_js_1.getElementsFromOption)(this.options.gallery, this.options.gallerySelector)
            .forEach(function (galleryElement) {
            galleryElement.removeEventListener('click', _this.onThumbnailsClick, false);
        });
    };
    return PhotoSwipeLightbox;
}(base_js_1.default));
exports.default = PhotoSwipeLightbox;
//# sourceMappingURL=lightbox.js.map