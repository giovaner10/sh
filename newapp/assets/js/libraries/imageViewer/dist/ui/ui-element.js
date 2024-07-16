"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var util_js_1 = require("../util/util.js");
/** @typedef {import('../photoswipe.js').default} PhotoSwipe */
/**
 * @template T
 * @typedef {import('../types.js').Methods<T>} Methods<T>
 */
/**
 * @typedef {Object} UIElementMarkupProps
 * @prop {boolean} [isCustomSVG]
 * @prop {string} inner
 * @prop {string} [outlineID]
 * @prop {number | string} [size]
 */
/**
 * @typedef {Object} UIElementData
 * @prop {DefaultUIElements | string} [name]
 * @prop {string} [className]
 * @prop {UIElementMarkup} [html]
 * @prop {boolean} [isButton]
 * @prop {keyof HTMLElementTagNameMap} [tagName]
 * @prop {string} [title]
 * @prop {string} [ariaLabel]
 * @prop {(element: HTMLElement, pswp: PhotoSwipe) => void} [onInit]
 * @prop {Methods<PhotoSwipe> | ((e: MouseEvent, element: HTMLElement, pswp: PhotoSwipe) => void)} [onClick]
 * @prop {'bar' | 'wrapper' | 'root'} [appendTo]
 * @prop {number} [order]
 */
/** @typedef {'arrowPrev' | 'arrowNext' | 'close' | 'zoom' | 'counter'} DefaultUIElements */
/** @typedef {string | UIElementMarkupProps} UIElementMarkup */
/**
 * @param {UIElementMarkup} [htmlData]
 * @returns {string}
 */
function addElementHTML(htmlData) {
    if (typeof htmlData === 'string') {
        // Allow developers to provide full svg,
        // For example:
        // <svg viewBox="0 0 32 32" width="32" height="32" aria-hidden="true" class="pswp__icn">
        //   <path d="..." />
        //   <circle ... />
        // </svg>
        // Can also be any HTML string.
        return htmlData;
    }
    if (!htmlData || !htmlData.isCustomSVG) {
        return '';
    }
    var svgData = htmlData;
    var out = '<svg aria-hidden="true" class="pswp__icn" viewBox="0 0 %d %d" width="%d" height="%d">';
    // replace all %d with size
    out = out.split('%d').join(/** @type {string} */ (svgData.size || 32));
    // Icons may contain outline/shadow,
    // to make it we "clone" base icon shape and add border to it.
    // Icon itself and border are styled via CSS.
    //
    // Property shadowID defines ID of element that should be cloned.
    if (svgData.outlineID) {
        out += '<use class="pswp__icn-shadow" xlink:href="#' + svgData.outlineID + '"/>';
    }
    out += svgData.inner;
    out += '</svg>';
    return out;
}
var UIElement = /** @class */ (function () {
    /**
     * @param {PhotoSwipe} pswp
     * @param {UIElementData} data
     */
    function UIElement(pswp, data) {
        var name = data.name || data.className;
        var elementHTML = data.html;
        // @ts-expect-error lookup only by `data.name` maybe?
        if (pswp.options[name] === false) {
            // exit if element is disabled from options
            return;
        }
        // Allow to override SVG icons from options
        // @ts-expect-error lookup only by `data.name` maybe?
        if (typeof pswp.options[name + 'SVG'] === 'string') {
            // arrowPrevSVG
            // arrowNextSVG
            // closeSVG
            // zoomSVG
            // @ts-expect-error lookup only by `data.name` maybe?
            elementHTML = pswp.options[name + 'SVG'];
        }
        pswp.dispatch('uiElementCreate', { data: data });
        var className = '';
        if (data.isButton) {
            className += 'pswp__button ';
            className += (data.className || "pswp__button--".concat(data.name));
        }
        else {
            className += (data.className || "pswp__".concat(data.name));
        }
        var tagName = data.isButton ? (data.tagName || 'button') : (data.tagName || 'div');
        tagName = /** @type {keyof HTMLElementTagNameMap} */ (tagName.toLowerCase());
        /** @type {HTMLElement} */
        var element = (0, util_js_1.createElement)(className, tagName);
        if (data.isButton) {
            if (tagName === 'button') {
                /** @type {HTMLButtonElement} */ (element).type = 'button';
            }
            var title = data.title;
            var ariaLabel = data.ariaLabel;
            // @ts-expect-error lookup only by `data.name` maybe?
            if (typeof pswp.options[name + 'Title'] === 'string') {
                // @ts-expect-error lookup only by `data.name` maybe?
                title = pswp.options[name + 'Title'];
            }
            if (title) {
                element.title = title;
            }
            var ariaText = ariaLabel || title;
            if (ariaText) {
                element.setAttribute('aria-label', ariaText);
            }
        }
        element.innerHTML = addElementHTML(elementHTML);
        if (data.onInit) {
            data.onInit(element, pswp);
        }
        if (data.onClick) {
            element.onclick = function (e) {
                if (typeof data.onClick === 'string') {
                    // @ts-ignore
                    pswp[data.onClick]();
                }
                else if (typeof data.onClick === 'function') {
                    data.onClick(e, element, pswp);
                }
            };
        }
        // Top bar is default position
        var appendTo = data.appendTo || 'bar';
        /** @type {HTMLElement | undefined} root element by default */
        var container = pswp.element;
        if (appendTo === 'bar') {
            if (!pswp.topBar) {
                pswp.topBar = (0, util_js_1.createElement)('pswp__top-bar pswp__hide-on-close', 'div', pswp.scrollWrap);
            }
            container = pswp.topBar;
        }
        else {
            // element outside of top bar gets a secondary class
            // that makes element fade out on close
            element.classList.add('pswp__hide-on-close');
            if (appendTo === 'wrapper') {
                container = pswp.scrollWrap;
            }
        }
        container === null || container === void 0 ? void 0 : container.appendChild(pswp.applyFilters('uiElement', element, data));
    }
    return UIElement;
}());
exports.default = UIElement;
//# sourceMappingURL=ui-element.js.map