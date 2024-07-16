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
var eventable_js_1 = require("./eventable.js");
var util_js_1 = require("../util/util.js");
var content_js_1 = require("../slide/content.js");
var loader_js_1 = require("../slide/loader.js");
/** @typedef {import("../photoswipe.js").default} PhotoSwipe */
/** @typedef {import("../slide/slide.js").SlideData} SlideData */
/**
 * PhotoSwipe base class that can retrieve data about every slide.
 * Shared by PhotoSwipe Core and PhotoSwipe Lightbox
 */
var PhotoSwipeBase = /** @class */ (function (_super) {
    __extends(PhotoSwipeBase, _super);
    function PhotoSwipeBase() {
        return _super !== null && _super.apply(this, arguments) || this;
    }
    /**
     * Get total number of slides
     *
     * @returns {number}
     */
    PhotoSwipeBase.prototype.getNumItems = function () {
        var _a;
        var numItems = 0;
        var dataSource = (_a = this.options) === null || _a === void 0 ? void 0 : _a.dataSource;
        if (dataSource && 'length' in dataSource) {
            // may be an array or just object with length property
            numItems = dataSource.length;
        }
        else if (dataSource && 'gallery' in dataSource) {
            // query DOM elements
            if (!dataSource.items) {
                dataSource.items = this._getGalleryDOMElements(dataSource.gallery);
            }
            if (dataSource.items) {
                numItems = dataSource.items.length;
            }
        }
        // legacy event, before filters were introduced
        var event = this.dispatch('numItems', {
            dataSource: dataSource,
            numItems: numItems
        });
        return this.applyFilters('numItems', event.numItems, dataSource);
    };
    /**
     * @param {SlideData} slideData
     * @param {number} index
     * @returns {Content}
     */
    PhotoSwipeBase.prototype.createContentFromData = function (slideData, index) {
        return new content_js_1.default(slideData, this, index);
    };
    /**
     * Get item data by index.
     *
     * "item data" should contain normalized information that PhotoSwipe needs to generate a slide.
     * For example, it may contain properties like
     * `src`, `srcset`, `w`, `h`, which will be used to generate a slide with image.
     *
     * @param {number} index
     * @returns {SlideData}
     */
    PhotoSwipeBase.prototype.getItemData = function (index) {
        var _a;
        var dataSource = (_a = this.options) === null || _a === void 0 ? void 0 : _a.dataSource;
        /** @type {SlideData | HTMLElement} */
        var dataSourceItem = {};
        if (Array.isArray(dataSource)) {
            // Datasource is an array of elements
            dataSourceItem = dataSource[index];
        }
        else if (dataSource && 'gallery' in dataSource) {
            // dataSource has gallery property,
            // thus it was created by Lightbox, based on
            // gallery and children options
            // query DOM elements
            if (!dataSource.items) {
                dataSource.items = this._getGalleryDOMElements(dataSource.gallery);
            }
            dataSourceItem = dataSource.items[index];
        }
        var itemData = dataSourceItem;
        if (itemData instanceof Element) {
            itemData = this._domElementToItemData(itemData);
        }
        // Dispatching the itemData event,
        // it's a legacy verion before filters were introduced
        var event = this.dispatch('itemData', {
            itemData: itemData || {},
            index: index
        });
        return this.applyFilters('itemData', event.itemData, index);
    };
    /**
     * Get array of gallery DOM elements,
     * based on childSelector and gallery element.
     *
     * @param {HTMLElement} galleryElement
     * @returns {HTMLElement[]}
     */
    PhotoSwipeBase.prototype._getGalleryDOMElements = function (galleryElement) {
        var _a, _b;
        if (((_a = this.options) === null || _a === void 0 ? void 0 : _a.children) || ((_b = this.options) === null || _b === void 0 ? void 0 : _b.childSelector)) {
            return (0, util_js_1.getElementsFromOption)(this.options.children, this.options.childSelector, galleryElement) || [];
        }
        return [galleryElement];
    };
    /**
     * Converts DOM element to item data object.
     *
     * @param {HTMLElement} element DOM element
     * @returns {SlideData}
     */
    PhotoSwipeBase.prototype._domElementToItemData = function (element) {
        var _a;
        /** @type {SlideData} */
        var itemData = {
            element: element
        };
        var linkEl = /** @type {HTMLAnchorElement} */ (element.tagName === 'A'
            ? element
            : element.querySelector('a'));
        if (linkEl) {
            // src comes from data-pswp-src attribute,
            // if it's empty link href is used
            itemData.src = linkEl.dataset.pswpSrc || linkEl.href;
            if (linkEl.dataset.pswpSrcset) {
                itemData.srcset = linkEl.dataset.pswpSrcset;
            }
            itemData.width = linkEl.dataset.pswpWidth ? parseInt(linkEl.dataset.pswpWidth, 10) : 0;
            itemData.height = linkEl.dataset.pswpHeight ? parseInt(linkEl.dataset.pswpHeight, 10) : 0;
            // support legacy w & h properties
            itemData.w = itemData.width;
            itemData.h = itemData.height;
            if (linkEl.dataset.pswpType) {
                itemData.type = linkEl.dataset.pswpType;
            }
            var thumbnailEl = element.querySelector('img');
            if (thumbnailEl) {
                // msrc is URL to placeholder image that's displayed before large image is loaded
                // by default it's displayed only for the first slide
                itemData.msrc = thumbnailEl.currentSrc || thumbnailEl.src;
                itemData.alt = (_a = thumbnailEl.getAttribute('alt')) !== null && _a !== void 0 ? _a : '';
            }
            if (linkEl.dataset.pswpCropped || linkEl.dataset.cropped) {
                itemData.thumbCropped = true;
            }
        }
        return this.applyFilters('domItemData', itemData, element, linkEl);
    };
    /**
     * Lazy-load by slide data
     *
     * @param {SlideData} itemData Data about the slide
     * @param {number} index
     * @returns {Content} Image that is being decoded or false.
     */
    PhotoSwipeBase.prototype.lazyLoadData = function (itemData, index) {
        return (0, loader_js_1.lazyLoadData)(itemData, this, index);
    };
    return PhotoSwipeBase;
}(eventable_js_1.default));
exports.default = PhotoSwipeBase;
//# sourceMappingURL=base.js.map