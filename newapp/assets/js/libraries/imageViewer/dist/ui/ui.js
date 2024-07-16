"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var ui_element_js_1 = require("./ui-element.js");
var button_arrow_js_1 = require("./button-arrow.js");
var button_close_js_1 = require("./button-close.js");
var button_zoom_js_1 = require("./button-zoom.js");
var loading_indicator_js_1 = require("./loading-indicator.js");
var counter_indicator_js_1 = require("./counter-indicator.js");
/** @typedef {import('../photoswipe.js').default} PhotoSwipe */
/** @typedef {import('./ui-element.js').UIElementData} UIElementData */
/**
 * Set special class on element when image is zoomed.
 *
 * By default, it is used to adjust
 * zoom icon and zoom cursor via CSS.
 *
 * @param {HTMLElement} el
 * @param {boolean} isZoomedIn
 */
function setZoomedIn(el, isZoomedIn) {
    el.classList.toggle('pswp--zoomed-in', isZoomedIn);
}
var UI = /** @class */ (function () {
    /**
     * @param {PhotoSwipe} pswp
     */
    function UI(pswp) {
        this.pswp = pswp;
        this.isRegistered = false;
        /** @type {UIElementData[]} */
        this.uiElementsData = [];
        /** @type {(UIElement | UIElementData)[]} */
        this.items = [];
        /** @type {() => void} */
        this.updatePreloaderVisibility = function () { };
        /**
         * @private
         * @type {number | undefined}
         */
        this._lastUpdatedZoomLevel = undefined;
    }
    UI.prototype.init = function () {
        var _this = this;
        var pswp = this.pswp;
        this.isRegistered = false;
        this.uiElementsData = [
            button_close_js_1.default,
            button_arrow_js_1.arrowPrev,
            button_arrow_js_1.arrowNext,
            button_zoom_js_1.default,
            loading_indicator_js_1.loadingIndicator,
            counter_indicator_js_1.counterIndicator
        ];
        pswp.dispatch('uiRegister');
        // sort by order
        this.uiElementsData.sort(function (a, b) {
            // default order is 0
            return (a.order || 0) - (b.order || 0);
        });
        this.items = [];
        this.isRegistered = true;
        this.uiElementsData.forEach(function (uiElementData) {
            _this.registerElement(uiElementData);
        });
        pswp.on('change', function () {
            var _a;
            (_a = pswp.element) === null || _a === void 0 ? void 0 : _a.classList.toggle('pswp--one-slide', pswp.getNumItems() === 1);
        });
        pswp.on('zoomPanUpdate', function () { return _this._onZoomPanUpdate(); });
    };
    /**
     * @param {UIElementData} elementData
     */
    UI.prototype.registerElement = function (elementData) {
        if (this.isRegistered) {
            this.items.push(new ui_element_js_1.default(this.pswp, elementData));
        }
        else {
            this.uiElementsData.push(elementData);
        }
    };
    /**
     * Fired each time zoom or pan position is changed.
     * Update classes that control visibility of zoom button and cursor icon.
     *
     * @private
     */
    UI.prototype._onZoomPanUpdate = function () {
        var _a = this.pswp, template = _a.template, currSlide = _a.currSlide, options = _a.options;
        if (this.pswp.opener.isClosing || !template || !currSlide) {
            return;
        }
        var currZoomLevel = currSlide.currZoomLevel;
        // if not open yet - check against initial zoom level
        if (!this.pswp.opener.isOpen) {
            currZoomLevel = currSlide.zoomLevels.initial;
        }
        if (currZoomLevel === this._lastUpdatedZoomLevel) {
            return;
        }
        this._lastUpdatedZoomLevel = currZoomLevel;
        var currZoomLevelDiff = currSlide.zoomLevels.initial - currSlide.zoomLevels.secondary;
        // Initial and secondary zoom levels are almost equal
        if (Math.abs(currZoomLevelDiff) < 0.01 || !currSlide.isZoomable()) {
            // disable zoom
            setZoomedIn(template, false);
            template.classList.remove('pswp--zoom-allowed');
            return;
        }
        template.classList.add('pswp--zoom-allowed');
        var potentialZoomLevel = currZoomLevel === currSlide.zoomLevels.initial
            ? currSlide.zoomLevels.secondary : currSlide.zoomLevels.initial;
        setZoomedIn(template, potentialZoomLevel <= currZoomLevel);
        if (options.imageClickAction === 'zoom'
            || options.imageClickAction === 'zoom-or-close') {
            template.classList.add('pswp--click-to-zoom');
        }
    };
    return UI;
}());
exports.default = UI;
//# sourceMappingURL=ui.js.map