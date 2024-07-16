"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var util_js_1 = require("../util/util.js");
/** @typedef {import('../photoswipe.js').Point} Point */
/** @typedef {import('./gestures.js').default} Gestures */
var PAN_END_FRICTION = 0.35;
var VERTICAL_DRAG_FRICTION = 0.6;
// 1 corresponds to the third of viewport height
var MIN_RATIO_TO_CLOSE = 0.4;
// Minimum speed required to navigate
// to next or previous slide
var MIN_NEXT_SLIDE_SPEED = 0.5;
/**
 * @param {number} initialVelocity
 * @param {number} decelerationRate
 * @returns {number}
 */
function project(initialVelocity, decelerationRate) {
    return initialVelocity * decelerationRate / (1 - decelerationRate);
}
/**
 * Handles single pointer dragging
 */
var DragHandler = /** @class */ (function () {
    /**
     * @param {Gestures} gestures
     */
    function DragHandler(gestures) {
        this.gestures = gestures;
        this.pswp = gestures.pswp;
        /** @type {Point} */
        this.startPan = { x: 0, y: 0 };
    }
    DragHandler.prototype.start = function () {
        if (this.pswp.currSlide) {
            (0, util_js_1.equalizePoints)(this.startPan, this.pswp.currSlide.pan);
        }
        this.pswp.animations.stopAll();
    };
    DragHandler.prototype.change = function () {
        var _a = this.gestures, p1 = _a.p1, prevP1 = _a.prevP1, dragAxis = _a.dragAxis;
        var currSlide = this.pswp.currSlide;
        if (dragAxis === 'y'
            && this.pswp.options.closeOnVerticalDrag
            && (currSlide && currSlide.currZoomLevel <= currSlide.zoomLevels.fit)
            && !this.gestures.isMultitouch) {
            // Handle vertical drag to close
            var panY = currSlide.pan.y + (p1.y - prevP1.y);
            if (!this.pswp.dispatch('verticalDrag', { panY: panY }).defaultPrevented) {
                this._setPanWithFriction('y', panY, VERTICAL_DRAG_FRICTION);
                var bgOpacity = 1 - Math.abs(this._getVerticalDragRatio(currSlide.pan.y));
                this.pswp.applyBgOpacity(bgOpacity);
                currSlide.applyCurrentZoomPan();
            }
        }
        else {
            var mainScrollChanged = this._panOrMoveMainScroll('x');
            if (!mainScrollChanged) {
                this._panOrMoveMainScroll('y');
                if (currSlide) {
                    (0, util_js_1.roundPoint)(currSlide.pan);
                    currSlide.applyCurrentZoomPan();
                }
            }
        }
    };
    DragHandler.prototype.end = function () {
        var velocity = this.gestures.velocity;
        var _a = this.pswp, mainScroll = _a.mainScroll, currSlide = _a.currSlide;
        var indexDiff = 0;
        this.pswp.animations.stopAll();
        // Handle main scroll if it's shifted
        if (mainScroll.isShifted()) {
            // Position of the main scroll relative to the viewport
            var mainScrollShiftDiff = mainScroll.x - mainScroll.getCurrSlideX();
            // Ratio between 0 and 1:
            // 0 - slide is not visible at all,
            // 0.5 - half of the slide is visible
            // 1 - slide is fully visible
            var currentSlideVisibilityRatio = (mainScrollShiftDiff / this.pswp.viewportSize.x);
            // Go next slide.
            //
            // - if velocity and its direction is matched,
            //   and we see at least tiny part of the next slide
            //
            // - or if we see less than 50% of the current slide
            //   and velocity is close to 0
            //
            if ((velocity.x < -MIN_NEXT_SLIDE_SPEED && currentSlideVisibilityRatio < 0)
                || (velocity.x < 0.1 && currentSlideVisibilityRatio < -0.5)) {
                // Go to next slide
                indexDiff = 1;
                velocity.x = Math.min(velocity.x, 0);
            }
            else if ((velocity.x > MIN_NEXT_SLIDE_SPEED && currentSlideVisibilityRatio > 0)
                || (velocity.x > -0.1 && currentSlideVisibilityRatio > 0.5)) {
                // Go to prev slide
                indexDiff = -1;
                velocity.x = Math.max(velocity.x, 0);
            }
            mainScroll.moveIndexBy(indexDiff, true, velocity.x);
        }
        // Restore zoom level
        if ((currSlide && currSlide.currZoomLevel > currSlide.zoomLevels.max)
            || this.gestures.isMultitouch) {
            this.gestures.zoomLevels.correctZoomPan(true);
        }
        else {
            // we run two animations instead of one,
            // as each axis has own pan boundaries and thus different spring function
            // (correctZoomPan does not have this functionality,
            //  it animates all properties with single timing function)
            this._finishPanGestureForAxis('x');
            this._finishPanGestureForAxis('y');
        }
    };
    /**
     * @private
     * @param {'x' | 'y'} axis
     */
    DragHandler.prototype._finishPanGestureForAxis = function (axis) {
        var _this = this;
        var velocity = this.gestures.velocity;
        var currSlide = this.pswp.currSlide;
        if (!currSlide) {
            return;
        }
        var pan = currSlide.pan, bounds = currSlide.bounds;
        var panPos = pan[axis];
        var restoreBgOpacity = (this.pswp.bgOpacity < 1 && axis === 'y');
        // 0.995 means - scroll view loses 0.5% of its velocity per millisecond
        // Increasing this number will reduce travel distance
        var decelerationRate = 0.995; // 0.99
        // Pan position if there is no bounds
        var projectedPosition = panPos + project(velocity[axis], decelerationRate);
        if (restoreBgOpacity) {
            var vDragRatio = this._getVerticalDragRatio(panPos);
            var projectedVDragRatio = this._getVerticalDragRatio(projectedPosition);
            // If we are above and moving upwards,
            // or if we are below and moving downwards
            if ((vDragRatio < 0 && projectedVDragRatio < -MIN_RATIO_TO_CLOSE)
                || (vDragRatio > 0 && projectedVDragRatio > MIN_RATIO_TO_CLOSE)) {
                this.pswp.close();
                return;
            }
        }
        // Pan position with corrected bounds
        var correctedPanPosition = bounds.correctPan(axis, projectedPosition);
        // Exit if pan position should not be changed
        // or if speed it too low
        if (panPos === correctedPanPosition) {
            return;
        }
        // Overshoot if the final position is out of pan bounds
        var dampingRatio = (correctedPanPosition === projectedPosition) ? 1 : 0.82;
        var initialBgOpacity = this.pswp.bgOpacity;
        var totalPanDist = correctedPanPosition - panPos;
        this.pswp.animations.startSpring({
            name: 'panGesture' + axis,
            isPan: true,
            start: panPos,
            end: correctedPanPosition,
            velocity: velocity[axis],
            dampingRatio: dampingRatio,
            onUpdate: function (pos) {
                // Animate opacity of background relative to Y pan position of an image
                if (restoreBgOpacity && _this.pswp.bgOpacity < 1) {
                    // 0 - start of animation, 1 - end of animation
                    var animationProgressRatio = 1 - (correctedPanPosition - pos) / totalPanDist;
                    // We clamp opacity to keep it between 0 and 1.
                    // As progress ratio can be larger than 1 due to overshoot,
                    // and we do not want to bounce opacity.
                    _this.pswp.applyBgOpacity((0, util_js_1.clamp)(initialBgOpacity + (1 - initialBgOpacity) * animationProgressRatio, 0, 1));
                }
                pan[axis] = Math.floor(pos);
                currSlide.applyCurrentZoomPan();
            },
        });
    };
    /**
     * Update position of the main scroll,
     * or/and update pan position of the current slide.
     *
     * Should return true if it changes (or can change) main scroll.
     *
     * @private
     * @param {'x' | 'y'} axis
     * @returns {boolean}
     */
    DragHandler.prototype._panOrMoveMainScroll = function (axis) {
        var _a = this.gestures, p1 = _a.p1, dragAxis = _a.dragAxis, prevP1 = _a.prevP1, isMultitouch = _a.isMultitouch;
        var _b = this.pswp, currSlide = _b.currSlide, mainScroll = _b.mainScroll;
        var delta = (p1[axis] - prevP1[axis]);
        var newMainScrollX = mainScroll.x + delta;
        if (!delta || !currSlide) {
            return false;
        }
        // Always move main scroll if image can not be panned
        if (axis === 'x' && !currSlide.isPannable() && !isMultitouch) {
            mainScroll.moveTo(newMainScrollX, true);
            return true; // changed main scroll
        }
        var bounds = currSlide.bounds;
        var newPan = currSlide.pan[axis] + delta;
        if (this.pswp.options.allowPanToNext
            && dragAxis === 'x'
            && axis === 'x'
            && !isMultitouch) {
            var currSlideMainScrollX = mainScroll.getCurrSlideX();
            // Position of the main scroll relative to the viewport
            var mainScrollShiftDiff = mainScroll.x - currSlideMainScrollX;
            var isLeftToRight = delta > 0;
            var isRightToLeft = !isLeftToRight;
            if (newPan > bounds.min[axis] && isLeftToRight) {
                // Panning from left to right, beyond the left edge
                // Wether the image was at minimum pan position (or less)
                // when this drag gesture started.
                // Minimum pan position refers to the left edge of the image.
                var wasAtMinPanPosition = (bounds.min[axis] <= this.startPan[axis]);
                if (wasAtMinPanPosition) {
                    mainScroll.moveTo(newMainScrollX, true);
                    return true;
                }
                else {
                    this._setPanWithFriction(axis, newPan);
                    //currSlide.pan[axis] = newPan;
                }
            }
            else if (newPan < bounds.max[axis] && isRightToLeft) {
                // Paning from right to left, beyond the right edge
                // Maximum pan position refers to the right edge of the image.
                var wasAtMaxPanPosition = (this.startPan[axis] <= bounds.max[axis]);
                if (wasAtMaxPanPosition) {
                    mainScroll.moveTo(newMainScrollX, true);
                    return true;
                }
                else {
                    this._setPanWithFriction(axis, newPan);
                    //currSlide.pan[axis] = newPan;
                }
            }
            else {
                // If main scroll is shifted
                if (mainScrollShiftDiff !== 0) {
                    // If main scroll is shifted right
                    if (mainScrollShiftDiff > 0 /*&& isRightToLeft*/) {
                        mainScroll.moveTo(Math.max(newMainScrollX, currSlideMainScrollX), true);
                        return true;
                    }
                    else if (mainScrollShiftDiff < 0 /*&& isLeftToRight*/) {
                        // Main scroll is shifted left (Position is less than 0 comparing to the viewport 0)
                        mainScroll.moveTo(Math.min(newMainScrollX, currSlideMainScrollX), true);
                        return true;
                    }
                }
                else {
                    // We are within pan bounds, so just pan
                    this._setPanWithFriction(axis, newPan);
                }
            }
        }
        else {
            if (axis === 'y') {
                // Do not pan vertically if main scroll is shifted o
                if (!mainScroll.isShifted() && bounds.min.y !== bounds.max.y) {
                    this._setPanWithFriction(axis, newPan);
                }
            }
            else {
                this._setPanWithFriction(axis, newPan);
            }
        }
        return false;
    };
    // If we move above - the ratio is negative
    // If we move below the ratio is positive
    /**
     * Relation between pan Y position and third of viewport height.
     *
     * When we are at initial position (center bounds) - the ratio is 0,
     * if position is shifted upwards - the ratio is negative,
     * if position is shifted downwards - the ratio is positive.
     *
     * @private
     * @param {number} panY The current pan Y position.
     * @returns {number}
     */
    DragHandler.prototype._getVerticalDragRatio = function (panY) {
        var _a, _b;
        return (panY - ((_b = (_a = this.pswp.currSlide) === null || _a === void 0 ? void 0 : _a.bounds.center.y) !== null && _b !== void 0 ? _b : 0)) / (this.pswp.viewportSize.y / 3);
    };
    /**
     * Set pan position of the current slide.
     * Apply friction if the position is beyond the pan bounds,
     * or if custom friction is defined.
     *
     * @private
     * @param {'x' | 'y'} axis
     * @param {number} potentialPan
     * @param {number} [customFriction] (0.1 - 1)
     */
    DragHandler.prototype._setPanWithFriction = function (axis, potentialPan, customFriction) {
        var currSlide = this.pswp.currSlide;
        if (!currSlide) {
            return;
        }
        var pan = currSlide.pan, bounds = currSlide.bounds;
        var correctedPan = bounds.correctPan(axis, potentialPan);
        // If we are out of pan bounds
        if (correctedPan !== potentialPan || customFriction) {
            var delta = Math.round(potentialPan - pan[axis]);
            pan[axis] += delta * (customFriction || PAN_END_FRICTION);
        }
        else {
            pan[axis] = potentialPan;
        }
    };
    return DragHandler;
}());
exports.default = DragHandler;
//# sourceMappingURL=drag-handler.js.map