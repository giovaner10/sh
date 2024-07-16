"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var util_js_1 = require("../util/util.js");
var placeholder_js_1 = require("./placeholder.js");
/** @typedef {import('./slide.js').default} Slide */
/** @typedef {import('./slide.js').SlideData} SlideData */
/** @typedef {import('../core/base.js').default} PhotoSwipeBase */
/** @typedef {import('../util/util.js').LoadState} LoadState */
var Content = /** @class */ (function () {
    /**
     * @param {SlideData} itemData Slide data
     * @param {PhotoSwipeBase} instance PhotoSwipe or PhotoSwipeLightbox instance
     * @param {number} index
     */
    function Content(itemData, instance, index) {
        this.instance = instance;
        this.data = itemData;
        this.index = index;
        /** @type {HTMLImageElement | HTMLDivElement | undefined} */
        this.element = undefined;
        /** @type {Placeholder | undefined} */
        this.placeholder = undefined;
        /** @type {Slide | undefined} */
        this.slide = undefined;
        this.displayedImageWidth = 0;
        this.displayedImageHeight = 0;
        this.width = Number(this.data.w) || Number(this.data.width) || 0;
        this.height = Number(this.data.h) || Number(this.data.height) || 0;
        this.isAttached = false;
        this.hasSlide = false;
        this.isDecoding = false;
        /** @type {LoadState} */
        this.state = util_js_1.LOAD_STATE.IDLE;
        if (this.data.type) {
            this.type = this.data.type;
        }
        else if (this.data.src) {
            this.type = 'image';
        }
        else {
            this.type = 'html';
        }
        this.instance.dispatch('contentInit', { content: this });
    }
    Content.prototype.removePlaceholder = function () {
        var _this = this;
        if (this.placeholder && !this.keepPlaceholder()) {
            // With delay, as image might be loaded, but not rendered
            setTimeout(function () {
                if (_this.placeholder) {
                    _this.placeholder.destroy();
                    _this.placeholder = undefined;
                }
            }, 1000);
        }
    };
    /**
     * Preload content
     *
     * @param {boolean} isLazy
     * @param {boolean} [reload]
     */
    Content.prototype.load = function (isLazy, reload) {
        if (this.slide && this.usePlaceholder()) {
            if (!this.placeholder) {
                var placeholderSrc = this.instance.applyFilters('placeholderSrc', 
                // use  image-based placeholder only for the first slide,
                // as rendering (even small stretched thumbnail) is an expensive operation
                (this.data.msrc && this.slide.isFirstSlide) ? this.data.msrc : false, this);
                this.placeholder = new placeholder_js_1.default(placeholderSrc, this.slide.container);
            }
            else {
                var placeholderEl = this.placeholder.element;
                // Add placeholder to DOM if it was already created
                if (placeholderEl && !placeholderEl.parentElement) {
                    this.slide.container.prepend(placeholderEl);
                }
            }
        }
        if (this.element && !reload) {
            return;
        }
        if (this.instance.dispatch('contentLoad', { content: this, isLazy: isLazy }).defaultPrevented) {
            return;
        }
        if (this.isImageContent()) {
            this.element = (0, util_js_1.createElement)('pswp__img', 'img');
            // Start loading only after width is defined, as sizes might depend on it.
            // Due to Safari feature, we must define sizes before srcset.
            if (this.displayedImageWidth) {
                this.loadImage(isLazy);
            }
        }
        else {
            this.element = (0, util_js_1.createElement)('pswp__content', 'div');
            this.element.innerHTML = this.data.html || '';
        }
        if (reload && this.slide) {
            this.slide.updateContentSize(true);
        }
    };
    /**
     * Preload image
     *
     * @param {boolean} isLazy
     */
    Content.prototype.loadImage = function (isLazy) {
        var _this = this;
        var _a, _b;
        if (!this.isImageContent()
            || !this.element
            || this.instance.dispatch('contentLoadImage', { content: this, isLazy: isLazy }).defaultPrevented) {
            return;
        }
        var imageElement = /** @type HTMLImageElement */ (this.element);
        this.updateSrcsetSizes();
        if (this.data.srcset) {
            imageElement.srcset = this.data.srcset;
        }
        imageElement.src = (_a = this.data.src) !== null && _a !== void 0 ? _a : '';
        imageElement.alt = (_b = this.data.alt) !== null && _b !== void 0 ? _b : '';
        this.state = util_js_1.LOAD_STATE.LOADING;
        if (imageElement.complete) {
            this.onLoaded();
        }
        else {
            imageElement.onload = function () {
                _this.onLoaded();
            };
            imageElement.onerror = function () {
                _this.onError();
            };
        }
    };
    /**
     * Assign slide to content
     *
     * @param {Slide} slide
     */
    Content.prototype.setSlide = function (slide) {
        this.slide = slide;
        this.hasSlide = true;
        this.instance = slide.pswp;
        // todo: do we need to unset slide?
    };
    /**
     * Content load success handler
     */
    Content.prototype.onLoaded = function () {
        this.state = util_js_1.LOAD_STATE.LOADED;
        if (this.slide && this.element) {
            this.instance.dispatch('loadComplete', { slide: this.slide, content: this });
            // if content is reloaded
            if (this.slide.isActive
                && this.slide.heavyAppended
                && !this.element.parentNode) {
                this.append();
                this.slide.updateContentSize(true);
            }
            if (this.state === util_js_1.LOAD_STATE.LOADED || this.state === util_js_1.LOAD_STATE.ERROR) {
                this.removePlaceholder();
            }
        }
    };
    /**
     * Content load error handler
     */
    Content.prototype.onError = function () {
        this.state = util_js_1.LOAD_STATE.ERROR;
        if (this.slide) {
            this.displayError();
            this.instance.dispatch('loadComplete', { slide: this.slide, isError: true, content: this });
            this.instance.dispatch('loadError', { slide: this.slide, content: this });
        }
    };
    /**
     * @returns {Boolean} If the content is currently loading
     */
    Content.prototype.isLoading = function () {
        return this.instance.applyFilters('isContentLoading', this.state === util_js_1.LOAD_STATE.LOADING, this);
    };
    /**
     * @returns {Boolean} If the content is in error state
     */
    Content.prototype.isError = function () {
        return this.state === util_js_1.LOAD_STATE.ERROR;
    };
    /**
     * @returns {boolean} If the content is image
     */
    Content.prototype.isImageContent = function () {
        return this.type === 'image';
    };
    /**
     * Update content size
     *
     * @param {Number} width
     * @param {Number} height
     */
    Content.prototype.setDisplayedSize = function (width, height) {
        if (!this.element) {
            return;
        }
        if (this.placeholder) {
            this.placeholder.setDisplayedSize(width, height);
        }
        if (this.instance.dispatch('contentResize', { content: this, width: width, height: height }).defaultPrevented) {
            return;
        }
        (0, util_js_1.setWidthHeight)(this.element, width, height);
        if (this.isImageContent() && !this.isError()) {
            var isInitialSizeUpdate = (!this.displayedImageWidth && width);
            this.displayedImageWidth = width;
            this.displayedImageHeight = height;
            if (isInitialSizeUpdate) {
                this.loadImage(false);
            }
            else {
                this.updateSrcsetSizes();
            }
            if (this.slide) {
                this.instance.dispatch('imageSizeChange', { slide: this.slide, width: width, height: height, content: this });
            }
        }
    };
    /**
     * @returns {boolean} If the content can be zoomed
     */
    Content.prototype.isZoomable = function () {
        return this.instance.applyFilters('isContentZoomable', this.isImageContent() && (this.state !== util_js_1.LOAD_STATE.ERROR), this);
    };
    /**
     * Update image srcset sizes attribute based on width and height
     */
    Content.prototype.updateSrcsetSizes = function () {
        // Handle srcset sizes attribute.
        //
        // Never lower quality, if it was increased previously.
        // Chrome does this automatically, Firefox and Safari do not,
        // so we store largest used size in dataset.
        if (!this.isImageContent() || !this.element || !this.data.srcset) {
            return;
        }
        var image = /** @type HTMLImageElement */ (this.element);
        var sizesWidth = this.instance.applyFilters('srcsetSizesWidth', this.displayedImageWidth, this);
        if (!image.dataset.largestUsedSize
            || sizesWidth > parseInt(image.dataset.largestUsedSize, 10)) {
            image.sizes = sizesWidth + 'px';
            image.dataset.largestUsedSize = String(sizesWidth);
        }
    };
    /**
     * @returns {boolean} If content should use a placeholder (from msrc by default)
     */
    Content.prototype.usePlaceholder = function () {
        return this.instance.applyFilters('useContentPlaceholder', this.isImageContent(), this);
    };
    /**
     * Preload content with lazy-loading param
     */
    Content.prototype.lazyLoad = function () {
        if (this.instance.dispatch('contentLazyLoad', { content: this }).defaultPrevented) {
            return;
        }
        this.load(true);
    };
    /**
     * @returns {boolean} If placeholder should be kept after content is loaded
     */
    Content.prototype.keepPlaceholder = function () {
        return this.instance.applyFilters('isKeepingPlaceholder', this.isLoading(), this);
    };
    /**
     * Destroy the content
     */
    Content.prototype.destroy = function () {
        this.hasSlide = false;
        this.slide = undefined;
        if (this.instance.dispatch('contentDestroy', { content: this }).defaultPrevented) {
            return;
        }
        this.remove();
        if (this.placeholder) {
            this.placeholder.destroy();
            this.placeholder = undefined;
        }
        if (this.isImageContent() && this.element) {
            this.element.onload = null;
            this.element.onerror = null;
            this.element = undefined;
        }
    };
    /**
     * Display error message
     */
    Content.prototype.displayError = function () {
        var _a, _b;
        if (this.slide) {
            var errorMsgEl = (0, util_js_1.createElement)('pswp__error-msg', 'div');
            errorMsgEl.innerText = (_b = (_a = this.instance.options) === null || _a === void 0 ? void 0 : _a.errorMsg) !== null && _b !== void 0 ? _b : '';
            errorMsgEl = /** @type {HTMLDivElement} */ (this.instance.applyFilters('contentErrorElement', errorMsgEl, this));
            this.element = (0, util_js_1.createElement)('pswp__content pswp__error-msg-container', 'div');
            this.element.appendChild(errorMsgEl);
            this.slide.container.innerText = '';
            this.slide.container.appendChild(this.element);
            this.slide.updateContentSize(true);
            this.removePlaceholder();
        }
    };
    /**
     * Append the content
     */
    Content.prototype.append = function () {
        var _this = this;
        if (this.isAttached || !this.element) {
            return;
        }
        this.isAttached = true;
        if (this.state === util_js_1.LOAD_STATE.ERROR) {
            this.displayError();
            return;
        }
        if (this.instance.dispatch('contentAppend', { content: this }).defaultPrevented) {
            return;
        }
        var supportsDecode = ('decode' in this.element);
        if (this.isImageContent()) {
            // Use decode() on nearby slides
            //
            // Nearby slide images are in DOM and not hidden via display:none.
            // However, they are placed offscreen (to the left and right side).
            //
            // Some browsers do not composite the image until it's actually visible,
            // using decode() helps.
            //
            // You might ask "why dont you just decode() and then append all images",
            // that's because I want to show image before it's fully loaded,
            // as browser can render parts of image while it is loading.
            // We do not do this in Safari due to partial loading bug.
            if (supportsDecode && this.slide && (!this.slide.isActive || (0, util_js_1.isSafari)())) {
                this.isDecoding = true;
                // purposefully using finally instead of then,
                // as if srcset sizes changes dynamically - it may cause decode error
                /** @type {HTMLImageElement} */
                (this.element).decode().catch(function () { }).finally(function () {
                    _this.isDecoding = false;
                    _this.appendImage();
                });
            }
            else {
                this.appendImage();
            }
        }
        else if (this.slide && !this.element.parentNode) {
            this.slide.container.appendChild(this.element);
        }
    };
    /**
     * Activate the slide,
     * active slide is generally the current one,
     * meaning the user can see it.
     */
    Content.prototype.activate = function () {
        if (this.instance.dispatch('contentActivate', { content: this }).defaultPrevented
            || !this.slide) {
            return;
        }
        if (this.isImageContent() && this.isDecoding && !(0, util_js_1.isSafari)()) {
            // add image to slide when it becomes active,
            // even if it's not finished decoding
            this.appendImage();
        }
        else if (this.isError()) {
            this.load(false, true); // try to reload
        }
        if (this.slide.holderElement) {
            this.slide.holderElement.setAttribute('aria-hidden', 'false');
        }
    };
    /**
     * Deactivate the content
     */
    Content.prototype.deactivate = function () {
        this.instance.dispatch('contentDeactivate', { content: this });
        if (this.slide && this.slide.holderElement) {
            this.slide.holderElement.setAttribute('aria-hidden', 'true');
        }
    };
    /**
     * Remove the content from DOM
     */
    Content.prototype.remove = function () {
        this.isAttached = false;
        if (this.instance.dispatch('contentRemove', { content: this }).defaultPrevented) {
            return;
        }
        if (this.element && this.element.parentNode) {
            this.element.remove();
        }
        if (this.placeholder && this.placeholder.element) {
            this.placeholder.element.remove();
        }
    };
    /**
     * Append the image content to slide container
     */
    Content.prototype.appendImage = function () {
        if (!this.isAttached) {
            return;
        }
        if (this.instance.dispatch('contentAppendImage', { content: this }).defaultPrevented) {
            return;
        }
        // ensure that element exists and is not already appended
        if (this.slide && this.element && !this.element.parentNode) {
            this.slide.container.appendChild(this.element);
        }
        if (this.state === util_js_1.LOAD_STATE.LOADED || this.state === util_js_1.LOAD_STATE.ERROR) {
            this.removePlaceholder();
        }
    };
    return Content;
}());
exports.default = Content;
//# sourceMappingURL=content.js.map