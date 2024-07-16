"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var util_js_1 = require("./util.js");
var DEFAULT_EASING = 'cubic-bezier(.4,0,.22,1)';
/** @typedef {import('./animations.js').SharedAnimationProps} SharedAnimationProps */
/** @typedef {Object} DefaultCssAnimationProps
 *
 * @prop {HTMLElement} target
 * @prop {number} [duration]
 * @prop {string} [easing]
 * @prop {string} [transform]
 * @prop {string} [opacity]
 * */
/** @typedef {SharedAnimationProps & DefaultCssAnimationProps} CssAnimationProps */
/**
 * Runs CSS transition.
 */
var CSSAnimation = /** @class */ (function () {
    /**
     * onComplete can be unpredictable, be careful about current state
     *
     * @param {CssAnimationProps} props
     */
    function CSSAnimation(props) {
        var _this = this;
        var _a;
        this.props = props;
        var target = props.target, onComplete = props.onComplete, transform = props.transform, _b = props.onFinish, onFinish = _b === void 0 ? function () { } : _b, _c = props.duration, duration = _c === void 0 ? 333 : _c, _d = props.easing, easing = _d === void 0 ? DEFAULT_EASING : _d;
        this.onFinish = onFinish;
        // support only transform and opacity
        var prop = transform ? 'transform' : 'opacity';
        var propValue = (_a = props[prop]) !== null && _a !== void 0 ? _a : '';
        /** @private */
        this._target = target;
        /** @private */
        this._onComplete = onComplete;
        /** @private */
        this._finished = false;
        /** @private */
        this._onTransitionEnd = this._onTransitionEnd.bind(this);
        // Using timeout hack to make sure that animation
        // starts even if the animated property was changed recently,
        // otherwise transitionend might not fire or transition won't start.
        // https://drafts.csswg.org/css-transitions/#starting
        //
        // ¯\_(ツ)_/¯
        /** @private */
        this._helperTimeout = setTimeout(function () {
            (0, util_js_1.setTransitionStyle)(target, prop, duration, easing);
            _this._helperTimeout = setTimeout(function () {
                target.addEventListener('transitionend', _this._onTransitionEnd, false);
                target.addEventListener('transitioncancel', _this._onTransitionEnd, false);
                // Safari occasionally does not emit transitionend event
                // if element property was modified during the transition,
                // which may be caused by resize or third party component,
                // using timeout as a safety fallback
                _this._helperTimeout = setTimeout(function () {
                    _this._finalizeAnimation();
                }, duration + 500);
                target.style[prop] = propValue;
            }, 30); // Do not reduce this number
        }, 0);
    }
    /**
     * @private
     * @param {TransitionEvent} e
     */
    CSSAnimation.prototype._onTransitionEnd = function (e) {
        if (e.target === this._target) {
            this._finalizeAnimation();
        }
    };
    /**
     * @private
     */
    CSSAnimation.prototype._finalizeAnimation = function () {
        if (!this._finished) {
            this._finished = true;
            this.onFinish();
            if (this._onComplete) {
                this._onComplete();
            }
        }
    };
    // Destroy is called automatically onFinish
    CSSAnimation.prototype.destroy = function () {
        if (this._helperTimeout) {
            clearTimeout(this._helperTimeout);
        }
        (0, util_js_1.removeTransitionStyle)(this._target);
        this._target.removeEventListener('transitionend', this._onTransitionEnd, false);
        this._target.removeEventListener('transitioncancel', this._onTransitionEnd, false);
        if (!this._finished) {
            this._finalizeAnimation();
        }
    };
    return CSSAnimation;
}());
exports.default = CSSAnimation;
//# sourceMappingURL=css-animation.js.map