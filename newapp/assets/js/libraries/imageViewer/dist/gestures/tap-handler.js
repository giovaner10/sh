"use strict";
/**
 * @template {string} T
 * @template {string} P
 * @typedef {import('../types.js').AddPostfix<T, P>} AddPostfix<T, P>
 */
Object.defineProperty(exports, "__esModule", { value: true });
/** @typedef {import('./gestures.js').default} Gestures */
/** @typedef {import('../photoswipe.js').Point} Point */
/** @typedef {'imageClick' | 'bgClick' | 'tap' | 'doubleTap'} Actions */
/**
 * Whether the tap was performed on the main slide
 * (rather than controls or caption).
 *
 * @param {PointerEvent} event
 * @returns {boolean}
 */
function didTapOnMainContent(event) {
    return !!( /** @type {HTMLElement} */(event.target).closest('.pswp__container'));
}
/**
 * Tap, double-tap handler.
 */
var TapHandler = /** @class */ (function () {
    /**
     * @param {Gestures} gestures
     */
    function TapHandler(gestures) {
        this.gestures = gestures;
    }
    /**
     * @param {Point} point
     * @param {PointerEvent} originalEvent
     */
    TapHandler.prototype.click = function (point, originalEvent) {
        var targetClassList = /** @type {HTMLElement} */ (originalEvent.target).classList;
        var isImageClick = targetClassList.contains('pswp__img');
        var isBackgroundClick = targetClassList.contains('pswp__item')
            || targetClassList.contains('pswp__zoom-wrap');
        if (isImageClick) {
            this._doClickOrTapAction('imageClick', point, originalEvent);
        }
        else if (isBackgroundClick) {
            this._doClickOrTapAction('bgClick', point, originalEvent);
        }
    };
    /**
     * @param {Point} point
     * @param {PointerEvent} originalEvent
     */
    TapHandler.prototype.tap = function (point, originalEvent) {
        if (didTapOnMainContent(originalEvent)) {
            this._doClickOrTapAction('tap', point, originalEvent);
        }
    };
    /**
     * @param {Point} point
     * @param {PointerEvent} originalEvent
     */
    TapHandler.prototype.doubleTap = function (point, originalEvent) {
        if (didTapOnMainContent(originalEvent)) {
            this._doClickOrTapAction('doubleTap', point, originalEvent);
        }
    };
    /**
     * @private
     * @param {Actions} actionName
     * @param {Point} point
     * @param {PointerEvent} originalEvent
     */
    TapHandler.prototype._doClickOrTapAction = function (actionName, point, originalEvent) {
        var _a;
        var pswp = this.gestures.pswp;
        var currSlide = pswp.currSlide;
        var actionFullName = /** @type {AddPostfix<Actions, 'Action'>} */ (actionName + 'Action');
        var optionValue = pswp.options[actionFullName];
        if (pswp.dispatch(actionFullName, { point: point, originalEvent: originalEvent }).defaultPrevented) {
            return;
        }
        if (typeof optionValue === 'function') {
            optionValue.call(pswp, point, originalEvent);
            return;
        }
        switch (optionValue) {
            case 'close':
            case 'next':
                pswp[optionValue]();
                break;
            case 'zoom':
                currSlide === null || currSlide === void 0 ? void 0 : currSlide.toggleZoom(point);
                break;
            case 'zoom-or-close':
                // by default click zooms current image,
                // if it can not be zoomed - gallery will be closed
                if ((currSlide === null || currSlide === void 0 ? void 0 : currSlide.isZoomable())
                    && currSlide.zoomLevels.secondary !== currSlide.zoomLevels.initial) {
                    currSlide.toggleZoom(point);
                }
                else if (pswp.options.clickToCloseNonZoomable) {
                    pswp.close();
                }
                break;
            case 'toggle-controls':
                (_a = this.gestures.pswp.element) === null || _a === void 0 ? void 0 : _a.classList.toggle('pswp--ui-visible');
                // if (_controlsVisible) {
                //   _ui.hideControls();
                // } else {
                //   _ui.showControls();
                // }
                break;
        }
    };
    return TapHandler;
}());
exports.default = TapHandler;
//# sourceMappingURL=tap-handler.js.map