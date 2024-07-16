"use strict";
/** @typedef {import('../photoswipe.js').Point} Point */
Object.defineProperty(exports, "__esModule", { value: true });
exports.isSafari = exports.isPswpClass = exports.getElementsFromOption = exports.specialKeyUsed = exports.LOAD_STATE = exports.decodeImage = exports.removeTransitionStyle = exports.setWidthHeight = exports.setTransitionStyle = exports.setTransform = exports.toTransformString = exports.clamp = exports.pointsEqual = exports.getDistanceBetween = exports.roundPoint = exports.equalizePoints = exports.createElement = void 0;
/**
 * @template {keyof HTMLElementTagNameMap} T
 * @param {string} className
 * @param {T} tagName
 * @param {Node} [appendToEl]
 * @returns {HTMLElementTagNameMap[T]}
 */
function createElement(className, tagName, appendToEl) {
    var el = document.createElement(tagName);
    if (className) {
        el.className = className;
    }
    if (appendToEl) {
        appendToEl.appendChild(el);
    }
    return el;
}
exports.createElement = createElement;
/**
 * @param {Point} p1
 * @param {Point} p2
 * @returns {Point}
 */
function equalizePoints(p1, p2) {
    p1.x = p2.x;
    p1.y = p2.y;
    if (p2.id !== undefined) {
        p1.id = p2.id;
    }
    return p1;
}
exports.equalizePoints = equalizePoints;
/**
 * @param {Point} p
 */
function roundPoint(p) {
    p.x = Math.round(p.x);
    p.y = Math.round(p.y);
}
exports.roundPoint = roundPoint;
/**
 * Returns distance between two points.
 *
 * @param {Point} p1
 * @param {Point} p2
 * @returns {number}
 */
function getDistanceBetween(p1, p2) {
    var x = Math.abs(p1.x - p2.x);
    var y = Math.abs(p1.y - p2.y);
    return Math.sqrt((x * x) + (y * y));
}
exports.getDistanceBetween = getDistanceBetween;
/**
 * Whether X and Y positions of points are equal
 *
 * @param {Point} p1
 * @param {Point} p2
 * @returns {boolean}
 */
function pointsEqual(p1, p2) {
    return p1.x === p2.x && p1.y === p2.y;
}
exports.pointsEqual = pointsEqual;
/**
 * The float result between the min and max values.
 *
 * @param {number} val
 * @param {number} min
 * @param {number} max
 * @returns {number}
 */
function clamp(val, min, max) {
    return Math.min(Math.max(val, min), max);
}
exports.clamp = clamp;
/**
 * Get transform string
 *
 * @param {number} x
 * @param {number} [y]
 * @param {number} [scale]
 * @returns {string}
 */
function toTransformString(x, y, scale) {
    var propValue = "translate3d(".concat(x, "px,").concat(y || 0, "px,0)");
    if (scale !== undefined) {
        propValue += " scale3d(".concat(scale, ",").concat(scale, ",1)");
    }
    return propValue;
}
exports.toTransformString = toTransformString;
/**
 * Apply transform:translate(x, y) scale(scale) to element
 *
 * @param {HTMLElement} el
 * @param {number} x
 * @param {number} [y]
 * @param {number} [scale]
 */
function setTransform(el, x, y, scale) {
    el.style.transform = toTransformString(x, y, scale);
}
exports.setTransform = setTransform;
var defaultCSSEasing = 'cubic-bezier(.4,0,.22,1)';
/**
 * Apply CSS transition to element
 *
 * @param {HTMLElement} el
 * @param {string} [prop] CSS property to animate
 * @param {number} [duration] in ms
 * @param {string} [ease] CSS easing function
 */
function setTransitionStyle(el, prop, duration, ease) {
    // inOut: 'cubic-bezier(.4, 0, .22, 1)', // for "toggle state" transitions
    // out: 'cubic-bezier(0, 0, .22, 1)', // for "show" transitions
    // in: 'cubic-bezier(.4, 0, 1, 1)'// for "hide" transitions
    el.style.transition = prop
        ? "".concat(prop, " ").concat(duration, "ms ").concat(ease || defaultCSSEasing)
        : 'none';
}
exports.setTransitionStyle = setTransitionStyle;
/**
 * Apply width and height CSS properties to element
 *
 * @param {HTMLElement} el
 * @param {string | number} w
 * @param {string | number} h
 */
function setWidthHeight(el, w, h) {
    el.style.width = (typeof w === 'number') ? "".concat(w, "px") : w;
    el.style.height = (typeof h === 'number') ? "".concat(h, "px") : h;
}
exports.setWidthHeight = setWidthHeight;
/**
 * @param {HTMLElement} el
 */
function removeTransitionStyle(el) {
    setTransitionStyle(el);
}
exports.removeTransitionStyle = removeTransitionStyle;
/**
 * @param {HTMLImageElement} img
 * @returns {Promise<HTMLImageElement | void>}
 */
function decodeImage(img) {
    if ('decode' in img) {
        return img.decode().catch(function () { });
    }
    if (img.complete) {
        return Promise.resolve(img);
    }
    return new Promise(function (resolve, reject) {
        img.onload = function () { return resolve(img); };
        img.onerror = reject;
    });
}
exports.decodeImage = decodeImage;
/** @typedef {LOAD_STATE[keyof LOAD_STATE]} LoadState */
/** @type {{ IDLE: 'idle'; LOADING: 'loading'; LOADED: 'loaded'; ERROR: 'error' }} */
exports.LOAD_STATE = {
    IDLE: 'idle',
    LOADING: 'loading',
    LOADED: 'loaded',
    ERROR: 'error',
};
/**
 * Check if click or keydown event was dispatched
 * with a special key or via mouse wheel.
 *
 * @param {MouseEvent | KeyboardEvent} e
 * @returns {boolean}
 */
function specialKeyUsed(e) {
    return ('button' in e && e.button === 1) || e.ctrlKey || e.metaKey || e.altKey || e.shiftKey;
}
exports.specialKeyUsed = specialKeyUsed;
/**
 * Parse `gallery` or `children` options.
 *
 * @param {import('../photoswipe.js').ElementProvider} [option]
 * @param {string} [legacySelector]
 * @param {HTMLElement | Document} [parent]
 * @returns HTMLElement[]
 */
function getElementsFromOption(option, legacySelector, parent) {
    if (parent === void 0) { parent = document; }
    /** @type {HTMLElement[]} */
    var elements = [];
    if (option instanceof Element) {
        elements = [option];
    }
    else if (option instanceof NodeList || Array.isArray(option)) {
        elements = Array.from(option);
    }
    else {
        var selector = typeof option === 'string' ? option : legacySelector;
        if (selector) {
            elements = Array.from(parent.querySelectorAll(selector));
        }
    }
    return elements;
}
exports.getElementsFromOption = getElementsFromOption;
/**
 * Check if variable is PhotoSwipe class
 *
 * @param {any} fn
 * @returns {boolean}
 */
function isPswpClass(fn) {
    return typeof fn === 'function'
        && fn.prototype
        && fn.prototype.goTo;
}
exports.isPswpClass = isPswpClass;
/**
 * Check if browser is Safari
 *
 * @returns {boolean}
 */
function isSafari() {
    return !!(navigator.vendor && navigator.vendor.match(/apple/i));
}
exports.isSafari = isSafari;
//# sourceMappingURL=util.js.map