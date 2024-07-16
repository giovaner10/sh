"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var util_js_1 = require("../util/util.js");
/** @typedef {import('../photoswipe.js').Point} Point */
/** @typedef {import('./gestures.js').default} Gestures */
var UPPER_ZOOM_FRICTION = 0.05;
var LOWER_ZOOM_FRICTION = 0.15;
/**
 * Get center point between two points
 *
 * @param {Point} p
 * @param {Point} p1
 * @param {Point} p2
 * @returns {Point}
 */
function getZoomPointsCenter(p, p1, p2) {
    p.x = (p1.x + p2.x) / 2;
    p.y = (p1.y + p2.y) / 2;
    return p;
}
var ZoomHandler = /** @class */ (function () {
    /**
     * @param {Gestures} gestures
     */
    function ZoomHandler(gestures) {
        this.gestures = gestures;
        /**
         * @private
         * @type {Point}
         */
        this._startPan = { x: 0, y: 0 };
        /**
         * @private
         * @type {Point}
         */
        this._startZoomPoint = { x: 0, y: 0 };
        /**
         * @private
         * @type {Point}
         */
        this._zoomPoint = { x: 0, y: 0 };
        /** @private */
        this._wasOverFitZoomLevel = false;
        /** @private */
        this._startZoomLevel = 1;
    }
    ZoomHandler.prototype.start = function () {
        var currSlide = this.gestures.pswp.currSlide;
        if (currSlide) {
            this._startZoomLevel = currSlide.currZoomLevel;
            (0, util_js_1.equalizePoints)(this._startPan, currSlide.pan);
        }
        this.gestures.pswp.animations.stopAllPan();
        this._wasOverFitZoomLevel = false;
    };
    ZoomHandler.prototype.change = function () {
        var _a = this.gestures, p1 = _a.p1, startP1 = _a.startP1, p2 = _a.p2, startP2 = _a.startP2, pswp = _a.pswp;
        var currSlide = pswp.currSlide;
        if (!currSlide) {
            return;
        }
        var minZoomLevel = currSlide.zoomLevels.min;
        var maxZoomLevel = currSlide.zoomLevels.max;
        if (!currSlide.isZoomable() || pswp.mainScroll.isShifted()) {
            return;
        }
        getZoomPointsCenter(this._startZoomPoint, startP1, startP2);
        getZoomPointsCenter(this._zoomPoint, p1, p2);
        var currZoomLevel = (1 / (0, util_js_1.getDistanceBetween)(startP1, startP2))
            * (0, util_js_1.getDistanceBetween)(p1, p2)
            * this._startZoomLevel;
        // slightly over the zoom.fit
        if (currZoomLevel > currSlide.zoomLevels.initial + (currSlide.zoomLevels.initial / 15)) {
            this._wasOverFitZoomLevel = true;
        }
        if (currZoomLevel < minZoomLevel) {
            if (pswp.options.pinchToClose
                && !this._wasOverFitZoomLevel
                && this._startZoomLevel <= currSlide.zoomLevels.initial) {
                // fade out background if zooming out
                var bgOpacity = 1 - ((minZoomLevel - currZoomLevel) / (minZoomLevel / 1.2));
                if (!pswp.dispatch('pinchClose', { bgOpacity: bgOpacity }).defaultPrevented) {
                    pswp.applyBgOpacity(bgOpacity);
                }
            }
            else {
                // Apply the friction if zoom level is below the min
                currZoomLevel = minZoomLevel - (minZoomLevel - currZoomLevel) * LOWER_ZOOM_FRICTION;
            }
        }
        else if (currZoomLevel > maxZoomLevel) {
            // Apply the friction if zoom level is above the max
            currZoomLevel = maxZoomLevel + (currZoomLevel - maxZoomLevel) * UPPER_ZOOM_FRICTION;
        }
        currSlide.pan.x = this._calculatePanForZoomLevel('x', currZoomLevel);
        currSlide.pan.y = this._calculatePanForZoomLevel('y', currZoomLevel);
        currSlide.setZoomLevel(currZoomLevel);
        currSlide.applyCurrentZoomPan();
    };
    ZoomHandler.prototype.end = function () {
        var pswp = this.gestures.pswp;
        var currSlide = pswp.currSlide;
        if ((!currSlide || currSlide.currZoomLevel < currSlide.zoomLevels.initial)
            && !this._wasOverFitZoomLevel
            && pswp.options.pinchToClose) {
            pswp.close();
        }
        else {
            this.correctZoomPan();
        }
    };
    /**
     * @private
     * @param {'x' | 'y'} axis
     * @param {number} currZoomLevel
     * @returns {number}
     */
    ZoomHandler.prototype._calculatePanForZoomLevel = function (axis, currZoomLevel) {
        var zoomFactor = currZoomLevel / this._startZoomLevel;
        return this._zoomPoint[axis]
            - ((this._startZoomPoint[axis] - this._startPan[axis]) * zoomFactor);
    };
    /**
     * Correct currZoomLevel and pan if they are
     * beyond minimum or maximum values.
     * With animation.
     *
     * @param {boolean} [ignoreGesture]
     * Wether gesture coordinates should be ignored when calculating destination pan position.
     */
    ZoomHandler.prototype.correctZoomPan = function (ignoreGesture) {
        var pswp = this.gestures.pswp;
        var currSlide = pswp.currSlide;
        if (!(currSlide === null || currSlide === void 0 ? void 0 : currSlide.isZoomable())) {
            return;
        }
        if (this._zoomPoint.x === 0) {
            ignoreGesture = true;
        }
        var prevZoomLevel = currSlide.currZoomLevel;
        /** @type {number} */
        var destinationZoomLevel;
        var currZoomLevelNeedsChange = true;
        if (prevZoomLevel < currSlide.zoomLevels.initial) {
            destinationZoomLevel = currSlide.zoomLevels.initial;
            // zoom to min
        }
        else if (prevZoomLevel > currSlide.zoomLevels.max) {
            destinationZoomLevel = currSlide.zoomLevels.max;
            // zoom to max
        }
        else {
            currZoomLevelNeedsChange = false;
            destinationZoomLevel = prevZoomLevel;
        }
        var initialBgOpacity = pswp.bgOpacity;
        var restoreBgOpacity = pswp.bgOpacity < 1;
        var initialPan = (0, util_js_1.equalizePoints)({ x: 0, y: 0 }, currSlide.pan);
        var destinationPan = (0, util_js_1.equalizePoints)({ x: 0, y: 0 }, initialPan);
        if (ignoreGesture) {
            this._zoomPoint.x = 0;
            this._zoomPoint.y = 0;
            this._startZoomPoint.x = 0;
            this._startZoomPoint.y = 0;
            this._startZoomLevel = prevZoomLevel;
            (0, util_js_1.equalizePoints)(this._startPan, initialPan);
        }
        if (currZoomLevelNeedsChange) {
            destinationPan = {
                x: this._calculatePanForZoomLevel('x', destinationZoomLevel),
                y: this._calculatePanForZoomLevel('y', destinationZoomLevel)
            };
        }
        // set zoom level, so pan bounds are updated according to it
        currSlide.setZoomLevel(destinationZoomLevel);
        destinationPan = {
            x: currSlide.bounds.correctPan('x', destinationPan.x),
            y: currSlide.bounds.correctPan('y', destinationPan.y)
        };
        // return zoom level and its bounds to initial
        currSlide.setZoomLevel(prevZoomLevel);
        var panNeedsChange = !(0, util_js_1.pointsEqual)(destinationPan, initialPan);
        if (!panNeedsChange && !currZoomLevelNeedsChange && !restoreBgOpacity) {
            // update resolution after gesture
            currSlide._setResolution(destinationZoomLevel);
            currSlide.applyCurrentZoomPan();
            // nothing to animate
            return;
        }
        pswp.animations.stopAllPan();
        pswp.animations.startSpring({
            isPan: true,
            start: 0,
            end: 1000,
            velocity: 0,
            dampingRatio: 1,
            naturalFrequency: 40,
            onUpdate: function (now) {
                now /= 1000; // 0 - start, 1 - end
                if (panNeedsChange || currZoomLevelNeedsChange) {
                    if (panNeedsChange) {
                        currSlide.pan.x = initialPan.x + (destinationPan.x - initialPan.x) * now;
                        currSlide.pan.y = initialPan.y + (destinationPan.y - initialPan.y) * now;
                    }
                    if (currZoomLevelNeedsChange) {
                        var newZoomLevel = prevZoomLevel
                            + (destinationZoomLevel - prevZoomLevel) * now;
                        currSlide.setZoomLevel(newZoomLevel);
                    }
                    currSlide.applyCurrentZoomPan();
                }
                // Restore background opacity
                if (restoreBgOpacity && pswp.bgOpacity < 1) {
                    // We clamp opacity to keep it between 0 and 1.
                    // As progress ratio can be larger than 1 due to overshoot,
                    // and we do not want to bounce opacity.
                    pswp.applyBgOpacity((0, util_js_1.clamp)(initialBgOpacity + (1 - initialBgOpacity) * now, 0, 1));
                }
            },
            onComplete: function () {
                // update resolution after transition ends
                currSlide._setResolution(destinationZoomLevel);
                currSlide.applyCurrentZoomPan();
            }
        });
    };
    return ZoomHandler;
}());
exports.default = ZoomHandler;
//# sourceMappingURL=zoom-handler.js.map