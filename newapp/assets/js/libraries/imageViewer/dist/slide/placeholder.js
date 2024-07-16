"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var util_js_1 = require("../util/util.js");
var Placeholder = /** @class */ (function () {
    /**
     * @param {string | false} imageSrc
     * @param {HTMLElement} container
     */
    function Placeholder(imageSrc, container) {
        // Create placeholder
        // (stretched thumbnail or simple div behind the main image)
        /** @type {HTMLImageElement | HTMLDivElement | null} */
        this.element = (0, util_js_1.createElement)('pswp__img pswp__img--placeholder', imageSrc ? 'img' : 'div', container);
        if (imageSrc) {
            var imgEl = /** @type {HTMLImageElement} */ (this.element);
            imgEl.decoding = 'async';
            imgEl.alt = '';
            imgEl.src = imageSrc;
            imgEl.setAttribute('role', 'presentation');
        }
        this.element.setAttribute('aria-hidden', 'true');
    }
    /**
     * @param {number} width
     * @param {number} height
     */
    Placeholder.prototype.setDisplayedSize = function (width, height) {
        if (!this.element) {
            return;
        }
        if (this.element.tagName === 'IMG') {
            // Use transform scale() to modify img placeholder size
            // (instead of changing width/height directly).
            // This helps with performance, specifically in iOS15 Safari.
            (0, util_js_1.setWidthHeight)(this.element, 250, 'auto');
            this.element.style.transformOrigin = '0 0';
            this.element.style.transform = (0, util_js_1.toTransformString)(0, 0, width / 250);
        }
        else {
            (0, util_js_1.setWidthHeight)(this.element, width, height);
        }
    };
    Placeholder.prototype.destroy = function () {
        var _a;
        if ((_a = this.element) === null || _a === void 0 ? void 0 : _a.parentNode) {
            this.element.remove();
        }
        this.element = null;
    };
    return Placeholder;
}());
exports.default = Placeholder;
//# sourceMappingURL=placeholder.js.map