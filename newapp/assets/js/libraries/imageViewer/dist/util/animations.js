"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var css_animation_js_1 = require("./css-animation.js");
var spring_animation_js_1 = require("./spring-animation.js");
/** @typedef {import('./css-animation.js').CssAnimationProps} CssAnimationProps */
/** @typedef {import('./spring-animation.js').SpringAnimationProps} SpringAnimationProps */
/** @typedef {Object} SharedAnimationProps
 * @prop {string} [name]
 * @prop {boolean} [isPan]
 * @prop {boolean} [isMainScroll]
 * @prop {VoidFunction} [onComplete]
 * @prop {VoidFunction} [onFinish]
 */
/** @typedef {SpringAnimation | CSSAnimation} Animation */
/** @typedef {SpringAnimationProps | CssAnimationProps} AnimationProps */
/**
 * Manages animations
 */
var Animations = /** @class */ (function () {
    function Animations() {
        /** @type {Animation[]} */
        this.activeAnimations = [];
    }
    /**
     * @param {SpringAnimationProps} props
     */
    Animations.prototype.startSpring = function (props) {
        this._start(props, true);
    };
    /**
     * @param {CssAnimationProps} props
     */
    Animations.prototype.startTransition = function (props) {
        this._start(props);
    };
    /**
     * @private
     * @param {AnimationProps} props
     * @param {boolean} [isSpring]
     * @returns {Animation}
     */
    Animations.prototype._start = function (props, isSpring) {
        var _this = this;
        var animation = isSpring
            ? new spring_animation_js_1.default(/** @type SpringAnimationProps */ (props))
            : new css_animation_js_1.default(/** @type CssAnimationProps */ (props));
        this.activeAnimations.push(animation);
        animation.onFinish = function () { return _this.stop(animation); };
        return animation;
    };
    /**
     * @param {Animation} animation
     */
    Animations.prototype.stop = function (animation) {
        animation.destroy();
        var index = this.activeAnimations.indexOf(animation);
        if (index > -1) {
            this.activeAnimations.splice(index, 1);
        }
    };
    Animations.prototype.stopAll = function () {
        this.activeAnimations.forEach(function (animation) {
            animation.destroy();
        });
        this.activeAnimations = [];
    };
    /**
     * Stop all pan or zoom transitions
     */
    Animations.prototype.stopAllPan = function () {
        this.activeAnimations = this.activeAnimations.filter(function (animation) {
            if (animation.props.isPan) {
                animation.destroy();
                return false;
            }
            return true;
        });
    };
    Animations.prototype.stopMainScroll = function () {
        this.activeAnimations = this.activeAnimations.filter(function (animation) {
            if (animation.props.isMainScroll) {
                animation.destroy();
                return false;
            }
            return true;
        });
    };
    /**
     * Returns true if main scroll transition is running
     */
    // isMainScrollRunning() {
    //   return this.activeAnimations.some((animation) => {
    //     return animation.props.isMainScroll;
    //   });
    // }
    /**
     * Returns true if any pan or zoom transition is running
     */
    Animations.prototype.isPanRunning = function () {
        return this.activeAnimations.some(function (animation) {
            return animation.props.isPan;
        });
    };
    return Animations;
}());
exports.default = Animations;
//# sourceMappingURL=animations.js.map