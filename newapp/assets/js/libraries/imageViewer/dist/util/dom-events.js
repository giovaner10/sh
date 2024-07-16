"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
// Detect passive event listener support
var supportsPassive = false;
/* eslint-disable */
try {
    /* @ts-ignore */
    window.addEventListener('test', null, Object.defineProperty({}, 'passive', {
        get: function () {
            supportsPassive = true;
        }
    }));
}
catch (e) { }
/* eslint-enable */
/**
 * @typedef {Object} PoolItem
 * @prop {HTMLElement | Window | Document | undefined | null} target
 * @prop {string} type
 * @prop {EventListenerOrEventListenerObject} listener
 * @prop {boolean} [passive]
 */
var DOMEvents = /** @class */ (function () {
    function DOMEvents() {
        /**
         * @type {PoolItem[]}
         * @private
         */
        this._pool = [];
    }
    /**
     * Adds event listeners
     *
     * @param {PoolItem['target']} target
     * @param {PoolItem['type']} type Can be multiple, separated by space.
     * @param {PoolItem['listener']} listener
     * @param {PoolItem['passive']} [passive]
     */
    DOMEvents.prototype.add = function (target, type, listener, passive) {
        this._toggleListener(target, type, listener, passive);
    };
    /**
     * Removes event listeners
     *
     * @param {PoolItem['target']} target
     * @param {PoolItem['type']} type
     * @param {PoolItem['listener']} listener
     * @param {PoolItem['passive']} [passive]
     */
    DOMEvents.prototype.remove = function (target, type, listener, passive) {
        this._toggleListener(target, type, listener, passive, true);
    };
    /**
     * Removes all bound events
     */
    DOMEvents.prototype.removeAll = function () {
        var _this = this;
        this._pool.forEach(function (poolItem) {
            _this._toggleListener(poolItem.target, poolItem.type, poolItem.listener, poolItem.passive, true, true);
        });
        this._pool = [];
    };
    /**
     * Adds or removes event
     *
     * @private
     * @param {PoolItem['target']} target
     * @param {PoolItem['type']} type
     * @param {PoolItem['listener']} listener
     * @param {PoolItem['passive']} [passive]
     * @param {boolean} [unbind] Whether the event should be added or removed
     * @param {boolean} [skipPool] Whether events pool should be skipped
     */
    DOMEvents.prototype._toggleListener = function (target, type, listener, passive, unbind, skipPool) {
        var _this = this;
        if (!target) {
            return;
        }
        var methodName = unbind ? 'removeEventListener' : 'addEventListener';
        var types = type.split(' ');
        types.forEach(function (eType) {
            if (eType) {
                // Events pool is used to easily unbind all events when PhotoSwipe is closed,
                // so developer doesn't need to do this manually
                if (!skipPool) {
                    if (unbind) {
                        // Remove from the events pool
                        _this._pool = _this._pool.filter(function (poolItem) {
                            return poolItem.type !== eType
                                || poolItem.listener !== listener
                                || poolItem.target !== target;
                        });
                    }
                    else {
                        // Add to the events pool
                        _this._pool.push({
                            target: target,
                            type: eType,
                            listener: listener,
                            passive: passive
                        });
                    }
                }
                // most PhotoSwipe events call preventDefault,
                // and we do not need browser to scroll the page
                var eventOptions = supportsPassive ? { passive: (passive || false) } : false;
                target[methodName](eType, listener, eventOptions);
            }
        });
    };
    return DOMEvents;
}());
exports.default = DOMEvents;
//# sourceMappingURL=dom-events.js.map