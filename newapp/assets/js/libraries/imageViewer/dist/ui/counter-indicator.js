"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.counterIndicator = void 0;
/** @type {import('./ui-element.js').UIElementData} UIElementData */
exports.counterIndicator = {
    name: 'counter',
    order: 5,
    onInit: function (counterElement, pswp) {
        pswp.on('change', function () {
            counterElement.innerText = (pswp.currIndex + 1)
                + pswp.options.indexIndicatorSep
                + pswp.getNumItems();
        });
    }
};
//# sourceMappingURL=counter-indicator.js.map