"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var util_js_1 = require("./util/util.js");
/** @typedef {import('./photoswipe.js').default} PhotoSwipe */
/** @typedef {import('./slide/slide.js').default} Slide */
/** @typedef {{ el: HTMLDivElement; slide?: Slide }} ItemHolder */
var MAIN_SCROLL_END_FRICTION = 0.35;
// const MIN_SWIPE_TRANSITION_DURATION = 250;
// const MAX_SWIPE_TRABSITION_DURATION = 500;
// const DEFAULT_SWIPE_TRANSITION_DURATION = 333;
/**
 * Handles movement of the main scrolling container
 * (for example, it repositions when user swipes left or right).
 *
 * Also stores its state.
 */
var MainScroll = /** @class */ (function () {
    /**
     * @param {PhotoSwipe} pswp
     */
    function MainScroll(pswp) {
        this.pswp = pswp;
        this.x = 0;
        this.slideWidth = 0;
        /** @private */
        this._currPositionIndex = 0;
        /** @private */
        this._prevPositionIndex = 0;
        /** @private */
        this._containerShiftIndex = -1;
        /** @type {ItemHolder[]} */
        this.itemHolders = [];
    }
    /**
     * Position the scroller and slide containers
     * according to viewport size.
     *
     * @param {boolean} [resizeSlides] Whether slides content should resized
     */
    MainScroll.prototype.resize = function (resizeSlides) {
        var _this = this;
        var pswp = this.pswp;
        var newSlideWidth = Math.round(pswp.viewportSize.x + pswp.viewportSize.x * pswp.options.spacing);
        // Mobile browsers might trigger a resize event during a gesture.
        // (due to toolbar appearing or hiding).
        // Avoid re-adjusting main scroll position if width wasn't changed
        var slideWidthChanged = (newSlideWidth !== this.slideWidth);
        if (slideWidthChanged) {
            this.slideWidth = newSlideWidth;
            this.moveTo(this.getCurrSlideX());
        }
        this.itemHolders.forEach(function (itemHolder, index) {
            if (slideWidthChanged) {
                (0, util_js_1.setTransform)(itemHolder.el, (index + _this._containerShiftIndex)
                    * _this.slideWidth);
            }
            if (resizeSlides && itemHolder.slide) {
                itemHolder.slide.resize();
            }
        });
    };
    /**
     * Reset X position of the main scroller to zero
     */
    MainScroll.prototype.resetPosition = function () {
        // Position on the main scroller (offset)
        // it is independent from slide index
        this._currPositionIndex = 0;
        this._prevPositionIndex = 0;
        // This will force recalculation of size on next resize()
        this.slideWidth = 0;
        // _containerShiftIndex*viewportSize will give you amount of transform of the current slide
        this._containerShiftIndex = -1;
    };
    /**
     * Create and append array of three items
     * that hold data about slides in DOM
     */
    MainScroll.prototype.appendHolders = function () {
        this.itemHolders = [];
        // append our three slide holders -
        // previous, current, and next
        for (var i = 0; i < 3; i++) {
            var el = (0, util_js_1.createElement)('pswp__item', 'div', this.pswp.container);
            el.setAttribute('role', 'group');
            el.setAttribute('aria-roledescription', 'slide');
            el.setAttribute('aria-hidden', 'true');
            // hide nearby item holders until initial zoom animation finishes (to avoid extra Paints)
            el.style.display = (i === 1) ? 'block' : 'none';
            this.itemHolders.push({
                el: el,
                //index: -1
            });
        }
    };
    /**
     * Whether the main scroll can be horizontally swiped to the next or previous slide.
     * @returns {boolean}
     */
    MainScroll.prototype.canBeSwiped = function () {
        return this.pswp.getNumItems() > 1;
    };
    /**
     * Move main scroll by X amount of slides.
     * For example:
     *   `-1` will move to the previous slide,
     *    `0` will reset the scroll position of the current slide,
     *    `3` will move three slides forward
     *
     * If loop option is enabled - index will be automatically looped too,
     * (for example `-1` will move to the last slide of the gallery).
     *
     * @param {number} diff
     * @param {boolean} [animate]
     * @param {number} [velocityX]
     * @returns {boolean} whether index was changed or not
     */
    MainScroll.prototype.moveIndexBy = function (diff, animate, velocityX) {
        var _this = this;
        var pswp = this.pswp;
        var newIndex = pswp.potentialIndex + diff;
        var numSlides = pswp.getNumItems();
        if (pswp.canLoop()) {
            newIndex = pswp.getLoopedIndex(newIndex);
            var distance = (diff + numSlides) % numSlides;
            if (distance <= numSlides / 2) {
                // go forward
                diff = distance;
            }
            else {
                // go backwards
                diff = distance - numSlides;
            }
        }
        else {
            if (newIndex < 0) {
                newIndex = 0;
            }
            else if (newIndex >= numSlides) {
                newIndex = numSlides - 1;
            }
            diff = newIndex - pswp.potentialIndex;
        }
        pswp.potentialIndex = newIndex;
        this._currPositionIndex -= diff;
        pswp.animations.stopMainScroll();
        var destinationX = this.getCurrSlideX();
        if (!animate) {
            this.moveTo(destinationX);
            this.updateCurrItem();
        }
        else {
            pswp.animations.startSpring({
                isMainScroll: true,
                start: this.x,
                end: destinationX,
                velocity: velocityX || 0,
                naturalFrequency: 30,
                dampingRatio: 1,
                onUpdate: function (x) {
                    _this.moveTo(x);
                },
                onComplete: function () {
                    _this.updateCurrItem();
                    pswp.appendHeavy();
                }
            });
            var currDiff = pswp.potentialIndex - pswp.currIndex;
            if (pswp.canLoop()) {
                var currDistance = (currDiff + numSlides) % numSlides;
                if (currDistance <= numSlides / 2) {
                    // go forward
                    currDiff = currDistance;
                }
                else {
                    // go backwards
                    currDiff = currDistance - numSlides;
                }
            }
            // Force-append new slides during transition
            // if difference between slides is more than 1
            if (Math.abs(currDiff) > 1) {
                this.updateCurrItem();
            }
        }
        return Boolean(diff);
    };
    /**
     * X position of the main scroll for the current slide
     * (ignores position during dragging)
     * @returns {number}
     */
    MainScroll.prototype.getCurrSlideX = function () {
        return this.slideWidth * this._currPositionIndex;
    };
    /**
     * Whether scroll position is shifted.
     * For example, it will return true if the scroll is being dragged or animated.
     * @returns {boolean}
     */
    MainScroll.prototype.isShifted = function () {
        return this.x !== this.getCurrSlideX();
    };
    /**
     * Update slides X positions and set their content
     */
    MainScroll.prototype.updateCurrItem = function () {
        var _a;
        var pswp = this.pswp;
        var positionDifference = this._prevPositionIndex - this._currPositionIndex;
        if (!positionDifference) {
            return;
        }
        this._prevPositionIndex = this._currPositionIndex;
        pswp.currIndex = pswp.potentialIndex;
        var diffAbs = Math.abs(positionDifference);
        /** @type {ItemHolder | undefined} */
        var tempHolder;
        if (diffAbs >= 3) {
            this._containerShiftIndex += positionDifference + (positionDifference > 0 ? -3 : 3);
            diffAbs = 3;
        }
        for (var i = 0; i < diffAbs; i++) {
            if (positionDifference > 0) {
                tempHolder = this.itemHolders.shift();
                if (tempHolder) {
                    this.itemHolders[2] = tempHolder; // move first to last
                    this._containerShiftIndex++;
                    (0, util_js_1.setTransform)(tempHolder.el, (this._containerShiftIndex + 2) * this.slideWidth);
                    pswp.setContent(tempHolder, (pswp.currIndex - diffAbs) + i + 2);
                }
            }
            else {
                tempHolder = this.itemHolders.pop();
                if (tempHolder) {
                    this.itemHolders.unshift(tempHolder); // move last to first
                    this._containerShiftIndex--;
                    (0, util_js_1.setTransform)(tempHolder.el, this._containerShiftIndex * this.slideWidth);
                    pswp.setContent(tempHolder, (pswp.currIndex + diffAbs) - i - 2);
                }
            }
        }
        // Reset transfrom every 50ish navigations in one direction.
        //
        // Otherwise transform will keep growing indefinitely,
        // which might cause issues as browsers have a maximum transform limit.
        // I wasn't able to reach it, but just to be safe.
        // This should not cause noticable lag.
        if (Math.abs(this._containerShiftIndex) > 50 && !this.isShifted()) {
            this.resetPosition();
            this.resize();
        }
        // Pan transition might be running (and consntantly updating pan position)
        pswp.animations.stopAllPan();
        this.itemHolders.forEach(function (itemHolder, i) {
            if (itemHolder.slide) {
                // Slide in the 2nd holder is always active
                itemHolder.slide.setIsActive(i === 1);
            }
        });
        pswp.currSlide = (_a = this.itemHolders[1]) === null || _a === void 0 ? void 0 : _a.slide;
        pswp.contentLoader.updateLazy(positionDifference);
        if (pswp.currSlide) {
            pswp.currSlide.applyCurrentZoomPan();
        }
        pswp.dispatch('change');
    };
    /**
     * Move the X position of the main scroll container
     *
     * @param {number} x
     * @param {boolean} [dragging]
     */
    MainScroll.prototype.moveTo = function (x, dragging) {
        if (!this.pswp.canLoop() && dragging) {
            // Apply friction
            var newSlideIndexOffset = ((this.slideWidth * this._currPositionIndex) - x) / this.slideWidth;
            newSlideIndexOffset += this.pswp.currIndex;
            var delta = Math.round(x - this.x);
            if ((newSlideIndexOffset < 0 && delta > 0)
                || (newSlideIndexOffset >= this.pswp.getNumItems() - 1 && delta < 0)) {
                x = this.x + (delta * MAIN_SCROLL_END_FRICTION);
            }
        }
        this.x = x;
        if (this.pswp.container) {
            (0, util_js_1.setTransform)(this.pswp.container, x);
        }
        this.pswp.dispatch('moveMainScroll', { x: x, dragging: dragging !== null && dragging !== void 0 ? dragging : false });
    };
    return MainScroll;
}());
exports.default = MainScroll;
//# sourceMappingURL=main-scroll.js.map