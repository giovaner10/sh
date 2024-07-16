"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var util_js_1 = require("./util/util.js");
/** @typedef {import('./photoswipe.js').default} PhotoSwipe */
/** @typedef {import('./slide/get-thumb-bounds.js').Bounds} Bounds */
/** @typedef {import('./util/animations.js').AnimationProps} AnimationProps */
// some browsers do not paint
// elements which opacity is set to 0,
// since we need to pre-render elements for the animation -
// we set it to the minimum amount
var MIN_OPACITY = 0.003;
/**
 * Manages opening and closing transitions of the PhotoSwipe.
 *
 * It can perform zoom, fade or no transition.
 */
var Opener = /** @class */ (function () {
    /**
     * @param {PhotoSwipe} pswp
     */
    function Opener(pswp) {
        this.pswp = pswp;
        this.isClosed = true;
        this.isOpen = false;
        this.isClosing = false;
        this.isOpening = false;
        /**
         * @private
         * @type {number | false | undefined}
         */
        this._duration = undefined;
        /** @private */
        this._useAnimation = false;
        /** @private */
        this._croppedZoom = false;
        /** @private */
        this._animateRootOpacity = false;
        /** @private */
        this._animateBgOpacity = false;
        /**
         * @private
         * @type { HTMLDivElement | HTMLImageElement | null | undefined }
         */
        this._placeholder = undefined;
        /**
         * @private
         * @type { HTMLDivElement | undefined }
         */
        this._opacityElement = undefined;
        /**
         * @private
         * @type { HTMLDivElement | undefined }
         */
        this._cropContainer1 = undefined;
        /**
         * @private
         * @type { HTMLElement | null | undefined }
         */
        this._cropContainer2 = undefined;
        /**
         * @private
         * @type {Bounds | undefined}
         */
        this._thumbBounds = undefined;
        this._prepareOpen = this._prepareOpen.bind(this);
        // Override initial zoom and pan position
        pswp.on('firstZoomPan', this._prepareOpen);
    }
    Opener.prototype.open = function () {
        this._prepareOpen();
        this._start();
    };
    Opener.prototype.close = function () {
        var _this = this;
        if (this.isClosed || this.isClosing || this.isOpening) {
            // if we close during opening animation
            // for now do nothing,
            // browsers aren't good at changing the direction of the CSS transition
            return;
        }
        var slide = this.pswp.currSlide;
        this.isOpen = false;
        this.isOpening = false;
        this.isClosing = true;
        this._duration = this.pswp.options.hideAnimationDuration;
        if (slide && slide.currZoomLevel * slide.width >= this.pswp.options.maxWidthToAnimate) {
            this._duration = 0;
        }
        this._applyStartProps();
        setTimeout(function () {
            _this._start();
        }, this._croppedZoom ? 30 : 0);
    };
    /** @private */
    Opener.prototype._prepareOpen = function () {
        this.pswp.off('firstZoomPan', this._prepareOpen);
        if (!this.isOpening) {
            var slide = this.pswp.currSlide;
            this.isOpening = true;
            this.isClosing = false;
            this._duration = this.pswp.options.showAnimationDuration;
            if (slide && slide.zoomLevels.initial * slide.width >= this.pswp.options.maxWidthToAnimate) {
                this._duration = 0;
            }
            this._applyStartProps();
        }
    };
    /** @private */
    Opener.prototype._applyStartProps = function () {
        var _a, _b;
        var pswp = this.pswp;
        var slide = this.pswp.currSlide;
        var options = pswp.options;
        if (options.showHideAnimationType === 'fade') {
            options.showHideOpacity = true;
            this._thumbBounds = undefined;
        }
        else if (options.showHideAnimationType === 'none') {
            options.showHideOpacity = false;
            this._duration = 0;
            this._thumbBounds = undefined;
        }
        else if (this.isOpening && pswp._initialThumbBounds) {
            // Use initial bounds if defined
            this._thumbBounds = pswp._initialThumbBounds;
        }
        else {
            this._thumbBounds = this.pswp.getThumbBounds();
        }
        this._placeholder = slide === null || slide === void 0 ? void 0 : slide.getPlaceholderElement();
        pswp.animations.stopAll();
        // Discard animations when duration is less than 50ms
        this._useAnimation = Boolean(this._duration && this._duration > 50);
        this._animateZoom = Boolean(this._thumbBounds)
            && (slide === null || slide === void 0 ? void 0 : slide.content.usePlaceholder())
            && (!this.isClosing || !pswp.mainScroll.isShifted());
        if (!this._animateZoom) {
            this._animateRootOpacity = true;
            if (this.isOpening && slide) {
                slide.zoomAndPanToInitial();
                slide.applyCurrentZoomPan();
            }
        }
        else {
            this._animateRootOpacity = (_a = options.showHideOpacity) !== null && _a !== void 0 ? _a : false;
        }
        this._animateBgOpacity = !this._animateRootOpacity && this.pswp.options.bgOpacity > MIN_OPACITY;
        this._opacityElement = this._animateRootOpacity ? pswp.element : pswp.bg;
        if (!this._useAnimation) {
            this._duration = 0;
            this._animateZoom = false;
            this._animateBgOpacity = false;
            this._animateRootOpacity = true;
            if (this.isOpening) {
                if (pswp.element) {
                    pswp.element.style.opacity = String(MIN_OPACITY);
                }
                pswp.applyBgOpacity(1);
            }
            return;
        }
        if (this._animateZoom && this._thumbBounds && this._thumbBounds.innerRect) {
            // Properties are used when animation from cropped thumbnail
            this._croppedZoom = true;
            this._cropContainer1 = this.pswp.container;
            this._cropContainer2 = (_b = this.pswp.currSlide) === null || _b === void 0 ? void 0 : _b.holderElement;
            if (pswp.container) {
                pswp.container.style.overflow = 'hidden';
                pswp.container.style.width = pswp.viewportSize.x + 'px';
            }
        }
        else {
            this._croppedZoom = false;
        }
        if (this.isOpening) {
            // Apply styles before opening transition
            if (this._animateRootOpacity) {
                if (pswp.element) {
                    pswp.element.style.opacity = String(MIN_OPACITY);
                }
                pswp.applyBgOpacity(1);
            }
            else {
                if (this._animateBgOpacity && pswp.bg) {
                    pswp.bg.style.opacity = String(MIN_OPACITY);
                }
                if (pswp.element) {
                    pswp.element.style.opacity = '1';
                }
            }
            if (this._animateZoom) {
                this._setClosedStateZoomPan();
                if (this._placeholder) {
                    // tell browser that we plan to animate the placeholder
                    this._placeholder.style.willChange = 'transform';
                    // hide placeholder to allow hiding of
                    // elements that overlap it (such as icons over the thumbnail)
                    this._placeholder.style.opacity = String(MIN_OPACITY);
                }
            }
        }
        else if (this.isClosing) {
            // hide nearby slides to make sure that
            // they are not painted during the transition
            if (pswp.mainScroll.itemHolders[0]) {
                pswp.mainScroll.itemHolders[0].el.style.display = 'none';
            }
            if (pswp.mainScroll.itemHolders[2]) {
                pswp.mainScroll.itemHolders[2].el.style.display = 'none';
            }
            if (this._croppedZoom) {
                if (pswp.mainScroll.x !== 0) {
                    // shift the main scroller to zero position
                    pswp.mainScroll.resetPosition();
                    pswp.mainScroll.resize();
                }
            }
        }
    };
    /** @private */
    Opener.prototype._start = function () {
        var _this = this;
        if (this.isOpening
            && this._useAnimation
            && this._placeholder
            && this._placeholder.tagName === 'IMG') {
            // To ensure smooth animation
            // we wait till the current slide image placeholder is decoded,
            // but no longer than 250ms,
            // and no shorter than 50ms
            // (just using requestanimationframe is not enough in Firefox,
            // for some reason)
            new Promise(function (resolve) {
                var decoded = false;
                var isDelaying = true;
                (0, util_js_1.decodeImage)(/** @type {HTMLImageElement} */ (_this._placeholder)).finally(function () {
                    decoded = true;
                    if (!isDelaying) {
                        resolve(true);
                    }
                });
                setTimeout(function () {
                    isDelaying = false;
                    if (decoded) {
                        resolve(true);
                    }
                }, 50);
                setTimeout(resolve, 250);
            }).finally(function () { return _this._initiate(); });
        }
        else {
            this._initiate();
        }
    };
    /** @private */
    Opener.prototype._initiate = function () {
        var _a, _b;
        (_a = this.pswp.element) === null || _a === void 0 ? void 0 : _a.style.setProperty('--pswp-transition-duration', this._duration + 'ms');
        this.pswp.dispatch(this.isOpening ? 'openingAnimationStart' : 'closingAnimationStart');
        // legacy event
        this.pswp.dispatch(
        /** @type {'initialZoomIn' | 'initialZoomOut'} */
        ('initialZoom' + (this.isOpening ? 'In' : 'Out')));
        (_b = this.pswp.element) === null || _b === void 0 ? void 0 : _b.classList.toggle('pswp--ui-visible', this.isOpening);
        if (this.isOpening) {
            if (this._placeholder) {
                // unhide the placeholder
                this._placeholder.style.opacity = '1';
            }
            this._animateToOpenState();
        }
        else if (this.isClosing) {
            this._animateToClosedState();
        }
        if (!this._useAnimation) {
            this._onAnimationComplete();
        }
    };
    /** @private */
    Opener.prototype._onAnimationComplete = function () {
        var _a;
        var pswp = this.pswp;
        this.isOpen = this.isOpening;
        this.isClosed = this.isClosing;
        this.isOpening = false;
        this.isClosing = false;
        pswp.dispatch(this.isOpen ? 'openingAnimationEnd' : 'closingAnimationEnd');
        // legacy event
        pswp.dispatch(
        /** @type {'initialZoomInEnd' | 'initialZoomOutEnd'} */
        ('initialZoom' + (this.isOpen ? 'InEnd' : 'OutEnd')));
        if (this.isClosed) {
            pswp.destroy();
        }
        else if (this.isOpen) {
            if (this._animateZoom && pswp.container) {
                pswp.container.style.overflow = 'visible';
                pswp.container.style.width = '100%';
            }
            (_a = pswp.currSlide) === null || _a === void 0 ? void 0 : _a.applyCurrentZoomPan();
        }
    };
    /** @private */
    Opener.prototype._animateToOpenState = function () {
        var pswp = this.pswp;
        if (this._animateZoom) {
            if (this._croppedZoom && this._cropContainer1 && this._cropContainer2) {
                this._animateTo(this._cropContainer1, 'transform', 'translate3d(0,0,0)');
                this._animateTo(this._cropContainer2, 'transform', 'none');
            }
            if (pswp.currSlide) {
                pswp.currSlide.zoomAndPanToInitial();
                this._animateTo(pswp.currSlide.container, 'transform', pswp.currSlide.getCurrentTransform());
            }
        }
        if (this._animateBgOpacity && pswp.bg) {
            this._animateTo(pswp.bg, 'opacity', String(pswp.options.bgOpacity));
        }
        if (this._animateRootOpacity && pswp.element) {
            this._animateTo(pswp.element, 'opacity', '1');
        }
    };
    /** @private */
    Opener.prototype._animateToClosedState = function () {
        var pswp = this.pswp;
        if (this._animateZoom) {
            this._setClosedStateZoomPan(true);
        }
        // do not animate opacity if it's already at 0
        if (this._animateBgOpacity && pswp.bgOpacity > 0.01 && pswp.bg) {
            this._animateTo(pswp.bg, 'opacity', '0');
        }
        if (this._animateRootOpacity && pswp.element) {
            this._animateTo(pswp.element, 'opacity', '0');
        }
    };
    /**
     * @private
     * @param {boolean} [animate]
     */
    Opener.prototype._setClosedStateZoomPan = function (animate) {
        if (!this._thumbBounds)
            return;
        var pswp = this.pswp;
        var innerRect = this._thumbBounds.innerRect;
        var currSlide = pswp.currSlide, viewportSize = pswp.viewportSize;
        if (this._croppedZoom && innerRect && this._cropContainer1 && this._cropContainer2) {
            var containerOnePanX = -viewportSize.x + (this._thumbBounds.x - innerRect.x) + innerRect.w;
            var containerOnePanY = -viewportSize.y + (this._thumbBounds.y - innerRect.y) + innerRect.h;
            var containerTwoPanX = viewportSize.x - innerRect.w;
            var containerTwoPanY = viewportSize.y - innerRect.h;
            if (animate) {
                this._animateTo(this._cropContainer1, 'transform', (0, util_js_1.toTransformString)(containerOnePanX, containerOnePanY));
                this._animateTo(this._cropContainer2, 'transform', (0, util_js_1.toTransformString)(containerTwoPanX, containerTwoPanY));
            }
            else {
                (0, util_js_1.setTransform)(this._cropContainer1, containerOnePanX, containerOnePanY);
                (0, util_js_1.setTransform)(this._cropContainer2, containerTwoPanX, containerTwoPanY);
            }
        }
        if (currSlide) {
            (0, util_js_1.equalizePoints)(currSlide.pan, innerRect || this._thumbBounds);
            currSlide.currZoomLevel = this._thumbBounds.w / currSlide.width;
            if (animate) {
                this._animateTo(currSlide.container, 'transform', currSlide.getCurrentTransform());
            }
            else {
                currSlide.applyCurrentZoomPan();
            }
        }
    };
    /**
     * @private
     * @param {HTMLElement} target
     * @param {'transform' | 'opacity'} prop
     * @param {string} propValue
     */
    Opener.prototype._animateTo = function (target, prop, propValue) {
        var _this = this;
        if (!this._duration) {
            target.style[prop] = propValue;
            return;
        }
        var animations = this.pswp.animations;
        /** @type {AnimationProps} */
        var animProps = {
            duration: this._duration,
            easing: this.pswp.options.easing,
            onComplete: function () {
                if (!animations.activeAnimations.length) {
                    _this._onAnimationComplete();
                }
            },
            target: target,
        };
        animProps[prop] = propValue;
        animations.startTransition(animProps);
    };
    return Opener;
}());
exports.default = Opener;
//# sourceMappingURL=opener.js.map