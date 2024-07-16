"use strict";
/** @typedef {import('./photoswipe.js').default} PhotoSwipe */
Object.defineProperty(exports, "__esModule", { value: true });
/**
 * Handles scroll wheel.
 * Can pan and zoom current slide image.
 */
var ScrollWheel = /** @class */ (function () {
    /**
     * @param {PhotoSwipe} pswp
     */
    function ScrollWheel(pswp) {
        this.pswp = pswp;
        pswp.events.add(pswp.element, 'wheel', /** @type EventListener */ (this._onWheel.bind(this)));
    }
    /**
     * @private
     * @param {WheelEvent} e
     */
    ScrollWheel.prototype._onWheel = function (e) {
        e.preventDefault();
        var currSlide = this.pswp.currSlide;
        var deltaX = e.deltaX, deltaY = e.deltaY;
        if (!currSlide) {
            return;
        }
        if (this.pswp.dispatch('wheel', { originalEvent: e }).defaultPrevented) {
            return;
        }
        if (e.ctrlKey || this.pswp.options.wheelToZoom) {
            // zoom
            if (currSlide.isZoomable()) {
                var zoomFactor = -deltaY;
                if (e.deltaMode === 1 /* DOM_DELTA_LINE */) {
                    zoomFactor *= 0.05;
                }
                else {
                    zoomFactor *= e.deltaMode ? 1 : 0.002;
                }
                zoomFactor = Math.pow(2, zoomFactor);
                var destZoomLevel = currSlide.currZoomLevel * zoomFactor;
                currSlide.zoomTo(destZoomLevel, {
                    x: e.clientX,
                    y: e.clientY
                });
            }
        }
        else {
            // pan
            if (currSlide.isPannable()) {
                if (e.deltaMode === 1 /* DOM_DELTA_LINE */) {
                    // 18 - average line height
                    deltaX *= 18;
                    deltaY *= 18;
                }
                currSlide.panTo(currSlide.pan.x - deltaX, currSlide.pan.y - deltaY);
            }
        }
    };
    return ScrollWheel;
}());
exports.default = ScrollWheel;
//# sourceMappingURL=scroll-wheel.js.map