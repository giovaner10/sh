"use strict";
/** @typedef {import('../photoswipe.js').default} PhotoSwipe */
/** @typedef {import('../photoswipe.js').Point} Point */
Object.defineProperty(exports, "__esModule", { value: true });
/**
 * @typedef {_SlideData & Record<string, any>} SlideData
 * @typedef {Object} _SlideData
 * @prop {HTMLElement} [element] thumbnail element
 * @prop {string} [src] image URL
 * @prop {string} [srcset] image srcset
 * @prop {number} [w] image width (deprecated)
 * @prop {number} [h] image height (deprecated)
 * @prop {number} [width] image width
 * @prop {number} [height] image height
 * @prop {string} [msrc] placeholder image URL that's displayed before large image is loaded
 * @prop {string} [alt] image alt text
 * @prop {boolean} [thumbCropped] whether thumbnail is cropped client-side or not
 * @prop {string} [html] html content of a slide
 * @prop {'image' | 'html' | string} [type] slide type
 */
var util_js_1 = require("../util/util.js");
var pan_bounds_js_1 = require("./pan-bounds.js");
var zoom_level_js_1 = require("./zoom-level.js");
var viewport_size_js_1 = require("../util/viewport-size.js");
/**
 * Renders and allows to control a single slide
 */
var Slide = /** @class */ (function () {
    /**
     * @param {SlideData} data
     * @param {number} index
     * @param {PhotoSwipe} pswp
     */
    function Slide(data, index, pswp) {
        this.data = data;
        this.index = index;
        this.pswp = pswp;
        this.isActive = (index === pswp.currIndex);
        this.currentResolution = 0;
        /** @type {Point} */
        this.panAreaSize = { x: 0, y: 0 };
        /** @type {Point} */
        this.pan = { x: 0, y: 0 };
        this.isFirstSlide = (this.isActive && !pswp.opener.isOpen);
        this.zoomLevels = new zoom_level_js_1.default(pswp.options, data, index, pswp);
        this.pswp.dispatch('gettingData', {
            slide: this,
            data: this.data,
            index: index
        });
        this.content = this.pswp.contentLoader.getContentBySlide(this);
        this.container = (0, util_js_1.createElement)('pswp__zoom-wrap', 'div');
        /** @type {HTMLElement | null} */
        this.holderElement = null;
        this.currZoomLevel = 1;
        /** @type {number} */
        this.width = this.content.width;
        /** @type {number} */
        this.height = this.content.height;
        this.heavyAppended = false;
        this.bounds = new pan_bounds_js_1.default(this);
        this.prevDisplayedWidth = -1;
        this.prevDisplayedHeight = -1;
        this.pswp.dispatch('slideInit', { slide: this });
    }
    /**
     * If this slide is active/current/visible
     *
     * @param {boolean} isActive
     */
    Slide.prototype.setIsActive = function (isActive) {
        if (isActive && !this.isActive) {
            // slide just became active
            this.activate();
        }
        else if (!isActive && this.isActive) {
            // slide just became non-active
            this.deactivate();
        }
    };
    /**
     * Appends slide content to DOM
     *
     * @param {HTMLElement} holderElement
     */
    Slide.prototype.append = function (holderElement) {
        this.holderElement = holderElement;
        this.container.style.transformOrigin = '0 0';
        // Slide appended to DOM
        if (!this.data) {
            return;
        }
        this.calculateSize();
        this.load();
        this.updateContentSize();
        this.appendHeavy();
        this.holderElement.appendChild(this.container);
        this.zoomAndPanToInitial();
        this.pswp.dispatch('firstZoomPan', { slide: this });
        this.applyCurrentZoomPan();
        this.pswp.dispatch('afterSetContent', { slide: this });
        if (this.isActive) {
            this.activate();
        }
    };
    Slide.prototype.load = function () {
        this.content.load(false);
        this.pswp.dispatch('slideLoad', { slide: this });
    };
    /**
     * Append "heavy" DOM elements
     *
     * This may depend on a type of slide,
     * but generally these are large images.
     */
    Slide.prototype.appendHeavy = function () {
        var pswp = this.pswp;
        var appendHeavyNearby = true; // todo
        // Avoid appending heavy elements during animations
        if (this.heavyAppended
            || !pswp.opener.isOpen
            || pswp.mainScroll.isShifted()
            || (!this.isActive && !appendHeavyNearby)) {
            return;
        }
        if (this.pswp.dispatch('appendHeavy', { slide: this }).defaultPrevented) {
            return;
        }
        this.heavyAppended = true;
        this.content.append();
        this.pswp.dispatch('appendHeavyContent', { slide: this });
    };
    /**
     * Triggered when this slide is active (selected).
     *
     * If it's part of opening/closing transition -
     * activate() will trigger after the transition is ended.
     */
    Slide.prototype.activate = function () {
        this.isActive = true;
        this.appendHeavy();
        this.content.activate();
        this.pswp.dispatch('slideActivate', { slide: this });
    };
    /**
     * Triggered when this slide becomes inactive.
     *
     * Slide can become inactive only after it was active.
     */
    Slide.prototype.deactivate = function () {
        this.isActive = false;
        this.content.deactivate();
        if (this.currZoomLevel !== this.zoomLevels.initial) {
            // allow filtering
            this.calculateSize();
        }
        // reset zoom level
        this.currentResolution = 0;
        this.zoomAndPanToInitial();
        this.applyCurrentZoomPan();
        this.updateContentSize();
        this.pswp.dispatch('slideDeactivate', { slide: this });
    };
    /**
     * The slide should destroy itself, it will never be used again.
     * (unbind all events and destroy internal components)
     */
    Slide.prototype.destroy = function () {
        this.content.hasSlide = false;
        this.content.remove();
        this.container.remove();
        this.pswp.dispatch('slideDestroy', { slide: this });
    };
    Slide.prototype.resize = function () {
        if (this.currZoomLevel === this.zoomLevels.initial || !this.isActive) {
            // Keep initial zoom level if it was before the resize,
            // as well as when this slide is not active
            // Reset position and scale to original state
            this.calculateSize();
            this.currentResolution = 0;
            this.zoomAndPanToInitial();
            this.applyCurrentZoomPan();
            this.updateContentSize();
        }
        else {
            // readjust pan position if it's beyond the bounds
            this.calculateSize();
            this.bounds.update(this.currZoomLevel);
            this.panTo(this.pan.x, this.pan.y);
        }
    };
    /**
     * Apply size to current slide content,
     * based on the current resolution and scale.
     *
     * @param {boolean} [force] if size should be updated even if dimensions weren't changed
     */
    Slide.prototype.updateContentSize = function (force) {
        // Use initial zoom level
        // if resolution is not defined (user didn't zoom yet)
        var scaleMultiplier = this.currentResolution || this.zoomLevels.initial;
        if (!scaleMultiplier) {
            return;
        }
        var width = Math.round(this.width * scaleMultiplier) || this.pswp.viewportSize.x;
        var height = Math.round(this.height * scaleMultiplier) || this.pswp.viewportSize.y;
        if (!this.sizeChanged(width, height) && !force) {
            return;
        }
        this.content.setDisplayedSize(width, height);
    };
    /**
     * @param {number} width
     * @param {number} height
     */
    Slide.prototype.sizeChanged = function (width, height) {
        if (width !== this.prevDisplayedWidth
            || height !== this.prevDisplayedHeight) {
            this.prevDisplayedWidth = width;
            this.prevDisplayedHeight = height;
            return true;
        }
        return false;
    };
    /** @returns {HTMLImageElement | HTMLDivElement | null | undefined} */
    Slide.prototype.getPlaceholderElement = function () {
        var _a;
        return (_a = this.content.placeholder) === null || _a === void 0 ? void 0 : _a.element;
    };
    /**
     * Zoom current slide image to...
     *
     * @param {number} destZoomLevel Destination zoom level.
     * @param {Point} [centerPoint]
     * Transform origin center point, or false if viewport center should be used.
     * @param {number | false} [transitionDuration] Transition duration, may be set to 0.
     * @param {boolean} [ignoreBounds] Minimum and maximum zoom levels will be ignored.
     */
    Slide.prototype.zoomTo = function (destZoomLevel, centerPoint, transitionDuration, ignoreBounds) {
        var _this = this;
        var pswp = this.pswp;
        if (!this.isZoomable()
            || pswp.mainScroll.isShifted()) {
            return;
        }
        pswp.dispatch('beforeZoomTo', {
            destZoomLevel: destZoomLevel,
            centerPoint: centerPoint,
            transitionDuration: transitionDuration
        });
        // stop all pan and zoom transitions
        pswp.animations.stopAllPan();
        // if (!centerPoint) {
        //   centerPoint = pswp.getViewportCenterPoint();
        // }
        var prevZoomLevel = this.currZoomLevel;
        if (!ignoreBounds) {
            destZoomLevel = (0, util_js_1.clamp)(destZoomLevel, this.zoomLevels.min, this.zoomLevels.max);
        }
        // if (transitionDuration === undefined) {
        //   transitionDuration = this.pswp.options.zoomAnimationDuration;
        // }
        this.setZoomLevel(destZoomLevel);
        this.pan.x = this.calculateZoomToPanOffset('x', centerPoint, prevZoomLevel);
        this.pan.y = this.calculateZoomToPanOffset('y', centerPoint, prevZoomLevel);
        (0, util_js_1.roundPoint)(this.pan);
        var finishTransition = function () {
            _this._setResolution(destZoomLevel);
            _this.applyCurrentZoomPan();
        };
        if (!transitionDuration) {
            finishTransition();
        }
        else {
            pswp.animations.startTransition({
                isPan: true,
                name: 'zoomTo',
                target: this.container,
                transform: this.getCurrentTransform(),
                onComplete: finishTransition,
                duration: transitionDuration,
                easing: pswp.options.easing
            });
        }
    };
    /**
     * @param {Point} [centerPoint]
     */
    Slide.prototype.toggleZoom = function (centerPoint) {
        this.zoomTo(this.currZoomLevel === this.zoomLevels.initial
            ? this.zoomLevels.secondary : this.zoomLevels.initial, centerPoint, this.pswp.options.zoomAnimationDuration);
    };
    /**
     * Updates zoom level property and recalculates new pan bounds,
     * unlike zoomTo it does not apply transform (use applyCurrentZoomPan)
     *
     * @param {number} currZoomLevel
     */
    Slide.prototype.setZoomLevel = function (currZoomLevel) {
        this.currZoomLevel = currZoomLevel;
        this.bounds.update(this.currZoomLevel);
    };
    /**
     * Get pan position after zoom at a given `point`.
     *
     * Always call setZoomLevel(newZoomLevel) beforehand to recalculate
     * pan bounds according to the new zoom level.
     *
     * @param {'x' | 'y'} axis
     * @param {Point} [point]
     * point based on which zoom is performed, usually refers to the current mouse position,
     * if false - viewport center will be used.
     * @param {number} [prevZoomLevel] Zoom level before new zoom was applied.
     * @returns {number}
     */
    Slide.prototype.calculateZoomToPanOffset = function (axis, point, prevZoomLevel) {
        var totalPanDistance = this.bounds.max[axis] - this.bounds.min[axis];
        if (totalPanDistance === 0) {
            return this.bounds.center[axis];
        }
        if (!point) {
            point = this.pswp.getViewportCenterPoint();
        }
        if (!prevZoomLevel) {
            prevZoomLevel = this.zoomLevels.initial;
        }
        var zoomFactor = this.currZoomLevel / prevZoomLevel;
        return this.bounds.correctPan(axis, (this.pan[axis] - point[axis]) * zoomFactor + point[axis]);
    };
    /**
     * Apply pan and keep it within bounds.
     *
     * @param {number} panX
     * @param {number} panY
     */
    Slide.prototype.panTo = function (panX, panY) {
        this.pan.x = this.bounds.correctPan('x', panX);
        this.pan.y = this.bounds.correctPan('y', panY);
        this.applyCurrentZoomPan();
    };
    /**
     * If the slide in the current state can be panned by the user
     * @returns {boolean}
     */
    Slide.prototype.isPannable = function () {
        return Boolean(this.width) && (this.currZoomLevel > this.zoomLevels.fit);
    };
    /**
     * If the slide can be zoomed
     * @returns {boolean}
     */
    Slide.prototype.isZoomable = function () {
        return Boolean(this.width) && this.content.isZoomable();
    };
    /**
     * Apply transform and scale based on
     * the current pan position (this.pan) and zoom level (this.currZoomLevel)
     */
    Slide.prototype.applyCurrentZoomPan = function () {
        this._applyZoomTransform(this.pan.x, this.pan.y, this.currZoomLevel);
        if (this === this.pswp.currSlide) {
            this.pswp.dispatch('zoomPanUpdate', { slide: this });
        }
    };
    Slide.prototype.zoomAndPanToInitial = function () {
        this.currZoomLevel = this.zoomLevels.initial;
        // pan according to the zoom level
        this.bounds.update(this.currZoomLevel);
        (0, util_js_1.equalizePoints)(this.pan, this.bounds.center);
        this.pswp.dispatch('initialZoomPan', { slide: this });
    };
    /**
     * Set translate and scale based on current resolution
     *
     * @param {number} x
     * @param {number} y
     * @param {number} zoom
     * @private
     */
    Slide.prototype._applyZoomTransform = function (x, y, zoom) {
        zoom /= this.currentResolution || this.zoomLevels.initial;
        (0, util_js_1.setTransform)(this.container, x, y, zoom);
    };
    Slide.prototype.calculateSize = function () {
        var pswp = this.pswp;
        (0, util_js_1.equalizePoints)(this.panAreaSize, (0, viewport_size_js_1.getPanAreaSize)(pswp.options, pswp.viewportSize, this.data, this.index));
        this.zoomLevels.update(this.width, this.height, this.panAreaSize);
        pswp.dispatch('calcSlideSize', {
            slide: this
        });
    };
    /** @returns {string} */
    Slide.prototype.getCurrentTransform = function () {
        var scale = this.currZoomLevel / (this.currentResolution || this.zoomLevels.initial);
        return (0, util_js_1.toTransformString)(this.pan.x, this.pan.y, scale);
    };
    /**
     * Set resolution and re-render the image.
     *
     * For example, if the real image size is 2000x1500,
     * and resolution is 0.5 - it will be rendered as 1000x750.
     *
     * Image with zoom level 2 and resolution 0.5 is
     * the same as image with zoom level 1 and resolution 1.
     *
     * Used to optimize animations and make
     * sure that browser renders image in the highest quality.
     * Also used by responsive images to load the correct one.
     *
     * @param {number} newResolution
     */
    Slide.prototype._setResolution = function (newResolution) {
        if (newResolution === this.currentResolution) {
            return;
        }
        this.currentResolution = newResolution;
        this.updateContentSize();
        this.pswp.dispatch('resolutionChanged');
    };
    return Slide;
}());
exports.default = Slide;
//# sourceMappingURL=slide.js.map