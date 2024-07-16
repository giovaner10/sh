"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.lazyLoadSlide = exports.lazyLoadData = void 0;
var viewport_size_js_1 = require("../util/viewport-size.js");
var zoom_level_js_1 = require("./zoom-level.js");
/** @typedef {import('./content.js').default} Content */
/** @typedef {import('./slide.js').default} Slide */
/** @typedef {import('./slide.js').SlideData} SlideData */
/** @typedef {import('../core/base.js').default} PhotoSwipeBase */
/** @typedef {import('../photoswipe.js').default} PhotoSwipe */
var MIN_SLIDES_TO_CACHE = 5;
/**
 * Lazy-load an image
 * This function is used both by Lightbox and PhotoSwipe core,
 * thus it can be called before dialog is opened.
 *
 * @param {SlideData} itemData Data about the slide
 * @param {PhotoSwipeBase} instance PhotoSwipe or PhotoSwipeLightbox instance
 * @param {number} index
 * @returns {Content} Image that is being decoded or false.
 */
function lazyLoadData(itemData, instance, index) {
    var content = instance.createContentFromData(itemData, index);
    /** @type {ZoomLevel | undefined} */
    var zoomLevel;
    var options = instance.options;
    // We need to know dimensions of the image to preload it,
    // as it might use srcset, and we need to define sizes
    if (options) {
        zoomLevel = new zoom_level_js_1.default(options, itemData, -1);
        var viewportSize = void 0;
        if (instance.pswp) {
            viewportSize = instance.pswp.viewportSize;
        }
        else {
            viewportSize = (0, viewport_size_js_1.getViewportSize)(options, instance);
        }
        var panAreaSize = (0, viewport_size_js_1.getPanAreaSize)(options, viewportSize, itemData, index);
        zoomLevel.update(content.width, content.height, panAreaSize);
    }
    content.lazyLoad();
    if (zoomLevel) {
        content.setDisplayedSize(Math.ceil(content.width * zoomLevel.initial), Math.ceil(content.height * zoomLevel.initial));
    }
    return content;
}
exports.lazyLoadData = lazyLoadData;
/**
 * Lazy-loads specific slide.
 * This function is used both by Lightbox and PhotoSwipe core,
 * thus it can be called before dialog is opened.
 *
 * By default, it loads image based on viewport size and initial zoom level.
 *
 * @param {number} index Slide index
 * @param {PhotoSwipeBase} instance PhotoSwipe or PhotoSwipeLightbox eventable instance
 * @returns {Content | undefined}
 */
function lazyLoadSlide(index, instance) {
    var itemData = instance.getItemData(index);
    if (instance.dispatch('lazyLoadSlide', { index: index, itemData: itemData }).defaultPrevented) {
        return;
    }
    return lazyLoadData(itemData, instance, index);
}
exports.lazyLoadSlide = lazyLoadSlide;
var ContentLoader = /** @class */ (function () {
    /**
     * @param {PhotoSwipe} pswp
     */
    function ContentLoader(pswp) {
        this.pswp = pswp;
        // Total amount of cached images
        this.limit = Math.max(pswp.options.preload[0] + pswp.options.preload[1] + 1, MIN_SLIDES_TO_CACHE);
        /** @type {Content[]} */
        this._cachedItems = [];
    }
    /**
     * Lazy load nearby slides based on `preload` option.
     *
     * @param {number} [diff] Difference between slide indexes that was changed recently, or 0.
     */
    ContentLoader.prototype.updateLazy = function (diff) {
        var pswp = this.pswp;
        if (pswp.dispatch('lazyLoad').defaultPrevented) {
            return;
        }
        var preload = pswp.options.preload;
        var isForward = diff === undefined ? true : (diff >= 0);
        var i;
        // preload[1] - num items to preload in forward direction
        for (i = 0; i <= preload[1]; i++) {
            this.loadSlideByIndex(pswp.currIndex + (isForward ? i : (-i)));
        }
        // preload[0] - num items to preload in backward direction
        for (i = 1; i <= preload[0]; i++) {
            this.loadSlideByIndex(pswp.currIndex + (isForward ? (-i) : i));
        }
    };
    /**
     * @param {number} initialIndex
     */
    ContentLoader.prototype.loadSlideByIndex = function (initialIndex) {
        var index = this.pswp.getLoopedIndex(initialIndex);
        // try to get cached content
        var content = this.getContentByIndex(index);
        if (!content) {
            // no cached content, so try to load from scratch:
            content = lazyLoadSlide(index, this.pswp);
            // if content can be loaded, add it to cache:
            if (content) {
                this.addToCache(content);
            }
        }
    };
    /**
     * @param {Slide} slide
     * @returns {Content}
     */
    ContentLoader.prototype.getContentBySlide = function (slide) {
        var content = this.getContentByIndex(slide.index);
        if (!content) {
            // create content if not found in cache
            content = this.pswp.createContentFromData(slide.data, slide.index);
            this.addToCache(content);
        }
        // assign slide to content
        content.setSlide(slide);
        return content;
    };
    /**
     * @param {Content} content
     */
    ContentLoader.prototype.addToCache = function (content) {
        // move to the end of array
        this.removeByIndex(content.index);
        this._cachedItems.push(content);
        if (this._cachedItems.length > this.limit) {
            // Destroy the first content that's not attached
            var indexToRemove = this._cachedItems.findIndex(function (item) {
                return !item.isAttached && !item.hasSlide;
            });
            if (indexToRemove !== -1) {
                var removedItem = this._cachedItems.splice(indexToRemove, 1)[0];
                removedItem.destroy();
            }
        }
    };
    /**
     * Removes an image from cache, does not destroy() it, just removes.
     *
     * @param {number} index
     */
    ContentLoader.prototype.removeByIndex = function (index) {
        var indexToRemove = this._cachedItems.findIndex(function (item) { return item.index === index; });
        if (indexToRemove !== -1) {
            this._cachedItems.splice(indexToRemove, 1);
        }
    };
    /**
     * @param {number} index
     * @returns {Content | undefined}
     */
    ContentLoader.prototype.getContentByIndex = function (index) {
        return this._cachedItems.find(function (content) { return content.index === index; });
    };
    ContentLoader.prototype.destroy = function () {
        this._cachedItems.forEach(function (content) { return content.destroy(); });
        this._cachedItems = [];
    };
    return ContentLoader;
}());
exports.default = ContentLoader;
//# sourceMappingURL=loader.js.map