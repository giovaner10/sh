"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var util_js_1 = require("../util/util.js");
var viewport_size_js_1 = require("../util/viewport-size.js");
/** @typedef {import('./slide.js').default} Slide */
/** @typedef {Record<Axis, number>} Point */
/** @typedef {'x' | 'y'} Axis */
/**
 * Calculates minimum, maximum and initial (center) bounds of a slide
 */
var PanBounds = /** @class */ (function () {
    /**
     * @param {Slide} slide
     */
    function PanBounds(slide) {
        this.slide = slide;
        this.currZoomLevel = 1;
        this.center = /** @type {Point} */ { x: 0, y: 0 };
        this.max = /** @type {Point} */ { x: 0, y: 0 };
        this.min = /** @type {Point} */ { x: 0, y: 0 };
    }
    /**
     * _getItemBounds
     *
     * @param {number} currZoomLevel
     */
    PanBounds.prototype.update = function (currZoomLevel) {
        this.currZoomLevel = currZoomLevel;
        if (!this.slide.width) {
            this.reset();
        }
        else {
            this._updateAxis('x');
            this._updateAxis('y');
            this.slide.pswp.dispatch('calcBounds', { slide: this.slide });
        }
    };
    /**
     * _calculateItemBoundsForAxis
     *
     * @param {Axis} axis
     */
    PanBounds.prototype._updateAxis = function (axis) {
        var pswp = this.slide.pswp;
        var elSize = this.slide[axis === 'x' ? 'width' : 'height'] * this.currZoomLevel;
        var paddingProp = axis === 'x' ? 'left' : 'top';
        var padding = (0, viewport_size_js_1.parsePaddingOption)(paddingProp, pswp.options, pswp.viewportSize, this.slide.data, this.slide.index);
        var panAreaSize = this.slide.panAreaSize[axis];
        // Default position of element.
        // By default, it is center of viewport:
        this.center[axis] = Math.round((panAreaSize - elSize) / 2) + padding;
        // maximum pan position
        this.max[axis] = (elSize > panAreaSize)
            ? Math.round(panAreaSize - elSize) + padding
            : this.center[axis];
        // minimum pan position
        this.min[axis] = (elSize > panAreaSize)
            ? padding
            : this.center[axis];
    };
    // _getZeroBounds
    PanBounds.prototype.reset = function () {
        this.center.x = 0;
        this.center.y = 0;
        this.max.x = 0;
        this.max.y = 0;
        this.min.x = 0;
        this.min.y = 0;
    };
    /**
     * Correct pan position if it's beyond the bounds
     *
     * @param {Axis} axis x or y
     * @param {number} panOffset
     * @returns {number}
     */
    PanBounds.prototype.correctPan = function (axis, panOffset) {
        return (0, util_js_1.clamp)(panOffset, this.max[axis], this.min[axis]);
    };
    return PanBounds;
}());
exports.default = PanBounds;
//# sourceMappingURL=pan-bounds.js.map