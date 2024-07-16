"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.loadingIndicator = void 0;
/** @type {import('./ui-element.js').UIElementData} UIElementData */
exports.loadingIndicator = {
    name: 'preloader',
    appendTo: 'bar',
    order: 7,
    html: {
        isCustomSVG: true,
        // eslint-disable-next-line max-len
        inner: '<path fill-rule="evenodd" clip-rule="evenodd" d="M21.2 16a5.2 5.2 0 1 1-5.2-5.2V8a8 8 0 1 0 8 8h-2.8Z" id="pswp__icn-loading"/>',
        outlineID: 'pswp__icn-loading'
    },
    onInit: function (indicatorElement, pswp) {
        /** @type {boolean | undefined} */
        var isVisible;
        /** @type {NodeJS.Timeout | null} */
        var delayTimeout = null;
        /**
         * @param {string} className
         * @param {boolean} add
         */
        var toggleIndicatorClass = function (className, add) {
            indicatorElement.classList.toggle('pswp__preloader--' + className, add);
        };
        /**
         * @param {boolean} visible
         */
        var setIndicatorVisibility = function (visible) {
            if (isVisible !== visible) {
                isVisible = visible;
                toggleIndicatorClass('active', visible);
            }
        };
        var updatePreloaderVisibility = function () {
            var _a;
            if (!((_a = pswp.currSlide) === null || _a === void 0 ? void 0 : _a.content.isLoading())) {
                setIndicatorVisibility(false);
                if (delayTimeout) {
                    clearTimeout(delayTimeout);
                    delayTimeout = null;
                }
                return;
            }
            if (!delayTimeout) {
                // display loading indicator with delay
                delayTimeout = setTimeout(function () {
                    var _a;
                    setIndicatorVisibility(Boolean((_a = pswp.currSlide) === null || _a === void 0 ? void 0 : _a.content.isLoading()));
                    delayTimeout = null;
                }, pswp.options.preloaderDelay);
            }
        };
        pswp.on('change', updatePreloaderVisibility);
        pswp.on('loadComplete', function (e) {
            if (pswp.currSlide === e.slide) {
                updatePreloaderVisibility();
            }
        });
        // expose the method
        if (pswp.ui) {
            pswp.ui.updatePreloaderVisibility = updatePreloaderVisibility;
        }
    }
};
//# sourceMappingURL=loading-indicator.js.map