"use strict";
/** @typedef {import('./slide.js').SlideData} SlideData */
/** @typedef {import('../photoswipe.js').default} PhotoSwipe */
Object.defineProperty(exports, "__esModule", { value: true });
exports.getThumbBounds = void 0;
/** @typedef {{ x: number; y: number; w: number; innerRect?: { w: number; h: number; x: number; y: number } }} Bounds */
/**
 * @param {HTMLElement} el
 * @returns Bounds
 */
function getBoundsByElement(el) {
    var thumbAreaRect = el.getBoundingClientRect();
    return {
        x: thumbAreaRect.left,
        y: thumbAreaRect.top,
        w: thumbAreaRect.width
    };
}
/**
 * @param {HTMLElement} el
 * @param {number} imageWidth
 * @param {number} imageHeight
 * @returns Bounds
 */
function getCroppedBoundsByElement(el, imageWidth, imageHeight) {
    var thumbAreaRect = el.getBoundingClientRect();
    // fill image into the area
    // (do they same as object-fit:cover does to retrieve coordinates)
    var hRatio = thumbAreaRect.width / imageWidth;
    var vRatio = thumbAreaRect.height / imageHeight;
    var fillZoomLevel = hRatio > vRatio ? hRatio : vRatio;
    var offsetX = (thumbAreaRect.width - imageWidth * fillZoomLevel) / 2;
    var offsetY = (thumbAreaRect.height - imageHeight * fillZoomLevel) / 2;
    /**
     * Coordinates of the image,
     * as if it was not cropped,
     * height is calculated automatically
     *
     * @type {Bounds}
     */
    var bounds = {
        x: thumbAreaRect.left + offsetX,
        y: thumbAreaRect.top + offsetY,
        w: imageWidth * fillZoomLevel
    };
    // Coordinates of inner crop area
    // relative to the image
    bounds.innerRect = {
        w: thumbAreaRect.width,
        h: thumbAreaRect.height,
        x: offsetX,
        y: offsetY
    };
    return bounds;
}
/**
 * Get dimensions of thumbnail image
 * (click on which opens photoswipe or closes photoswipe to)
 *
 * @param {number} index
 * @param {SlideData} itemData
 * @param {PhotoSwipe} instance PhotoSwipe instance
 * @returns {Bounds | undefined}
 */
function getThumbBounds(index, itemData, instance) {
    // legacy event, before filters were introduced
    var event = instance.dispatch('thumbBounds', {
        index: index,
        itemData: itemData,
        instance: instance
    });
    // @ts-expect-error
    if (event.thumbBounds) {
        // @ts-expect-error
        return event.thumbBounds;
    }
    var element = itemData.element;
    /** @type {Bounds | undefined} */
    var thumbBounds;
    /** @type {HTMLElement | null | undefined} */
    var thumbnail;
    if (element && instance.options.thumbSelector !== false) {
        var thumbSelector = instance.options.thumbSelector || 'img';
        thumbnail = element.matches(thumbSelector)
            ? element : /** @type {HTMLElement | null} */ (element.querySelector(thumbSelector));
    }
    thumbnail = instance.applyFilters('thumbEl', thumbnail, itemData, index);
    if (thumbnail) {
        if (!itemData.thumbCropped) {
            thumbBounds = getBoundsByElement(thumbnail);
        }
        else {
            thumbBounds = getCroppedBoundsByElement(thumbnail, itemData.width || itemData.w || 0, itemData.height || itemData.h || 0);
        }
    }
    return instance.applyFilters('thumbBounds', thumbBounds, itemData, index);
}
exports.getThumbBounds = getThumbBounds;
//# sourceMappingURL=get-thumb-bounds.js.map