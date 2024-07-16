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
var __assign = (this && this.__assign) || function () {
    __assign = Object.assign || function(t) {
        for (var s, i = 1, n = arguments.length; i < n; i++) {
            s = arguments[i];
            for (var p in s) if (Object.prototype.hasOwnProperty.call(s, p))
                t[p] = s[p];
        }
        return t;
    };
    return __assign.apply(this, arguments);
};
Object.defineProperty(exports, "__esModule", { value: true });
var util_js_1 = require("./util/util.js");
var dom_events_js_1 = require("./util/dom-events.js");
var slide_js_1 = require("./slide/slide.js");
var gestures_js_1 = require("./gestures/gestures.js");
var main_scroll_js_1 = require("./main-scroll.js");
var keyboard_js_1 = require("./keyboard.js");
var animations_js_1 = require("./util/animations.js");
var scroll_wheel_js_1 = require("./scroll-wheel.js");
var ui_js_1 = require("./ui/ui.js");
var viewport_size_js_1 = require("./util/viewport-size.js");
var get_thumb_bounds_js_1 = require("./slide/get-thumb-bounds.js");
var base_js_1 = require("./core/base.js");
var opener_js_1 = require("./opener.js");
var loader_js_1 = require("./slide/loader.js");
/**
 * @template T
 * @typedef {import('./types.js').Type<T>} Type<T>
 */
/** @typedef {import('./slide/slide.js').SlideData} SlideData */
/** @typedef {import('./slide/zoom-level.js').ZoomLevelOption} ZoomLevelOption */
/** @typedef {import('./ui/ui-element.js').UIElementData} UIElementData */
/** @typedef {import('./main-scroll.js').ItemHolder} ItemHolder */
/** @typedef {import('./core/eventable.js').PhotoSwipeEventsMap} PhotoSwipeEventsMap */
/** @typedef {import('./core/eventable.js').PhotoSwipeFiltersMap} PhotoSwipeFiltersMap */
/** @typedef {import('./slide/get-thumb-bounds').Bounds} Bounds */
/**
 * @template {keyof PhotoSwipeEventsMap} T
 * @typedef {import('./core/eventable.js').EventCallback<T>} EventCallback<T>
 */
/**
 * @template {keyof PhotoSwipeEventsMap} T
 * @typedef {import('./core/eventable.js').AugmentedEvent<T>} AugmentedEvent<T>
 */
/** @typedef {{ x: number; y: number; id?: string | number }} Point */
/** @typedef {{ top: number; bottom: number; left: number; right: number }} Padding */
/** @typedef {SlideData[]} DataSourceArray */
/** @typedef {{ gallery: HTMLElement; items?: HTMLElement[] }} DataSourceObject */
/** @typedef {DataSourceArray | DataSourceObject} DataSource */
/** @typedef {(point: Point, originalEvent: PointerEvent) => void} ActionFn */
/** @typedef {'close' | 'next' | 'zoom' | 'zoom-or-close' | 'toggle-controls'} ActionType */
/** @typedef {Type<PhotoSwipe> | { default: Type<PhotoSwipe> }} PhotoSwipeModule */
/** @typedef {PhotoSwipeModule | Promise<PhotoSwipeModule> | (() => Promise<PhotoSwipeModule>)} PhotoSwipeModuleOption */
/**
 * @typedef {string | NodeListOf<HTMLElement> | HTMLElement[] | HTMLElement} ElementProvider
 */
/** @typedef {Partial<PreparedPhotoSwipeOptions>} PhotoSwipeOptions https://photoswipe.com/options/ */
/**
 * @typedef {Object} PreparedPhotoSwipeOptions
 *
 * @prop {DataSource} [dataSource]
 * Pass an array of any items via dataSource option. Its length will determine amount of slides
 * (which may be modified further from numItems event).
 *
 * Each item should contain data that you need to generate slide
 * (for image slide it would be src (image URL), width (image width), height, srcset, alt).
 *
 * If these properties are not present in your initial array, you may "pre-parse" each item from itemData filter.
 *
 * @prop {number} bgOpacity
 * Background backdrop opacity, always define it via this option and not via CSS rgba color.
 *
 * @prop {number} spacing
 * Spacing between slides. Defined as ratio relative to the viewport width (0.1 = 10% of viewport).
 *
 * @prop {boolean} allowPanToNext
 * Allow swipe navigation to the next slide when the current slide is zoomed. Does not apply to mouse events.
 *
 * @prop {boolean} loop
 * If set to true you'll be able to swipe from the last to the first image.
 * Option is always false when there are less than 3 slides.
 *
 * @prop {boolean} [wheelToZoom]
 * By default PhotoSwipe zooms image with ctrl-wheel, if you enable this option - image will zoom just via wheel.
 *
 * @prop {boolean} pinchToClose
 * Pinch touch gesture to close the gallery.
 *
 * @prop {boolean} closeOnVerticalDrag
 * Vertical drag gesture to close the PhotoSwipe.
 *
 * @prop {Padding} [padding]
 * Slide area padding (in pixels).
 *
 * @prop {(viewportSize: Point, itemData: SlideData, index: number) => Padding} [paddingFn]
 * The option is checked frequently, so make sure it's performant. Overrides padding option if defined. For example:
 *
 * @prop {number | false} hideAnimationDuration
 * Transition duration in milliseconds, can be 0.
 *
 * @prop {number | false} showAnimationDuration
 * Transition duration in milliseconds, can be 0.
 *
 * @prop {number | false} zoomAnimationDuration
 * Transition duration in milliseconds, can be 0.
 *
 * @prop {string} easing
 * String, 'cubic-bezier(.4,0,.22,1)'. CSS easing function for open/close/zoom transitions.
 *
 * @prop {boolean} escKey
 * Esc key to close.
 *
 * @prop {boolean} arrowKeys
 * Left/right arrow keys for navigation.
 *
 * @prop {boolean} trapFocus
 * Trap focus within PhotoSwipe element while it's open.
 *
 * @prop {boolean} returnFocus
 * Restore focus the last active element after PhotoSwipe is closed.
 *
 * @prop {boolean} clickToCloseNonZoomable
 * If image is not zoomable (for example, smaller than viewport) it can be closed by clicking on it.
 *
 * @prop {ActionType | ActionFn | false} imageClickAction
 * Refer to click and tap actions page.
 *
 * @prop {ActionType | ActionFn | false} bgClickAction
 * Refer to click and tap actions page.
 *
 * @prop {ActionType | ActionFn | false} tapAction
 * Refer to click and tap actions page.
 *
 * @prop {ActionType | ActionFn | false} doubleTapAction
 * Refer to click and tap actions page.
 *
 * @prop {number} preloaderDelay
 * Delay before the loading indicator will be displayed,
 * if image is loaded during it - the indicator will not be displayed at all. Can be zero.
 *
 * @prop {string} indexIndicatorSep
 * Used for slide count indicator ("1 of 10 ").
 *
 * @prop {(options: PhotoSwipeOptions, pswp: PhotoSwipeBase) => Point} [getViewportSizeFn]
 * A function that should return slide viewport width and height, in format {x: 100, y: 100}.
 *
 * @prop {string} errorMsg
 * Message to display when the image wasn't able to load. If you need to display HTML - use contentErrorElement filter.
 *
 * @prop {[number, number]} preload
 * Lazy loading of nearby slides based on direction of movement. Should be an array with two integers,
 * first one - number of items to preload before the current image, second one - after the current image.
 * Two nearby images are always loaded.
 *
 * @prop {string} [mainClass]
 * Class that will be added to the root element of PhotoSwipe, may contain multiple separated by space.
 * Example on Styling page.
 *
 * @prop {HTMLElement} [appendToEl]
 * Element to which PhotoSwipe dialog will be appended when it opens.
 *
 * @prop {number} maxWidthToAnimate
 * Maximum width of image to animate, if initial rendered image width
 * is larger than this value - the opening/closing transition will be automatically disabled.
 *
 * @prop {string} [closeTitle]
 * Translating
 *
 * @prop {string} [zoomTitle]
 * Translating
 *
 * @prop {string} [arrowPrevTitle]
 * Translating
 *
 * @prop {string} [arrowNextTitle]
 * Translating
 *
 * @prop {'zoom' | 'fade' | 'none'} [showHideAnimationType]
 * To adjust opening or closing transition type use lightbox option `showHideAnimationType` (`String`).
 * It supports three values - `zoom` (default), `fade` (default if there is no thumbnail) and `none`.
 *
 * Animations are automatically disabled if user `(prefers-reduced-motion: reduce)`.
 *
 * @prop {number} index
 * Defines start slide index.
 *
 * @prop {(e: MouseEvent) => number} [getClickedIndexFn]
 *
 * @prop {boolean} [arrowPrev]
 * @prop {boolean} [arrowNext]
 * @prop {boolean} [zoom]
 * @prop {boolean} [close]
 * @prop {boolean} [counter]
 *
 * @prop {string} [arrowPrevSVG]
 * @prop {string} [arrowNextSVG]
 * @prop {string} [zoomSVG]
 * @prop {string} [closeSVG]
 * @prop {string} [counterSVG]
 *
 * @prop {string} [arrowPrevTitle]
 * @prop {string} [arrowNextTitle]
 * @prop {string} [zoomTitle]
 * @prop {string} [closeTitle]
 * @prop {string} [counterTitle]
 *
 * @prop {ZoomLevelOption} [initialZoomLevel]
 * @prop {ZoomLevelOption} [secondaryZoomLevel]
 * @prop {ZoomLevelOption} [maxZoomLevel]
 *
 * @prop {boolean} [mouseMovePan]
 * @prop {Point | null} [initialPointerPos]
 * @prop {boolean} [showHideOpacity]
 *
 * @prop {PhotoSwipeModuleOption} [pswpModule]
 * @prop {() => Promise<any>} [openPromise]
 * @prop {boolean} [preloadFirstSlide]
 * @prop {ElementProvider} [gallery]
 * @prop {string} [gallerySelector]
 * @prop {ElementProvider} [children]
 * @prop {string} [childSelector]
 * @prop {string | false} [thumbSelector]
 */
/** @type {PreparedPhotoSwipeOptions} */
var defaultOptions = {
    allowPanToNext: true,
    spacing: 0.1,
    loop: true,
    pinchToClose: true,
    closeOnVerticalDrag: true,
    hideAnimationDuration: 333,
    showAnimationDuration: 333,
    zoomAnimationDuration: 333,
    escKey: true,
    arrowKeys: true,
    trapFocus: true,
    returnFocus: true,
    maxWidthToAnimate: 4000,
    clickToCloseNonZoomable: true,
    imageClickAction: 'zoom-or-close',
    bgClickAction: 'close',
    tapAction: 'toggle-controls',
    doubleTapAction: 'zoom',
    indexIndicatorSep: ' / ',
    preloaderDelay: 2000,
    bgOpacity: 0.8,
    index: 0,
    errorMsg: 'The image cannot be loaded',
    preload: [1, 2],
    easing: 'cubic-bezier(.4,0,.22,1)'
};
/**
 * PhotoSwipe Core
 */
var PhotoSwipe = /** @class */ (function (_super) {
    __extends(PhotoSwipe, _super);
    /**
     * @param {PhotoSwipeOptions} [options]
     */
    function PhotoSwipe(options) {
        var _this = _super.call(this) || this;
        _this.options = _this._prepareOptions(options || {});
        /**
         * offset of viewport relative to document
         *
         * @type {Point}
         */
        _this.offset = { x: 0, y: 0 };
        /**
         * @type {Point}
         * @private
         */
        _this._prevViewportSize = { x: 0, y: 0 };
        /**
         * Size of scrollable PhotoSwipe viewport
         *
         * @type {Point}
         */
        _this.viewportSize = { x: 0, y: 0 };
        /**
         * background (backdrop) opacity
         */
        _this.bgOpacity = 1;
        _this.currIndex = 0;
        _this.potentialIndex = 0;
        _this.isOpen = false;
        _this.isDestroying = false;
        _this.hasMouse = false;
        /**
         * @private
         * @type {SlideData}
         */
        _this._initialItemData = {};
        /** @type {Bounds | undefined} */
        _this._initialThumbBounds = undefined;
        /** @type {HTMLDivElement | undefined} */
        _this.topBar = undefined;
        /** @type {HTMLDivElement | undefined} */
        _this.element = undefined;
        /** @type {HTMLDivElement | undefined} */
        _this.template = undefined;
        /** @type {HTMLDivElement | undefined} */
        _this.container = undefined;
        /** @type {HTMLElement | undefined} */
        _this.scrollWrap = undefined;
        /** @type {Slide | undefined} */
        _this.currSlide = undefined;
        _this.events = new dom_events_js_1.default();
        _this.animations = new animations_js_1.default();
        _this.mainScroll = new main_scroll_js_1.default(_this);
        _this.gestures = new gestures_js_1.default(_this);
        _this.opener = new opener_js_1.default(_this);
        _this.keyboard = new keyboard_js_1.default(_this);
        _this.contentLoader = new loader_js_1.default(_this);
        return _this;
    }
    /** @returns {boolean} */
    PhotoSwipe.prototype.init = function () {
        var _this = this;
        if (this.isOpen || this.isDestroying) {
            return false;
        }
        this.isOpen = true;
        this.dispatch('init'); // legacy
        this.dispatch('beforeOpen');
        this._createMainStructure();
        // add classes to the root element of PhotoSwipe
        var rootClasses = 'pswp--open';
        if (this.gestures.supportsTouch) {
            rootClasses += ' pswp--touch';
        }
        if (this.options.mainClass) {
            rootClasses += ' ' + this.options.mainClass;
        }
        if (this.element) {
            this.element.className += ' ' + rootClasses;
        }
        this.currIndex = this.options.index || 0;
        this.potentialIndex = this.currIndex;
        this.dispatch('firstUpdate'); // starting index can be modified here
        // initialize scroll wheel handler to block the scroll
        this.scrollWheel = new scroll_wheel_js_1.default(this);
        // sanitize index
        if (Number.isNaN(this.currIndex)
            || this.currIndex < 0
            || this.currIndex >= this.getNumItems()) {
            this.currIndex = 0;
        }
        if (!this.gestures.supportsTouch) {
            // enable mouse features if no touch support detected
            this.mouseDetected();
        }
        // causes forced synchronous layout
        this.updateSize();
        this.offset.y = window.pageYOffset;
        this._initialItemData = this.getItemData(this.currIndex);
        this.dispatch('gettingData', {
            index: this.currIndex,
            data: this._initialItemData,
            slide: undefined
        });
        // *Layout* - calculate size and position of elements here
        this._initialThumbBounds = this.getThumbBounds();
        this.dispatch('initialLayout');
        this.on('openingAnimationEnd', function () {
            var itemHolders = _this.mainScroll.itemHolders;
            // Add content to the previous and next slide
            if (itemHolders[0]) {
                itemHolders[0].el.style.display = 'block';
                _this.setContent(itemHolders[0], _this.currIndex - 1);
            }
            if (itemHolders[2]) {
                itemHolders[2].el.style.display = 'block';
                _this.setContent(itemHolders[2], _this.currIndex + 1);
            }
            _this.appendHeavy();
            _this.contentLoader.updateLazy();
            _this.events.add(window, 'resize', _this._handlePageResize.bind(_this));
            _this.events.add(window, 'scroll', _this._updatePageScrollOffset.bind(_this));
            _this.dispatch('bindEvents');
        });
        // set content for center slide (first time)
        if (this.mainScroll.itemHolders[1]) {
            this.setContent(this.mainScroll.itemHolders[1], this.currIndex);
        }
        this.dispatch('change');
        this.opener.open();
        this.dispatch('afterInit');
        return true;
    };
    /**
     * Get looped slide index
     * (for example, -1 will return the last slide)
     *
     * @param {number} index
     * @returns {number}
     */
    PhotoSwipe.prototype.getLoopedIndex = function (index) {
        var numSlides = this.getNumItems();
        if (this.options.loop) {
            if (index > numSlides - 1) {
                index -= numSlides;
            }
            if (index < 0) {
                index += numSlides;
            }
        }
        return (0, util_js_1.clamp)(index, 0, numSlides - 1);
    };
    PhotoSwipe.prototype.appendHeavy = function () {
        this.mainScroll.itemHolders.forEach(function (itemHolder) {
            var _a;
            (_a = itemHolder.slide) === null || _a === void 0 ? void 0 : _a.appendHeavy();
        });
    };
    /**
     * Change the slide
     * @param {number} index New index
     */
    PhotoSwipe.prototype.goTo = function (index) {
        this.mainScroll.moveIndexBy(this.getLoopedIndex(index) - this.potentialIndex);
    };
    /**
     * Go to the next slide.
     */
    PhotoSwipe.prototype.next = function () {
        this.goTo(this.potentialIndex + 1);
    };
    /**
     * Go to the previous slide.
     */
    PhotoSwipe.prototype.prev = function () {
        this.goTo(this.potentialIndex - 1);
    };
    /**
     * @see slide/slide.js zoomTo
     *
     * @param {Parameters<Slide['zoomTo']>} args
     */
    PhotoSwipe.prototype.zoomTo = function () {
        var _a;
        var args = [];
        for (var _i = 0; _i < arguments.length; _i++) {
            args[_i] = arguments[_i];
        }
        (_a = this.currSlide) === null || _a === void 0 ? void 0 : _a.zoomTo.apply(_a, args);
    };
    /**
     * @see slide/slide.js toggleZoom
     */
    PhotoSwipe.prototype.toggleZoom = function () {
        var _a;
        (_a = this.currSlide) === null || _a === void 0 ? void 0 : _a.toggleZoom();
    };
    /**
     * Close the gallery.
     * After closing transition ends - destroy it
     */
    PhotoSwipe.prototype.close = function () {
        if (!this.opener.isOpen || this.isDestroying) {
            return;
        }
        this.isDestroying = true;
        this.dispatch('close');
        this.events.removeAll();
        this.opener.close();
    };
    /**
     * Destroys the gallery:
     * - instantly closes the gallery
     * - unbinds events,
     * - cleans intervals and timeouts
     * - removes elements from DOM
     */
    PhotoSwipe.prototype.destroy = function () {
        var _a;
        if (!this.isDestroying) {
            this.options.showHideAnimationType = 'none';
            this.close();
            return;
        }
        this.dispatch('destroy');
        this._listeners = {};
        if (this.scrollWrap) {
            this.scrollWrap.ontouchmove = null;
            this.scrollWrap.ontouchend = null;
        }
        (_a = this.element) === null || _a === void 0 ? void 0 : _a.remove();
        this.mainScroll.itemHolders.forEach(function (itemHolder) {
            var _a;
            (_a = itemHolder.slide) === null || _a === void 0 ? void 0 : _a.destroy();
        });
        this.contentLoader.destroy();
        this.events.removeAll();
    };
    /**
     * Refresh/reload content of a slide by its index
     *
     * @param {number} slideIndex
     */
    PhotoSwipe.prototype.refreshSlideContent = function (slideIndex) {
        var _this = this;
        this.contentLoader.removeByIndex(slideIndex);
        this.mainScroll.itemHolders.forEach(function (itemHolder, i) {
            var _a, _b, _c;
            var potentialHolderIndex = ((_b = (_a = _this.currSlide) === null || _a === void 0 ? void 0 : _a.index) !== null && _b !== void 0 ? _b : 0) - 1 + i;
            if (_this.canLoop()) {
                potentialHolderIndex = _this.getLoopedIndex(potentialHolderIndex);
            }
            if (potentialHolderIndex === slideIndex) {
                // set the new slide content
                _this.setContent(itemHolder, slideIndex, true);
                // activate the new slide if it's current
                if (i === 1) {
                    _this.currSlide = itemHolder.slide;
                    (_c = itemHolder.slide) === null || _c === void 0 ? void 0 : _c.setIsActive(true);
                }
            }
        });
        this.dispatch('change');
    };
    /**
     * Set slide content
     *
     * @param {ItemHolder} holder mainScroll.itemHolders array item
     * @param {number} index Slide index
     * @param {boolean} [force] If content should be set even if index wasn't changed
     */
    PhotoSwipe.prototype.setContent = function (holder, index, force) {
        if (this.canLoop()) {
            index = this.getLoopedIndex(index);
        }
        if (holder.slide) {
            if (holder.slide.index === index && !force) {
                // exit if holder already contains this slide
                // this could be common when just three slides are used
                return;
            }
            // destroy previous slide
            holder.slide.destroy();
            holder.slide = undefined;
        }
        // exit if no loop and index is out of bounds
        if (!this.canLoop() && (index < 0 || index >= this.getNumItems())) {
            return;
        }
        var itemData = this.getItemData(index);
        holder.slide = new slide_js_1.default(itemData, index, this);
        // set current slide
        if (index === this.currIndex) {
            this.currSlide = holder.slide;
        }
        holder.slide.append(holder.el);
    };
    /** @returns {Point} */
    PhotoSwipe.prototype.getViewportCenterPoint = function () {
        return {
            x: this.viewportSize.x / 2,
            y: this.viewportSize.y / 2
        };
    };
    /**
     * Update size of all elements.
     * Executed on init and on page resize.
     *
     * @param {boolean} [force] Update size even if size of viewport was not changed.
     */
    PhotoSwipe.prototype.updateSize = function (force) {
        // let item;
        // let itemIndex;
        if (this.isDestroying) {
            // exit if PhotoSwipe is closed or closing
            // (to avoid errors, as resize event might be delayed)
            return;
        }
        //const newWidth = this.scrollWrap.clientWidth;
        //const newHeight = this.scrollWrap.clientHeight;
        var newViewportSize = (0, viewport_size_js_1.getViewportSize)(this.options, this);
        if (!force && (0, util_js_1.pointsEqual)(newViewportSize, this._prevViewportSize)) {
            // Exit if dimensions were not changed
            return;
        }
        //this._prevViewportSize.x = newWidth;
        //this._prevViewportSize.y = newHeight;
        (0, util_js_1.equalizePoints)(this._prevViewportSize, newViewportSize);
        this.dispatch('beforeResize');
        (0, util_js_1.equalizePoints)(this.viewportSize, this._prevViewportSize);
        this._updatePageScrollOffset();
        this.dispatch('viewportSize');
        // Resize slides only after opener animation is finished
        // and don't re-calculate size on inital size update
        this.mainScroll.resize(this.opener.isOpen);
        if (!this.hasMouse && window.matchMedia('(any-hover: hover)').matches) {
            this.mouseDetected();
        }
        this.dispatch('resize');
    };
    /**
     * @param {number} opacity
     */
    PhotoSwipe.prototype.applyBgOpacity = function (opacity) {
        this.bgOpacity = Math.max(opacity, 0);
        if (this.bg) {
            this.bg.style.opacity = String(this.bgOpacity * this.options.bgOpacity);
        }
    };
    /**
     * Whether mouse is detected
     */
    PhotoSwipe.prototype.mouseDetected = function () {
        var _a;
        if (!this.hasMouse) {
            this.hasMouse = true;
            (_a = this.element) === null || _a === void 0 ? void 0 : _a.classList.add('pswp--has_mouse');
        }
    };
    /**
     * Page resize event handler
     *
     * @private
     */
    PhotoSwipe.prototype._handlePageResize = function () {
        var _this = this;
        this.updateSize();
        // In iOS webview, if element size depends on document size,
        // it'll be measured incorrectly in resize event
        //
        // https://bugs.webkit.org/show_bug.cgi?id=170595
        // https://hackernoon.com/onresize-event-broken-in-mobile-safari-d8469027bf4d
        if (/iPhone|iPad|iPod/i.test(window.navigator.userAgent)) {
            setTimeout(function () {
                _this.updateSize();
            }, 500);
        }
    };
    /**
     * Page scroll offset is used
     * to get correct coordinates
     * relative to PhotoSwipe viewport.
     *
     * @private
     */
    PhotoSwipe.prototype._updatePageScrollOffset = function () {
        this.setScrollOffset(0, window.pageYOffset);
    };
    /**
     * @param {number} x
     * @param {number} y
     */
    PhotoSwipe.prototype.setScrollOffset = function (x, y) {
        this.offset.x = x;
        this.offset.y = y;
        this.dispatch('updateScrollOffset');
    };
    /**
     * Create main HTML structure of PhotoSwipe,
     * and add it to DOM
     *
     * @private
     */
    PhotoSwipe.prototype._createMainStructure = function () {
        // root DOM element of PhotoSwipe (.pswp)
        this.element = (0, util_js_1.createElement)('pswp', 'div');
        this.element.setAttribute('tabindex', '-1');
        this.element.setAttribute('role', 'dialog');
        // template is legacy prop
        this.template = this.element;
        // Background is added as a separate element,
        // as animating opacity is faster than animating rgba()
        this.bg = (0, util_js_1.createElement)('pswp__bg', 'div', this.element);
        this.scrollWrap = (0, util_js_1.createElement)('pswp__scroll-wrap', 'section', this.element);
        this.container = (0, util_js_1.createElement)('pswp__container', 'div', this.scrollWrap);
        // aria pattern: carousel
        this.scrollWrap.setAttribute('aria-roledescription', 'carousel');
        this.container.setAttribute('aria-live', 'off');
        this.container.setAttribute('id', 'pswp__items');
        this.mainScroll.appendHolders();
        this.ui = new ui_js_1.default(this);
        this.ui.init();
        // append to DOM
        (this.options.appendToEl || document.body).appendChild(this.element);
    };
    /**
     * Get position and dimensions of small thumbnail
     *   {x:,y:,w:}
     *
     * Height is optional (calculated based on the large image)
     *
     * @returns {Bounds | undefined}
     */
    PhotoSwipe.prototype.getThumbBounds = function () {
        return (0, get_thumb_bounds_js_1.getThumbBounds)(this.currIndex, this.currSlide ? this.currSlide.data : this._initialItemData, this);
    };
    /**
     * If the PhotoSwipe can have continuous loop
     * @returns Boolean
     */
    PhotoSwipe.prototype.canLoop = function () {
        return (this.options.loop && this.getNumItems() > 2);
    };
    /**
     * @private
     * @param {PhotoSwipeOptions} options
     * @returns {PreparedPhotoSwipeOptions}
     */
    PhotoSwipe.prototype._prepareOptions = function (options) {
        if (window.matchMedia('(prefers-reduced-motion), (update: slow)').matches) {
            options.showHideAnimationType = 'none';
            options.zoomAnimationDuration = 0;
        }
        /** @type {PreparedPhotoSwipeOptions} */
        return __assign(__assign({}, defaultOptions), options);
    };
    return PhotoSwipe;
}(base_js_1.default));
exports.default = PhotoSwipe;
//# sourceMappingURL=photoswipe.js.map