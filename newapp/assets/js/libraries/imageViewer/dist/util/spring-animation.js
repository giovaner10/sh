"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var spring_easer_js_1 = require("./spring-easer.js");
/** @typedef {import('./animations.js').SharedAnimationProps} SharedAnimationProps */
/**
 * @typedef {Object} DefaultSpringAnimationProps
 *
 * @prop {number} start
 * @prop {number} end
 * @prop {number} velocity
 * @prop {number} [dampingRatio]
 * @prop {number} [naturalFrequency]
 * @prop {(end: number) => void} onUpdate
 */
/** @typedef {SharedAnimationProps & DefaultSpringAnimationProps} SpringAnimationProps */
var SpringAnimation = /** @class */ (function () {
    /**
     * @param {SpringAnimationProps} props
     */
    function SpringAnimation(props) {
        var _this = this;
        this.props = props;
        this._raf = 0;
        var start = props.start, end = props.end, velocity = props.velocity, onUpdate = props.onUpdate, onComplete = props.onComplete, _a = props.onFinish, onFinish = _a === void 0 ? function () { } : _a, dampingRatio = props.dampingRatio, naturalFrequency = props.naturalFrequency;
        this.onFinish = onFinish;
        var easer = new spring_easer_js_1.default(velocity, dampingRatio, naturalFrequency);
        var prevTime = Date.now();
        var deltaPosition = start - end;
        var animationLoop = function () {
            if (_this._raf) {
                deltaPosition = easer.easeFrame(deltaPosition, Date.now() - prevTime);
                // Stop the animation if velocity is low and position is close to end
                if (Math.abs(deltaPosition) < 1 && Math.abs(easer.velocity) < 50) {
                    // Finalize the animation
                    onUpdate(end);
                    if (onComplete) {
                        onComplete();
                    }
                    _this.onFinish();
                }
                else {
                    prevTime = Date.now();
                    onUpdate(deltaPosition + end);
                    _this._raf = requestAnimationFrame(animationLoop);
                }
            }
        };
        this._raf = requestAnimationFrame(animationLoop);
    }
    // Destroy is called automatically onFinish
    SpringAnimation.prototype.destroy = function () {
        if (this._raf >= 0) {
            cancelAnimationFrame(this._raf);
        }
        this._raf = 0;
    };
    return SpringAnimation;
}());
exports.default = SpringAnimation;
//# sourceMappingURL=spring-animation.js.map