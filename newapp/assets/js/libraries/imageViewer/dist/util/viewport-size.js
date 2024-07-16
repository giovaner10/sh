"use strict";
/** @typedef {import('../photoswipe.js').PhotoSwipeOptions} PhotoSwipeOptions */
/** @typedef {import('../core/base.js').default} PhotoSwipeBase */
/** @typedef {import('../photoswipe.js').Point} Point */
/** @typedef {import('../slide/slide.js').SlideData} SlideData */
Object.defineProperty(exports, "__esModule", { value: true });
exports.getPanAreaSize = exports.parsePaddingOption = exports.getViewportSize = void 0;
/**
 * @param {PhotoSwipeOptions} options
 * @param {PhotoSwipeBase} pswp
 * @returns {Point}
 */
function getViewportSize(options, pswp) {
    if (options.getViewportSizeFn) {
        var newViewportSize = options.getViewportSizeFn(options, pswp);
        if (newViewportSize) {
            return newViewportSize;
        }
    }
    return {
        x: document.documentElement.clientWidth,
        // TODO: height on mobile is very incosistent due to toolbar
        // find a way to improve this
        //
        // document.documentElement.clientHeight - doesn't seem to work well
        y: window.innerHeight
    };
}
exports.getViewportSize = getViewportSize;
/**
 * Parses padding option.
 * Supported formats:
 *
 * // Object
 * padding: {
 *  top: 0,
 *  bottom: 0,
 *  left: 0,
 *  right: 0
 * }
 *
 * // A function that returns the object
 * paddingFn: (viewportSize, itemData, index) => {
 *  return {
 *    top: 0,
 *    bottom: 0,
 *    left: 0,
 *    right: 0
 *  };
 * }
 *
 * // Legacy variant
 * paddingLeft: 0,
 * paddingRight: 0,
 * paddingTop: 0,
 * paddingBottom: 0,
 *
 * @param {'left' | 'top' | 'bottom' | 'right'} prop
 * @param {PhotoSwipeOptions} options PhotoSwipe options
 * @param {Point} viewportSize PhotoSwipe viewport size, for example: { x:800, y:600 }
 * @param {SlideData} itemData Data about the slide
 * @param {number} index Slide index
 * @returns {number}
 */
function parsePaddingOption(prop, options, viewportSize, itemData, index) {
    var paddingValue = 0;
    if (options.paddingFn) {
        paddingValue = options.paddingFn(viewportSize, itemData, index)[prop];
    }
    else if (options.padding) {
        paddingValue = options.padding[prop];
    }
    else {
        var legacyPropName = 'padding' + prop[0].toUpperCase() + prop.slice(1);
        // @ts-expect-error
        if (options[legacyPropName]) {
            // @ts-expect-error
            paddingValue = options[legacyPropName];
        }
    }
    return Number(paddingValue) || 0;
}
exports.parsePaddingOption = parsePaddingOption;
/**
 * @param {PhotoSwipeOptions} options
 * @param {Point} viewportSize
 * @param {SlideData} itemData
 * @param {number} index
 * @returns {Point}
 */
function getPanAreaSize(options, viewportSize, itemData, index) {
    return {
        x: viewportSize.x
            - parsePaddingOption('left', options, viewportSize, itemData, index)
            - parsePaddingOption('right', options, viewportSize, itemData, index),
        y: viewportSize.y
            - parsePaddingOption('top', options, viewportSize, itemData, index)
            - parsePaddingOption('bottom', options, viewportSize, itemData, index)
    };
}
exports.getPanAreaSize = getPanAreaSize;
//# sourceMappingURL=viewport-size.js.map