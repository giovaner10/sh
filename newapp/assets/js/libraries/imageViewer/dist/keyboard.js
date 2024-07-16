"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var util_js_1 = require("./util/util.js");
/** @typedef {import('./photoswipe.js').default} PhotoSwipe */
/**
 * @template T
 * @typedef {import('./types.js').Methods<T>} Methods<T>
 */
var KeyboardKeyCodesMap = {
    Escape: 27,
    z: 90,
    ArrowLeft: 37,
    ArrowUp: 38,
    ArrowRight: 39,
    ArrowDown: 40,
    Tab: 9,
};
/**
 * @template {keyof KeyboardKeyCodesMap} T
 * @param {T} key
 * @param {boolean} isKeySupported
 * @returns {T | number | undefined}
 */
var getKeyboardEventKey = function (key, isKeySupported) {
    return isKeySupported ? key : KeyboardKeyCodesMap[key];
};
/**
 * - Manages keyboard shortcuts.
 * - Helps trap focus within photoswipe.
 */
var Keyboard = /** @class */ (function () {
    /**
     * @param {PhotoSwipe} pswp
     */
    function Keyboard(pswp) {
        var _this = this;
        this.pswp = pswp;
        /** @private */
        this._wasFocused = false;
        pswp.on('bindEvents', function () {
            if (pswp.options.trapFocus) {
                // Dialog was likely opened by keyboard if initial point is not defined
                if (!pswp.options.initialPointerPos) {
                    // focus causes layout,
                    // which causes lag during the animation,
                    // that's why we delay it until the opener transition ends
                    _this._focusRoot();
                }
                pswp.events.add(document, 'focusin', 
                /** @type EventListener */ (_this._onFocusIn.bind(_this)));
            }
            pswp.events.add(document, 'keydown', /** @type EventListener */ (_this._onKeyDown.bind(_this)));
        });
        var lastActiveElement = /** @type {HTMLElement} */ (document.activeElement);
        pswp.on('destroy', function () {
            if (pswp.options.returnFocus
                && lastActiveElement
                && _this._wasFocused) {
                lastActiveElement.focus();
            }
        });
    }
    /** @private */
    Keyboard.prototype._focusRoot = function () {
        if (!this._wasFocused && this.pswp.element) {
            this.pswp.element.focus();
            this._wasFocused = true;
        }
    };
    /**
     * @private
     * @param {KeyboardEvent} e
     */
    Keyboard.prototype._onKeyDown = function (e) {
        var pswp = this.pswp;
        if (pswp.dispatch('keydown', { originalEvent: e }).defaultPrevented) {
            return;
        }
        if ((0, util_js_1.specialKeyUsed)(e)) {
            // don't do anything if special key pressed
            // to prevent from overriding default browser actions
            // for example, in Chrome on Mac cmd+arrow-left returns to previous page
            return;
        }
        /** @type {Methods<PhotoSwipe> | undefined} */
        var keydownAction;
        /** @type {'x' | 'y' | undefined} */
        var axis;
        var isForward = false;
        var isKeySupported = 'key' in e;
        switch (isKeySupported ? e.key : e.keyCode) {
            case getKeyboardEventKey('Escape', isKeySupported):
                if (pswp.options.escKey) {
                    keydownAction = 'close';
                }
                break;
            case getKeyboardEventKey('z', isKeySupported):
                keydownAction = 'toggleZoom';
                break;
            case getKeyboardEventKey('ArrowLeft', isKeySupported):
                axis = 'x';
                break;
            case getKeyboardEventKey('ArrowUp', isKeySupported):
                axis = 'y';
                break;
            case getKeyboardEventKey('ArrowRight', isKeySupported):
                axis = 'x';
                isForward = true;
                break;
            case getKeyboardEventKey('ArrowDown', isKeySupported):
                isForward = true;
                axis = 'y';
                break;
            case getKeyboardEventKey('Tab', isKeySupported):
                this._focusRoot();
                break;
            default:
        }
        // if left/right/top/bottom key
        if (axis) {
            // prevent page scroll
            e.preventDefault();
            var currSlide = pswp.currSlide;
            if (pswp.options.arrowKeys
                && axis === 'x'
                && pswp.getNumItems() > 1) {
                keydownAction = isForward ? 'next' : 'prev';
            }
            else if (currSlide && currSlide.currZoomLevel > currSlide.zoomLevels.fit) {
                // up/down arrow keys pan the image vertically
                // left/right arrow keys pan horizontally.
                // Unless there is only one image,
                // or arrowKeys option is disabled
                currSlide.pan[axis] += isForward ? -80 : 80;
                currSlide.panTo(currSlide.pan.x, currSlide.pan.y);
            }
        }
        if (keydownAction) {
            e.preventDefault();
            // @ts-ignore
            pswp[keydownAction]();
        }
    };
    /**
     * Trap focus inside photoswipe
     *
     * @private
     * @param {FocusEvent} e
     */
    Keyboard.prototype._onFocusIn = function (e) {
        e.preventDefault();  // Prevent the default focus behavior
        e.stopPropagation(); // Stop the event from bubbling up further
    
        var template = this.pswp.template;
        // Check if the event target is not the document, template, or a child of template
        // and also ensure the template isn't already focused.
        if (template
            && document !== e.target
            && template !== e.target
            && !template.contains(e.target)
            && document.activeElement !== template) {
                try {

                    template.focus();
                } catch (RangeError) {}
        }
    };
    
    return Keyboard;
}());
exports.default = Keyboard;
//# sourceMappingURL=keyboard.js.map