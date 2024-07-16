"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var util_js_1 = require("../util/util.js");
var drag_handler_js_1 = require("./drag-handler.js");
var zoom_handler_js_1 = require("./zoom-handler.js");
var tap_handler_js_1 = require("./tap-handler.js");
/** @typedef {import('../photoswipe.js').default} PhotoSwipe */
/** @typedef {import('../photoswipe.js').Point} Point */
// How far should user should drag
// until we can determine that the gesture is swipe and its direction
var AXIS_SWIPE_HYSTERISIS = 10;
//const PAN_END_FRICTION = 0.35;
var DOUBLE_TAP_DELAY = 300; // ms
var MIN_TAP_DISTANCE = 25; // px
/**
 * Gestures class bind touch, pointer or mouse events
 * and emits drag to drag-handler and zoom events zoom-handler.
 *
 * Drag and zoom events are emited in requestAnimationFrame,
 * and only when one of pointers was actually changed.
 */
var Gestures = /** @class */ (function () {
    /**
     * @param {PhotoSwipe} pswp
     */
    function Gestures(pswp) {
        var _this = this;
        this.pswp = pswp;
        /** @type {'x' | 'y' | null} */
        this.dragAxis = null;
        // point objects are defined once and reused
        // PhotoSwipe keeps track only of two pointers, others are ignored
        /** @type {Point} */
        this.p1 = { x: 0, y: 0 }; // the first pressed pointer
        /** @type {Point} */
        this.p2 = { x: 0, y: 0 }; // the second pressed pointer
        /** @type {Point} */
        this.prevP1 = { x: 0, y: 0 };
        /** @type {Point} */
        this.prevP2 = { x: 0, y: 0 };
        /** @type {Point} */
        this.startP1 = { x: 0, y: 0 };
        /** @type {Point} */
        this.startP2 = { x: 0, y: 0 };
        /** @type {Point} */
        this.velocity = { x: 0, y: 0 };
        /** @type {Point}
         * @private
         */
        this._lastStartP1 = { x: 0, y: 0 };
        /** @type {Point}
         * @private
         */
        this._intervalP1 = { x: 0, y: 0 };
        /** @private */
        this._numActivePoints = 0;
        /** @type {Point[]}
         * @private
         */
        this._ongoingPointers = [];
        /** @private */
        this._touchEventEnabled = 'ontouchstart' in window;
        /** @private */
        this._pointerEventEnabled = !!(window.PointerEvent);
        this.supportsTouch = this._touchEventEnabled
            || (this._pointerEventEnabled && navigator.maxTouchPoints > 1);
        /** @private */
        this._numActivePoints = 0;
        /** @private */
        this._intervalTime = 0;
        /** @private */
        this._velocityCalculated = false;
        this.isMultitouch = false;
        this.isDragging = false;
        this.isZooming = false;
        /** @type {number | null} */
        this.raf = null;
        /** @type {NodeJS.Timeout | null}
         * @private
         */
        this._tapTimer = null;
        if (!this.supportsTouch) {
            // disable pan to next slide for non-touch devices
            pswp.options.allowPanToNext = false;
        }
        this.drag = new drag_handler_js_1.default(this);
        this.zoomLevels = new zoom_handler_js_1.default(this);
        this.tapHandler = new tap_handler_js_1.default(this);
        pswp.on('bindEvents', function () {
            pswp.events.add(pswp.scrollWrap, 'click', 
            /** @type EventListener */ (_this._onClick.bind(_this)));
            if (_this._pointerEventEnabled) {
                _this._bindEvents('pointer', 'down', 'up', 'cancel');
            }
            else if (_this._touchEventEnabled) {
                _this._bindEvents('touch', 'start', 'end', 'cancel');
                // In previous versions we also bound mouse event here,
                // in case device supports both touch and mouse events,
                // but newer versions of browsers now support PointerEvent.
                // on iOS10 if you bind touchmove/end after touchstart,
                // and you don't preventDefault touchstart (which PhotoSwipe does),
                // preventDefault will have no effect on touchmove and touchend.
                // Unless you bind it previously.
                if (pswp.scrollWrap) {
                    pswp.scrollWrap.ontouchmove = function () { };
                    pswp.scrollWrap.ontouchend = function () { };
                }
            }
            else {
                _this._bindEvents('mouse', 'down', 'up');
            }
        });
    }
    /**
     * @private
     * @param {'mouse' | 'touch' | 'pointer'} pref
     * @param {'down' | 'start'} down
     * @param {'up' | 'end'} up
     * @param {'cancel'} [cancel]
     */
    Gestures.prototype._bindEvents = function (pref, down, up, cancel) {
        var pswp = this.pswp;
        var events = pswp.events;
        var cancelEvent = cancel ? pref + cancel : '';
        events.add(pswp.scrollWrap, pref + down, 
        /** @type EventListener */ (this.onPointerDown.bind(this)));
        events.add(window, pref + 'move', /** @type EventListener */ (this.onPointerMove.bind(this)));
        events.add(window, pref + up, /** @type EventListener */ (this.onPointerUp.bind(this)));
        if (cancelEvent) {
            events.add(pswp.scrollWrap, cancelEvent, 
            /** @type EventListener */ (this.onPointerUp.bind(this)));
        }
    };
    /**
     * @param {PointerEvent} e
     */
    Gestures.prototype.onPointerDown = function (e) {
        // We do not call preventDefault for touch events
        // to allow browser to show native dialog on longpress
        // (the one that allows to save image or open it in new tab).
        //
        // Desktop Safari allows to drag images when preventDefault isn't called on mousedown,
        // even though preventDefault IS called on mousemove. That's why we preventDefault mousedown.
        var isMousePointer = e.type === 'mousedown' || e.pointerType === 'mouse';
        // Allow dragging only via left mouse button.
        // http://www.quirksmode.org/js/events_properties.html
        // https://developer.mozilla.org/en-US/docs/Web/API/event.button
        if (isMousePointer && e.button > 0) {
            return;
        }
        var pswp = this.pswp;
        // if PhotoSwipe is opening or closing
        if (!pswp.opener.isOpen) {
            e.preventDefault();
            return;
        }
        if (pswp.dispatch('pointerDown', { originalEvent: e }).defaultPrevented) {
            return;
        }
        if (isMousePointer) {
            pswp.mouseDetected();
            // preventDefault mouse event to prevent
            // browser image drag feature
            this._preventPointerEventBehaviour(e, 'down');
        }
        pswp.animations.stopAll();
        this._updatePoints(e, 'down');
        if (this._numActivePoints === 1) {
            this.dragAxis = null;
            // we need to store initial point to determine the main axis,
            // drag is activated only after the axis is determined
            (0, util_js_1.equalizePoints)(this.startP1, this.p1);
        }
        if (this._numActivePoints > 1) {
            // Tap or double tap should not trigger if more than one pointer
            this._clearTapTimer();
            this.isMultitouch = true;
        }
        else {
            this.isMultitouch = false;
        }
    };
    /**
     * @param {PointerEvent} e
     */
    Gestures.prototype.onPointerMove = function (e) {
        this._preventPointerEventBehaviour(e, 'move');
        if (!this._numActivePoints) {
            return;
        }
        this._updatePoints(e, 'move');
        if (this.pswp.dispatch('pointerMove', { originalEvent: e }).defaultPrevented) {
            return;
        }
        if (this._numActivePoints === 1 && !this.isDragging) {
            if (!this.dragAxis) {
                this._calculateDragDirection();
            }
            // Drag axis was detected, emit drag.start
            if (this.dragAxis && !this.isDragging) {
                if (this.isZooming) {
                    this.isZooming = false;
                    this.zoomLevels.end();
                }
                this.isDragging = true;
                this._clearTapTimer(); // Tap can not trigger after drag
                // Adjust starting point
                this._updateStartPoints();
                this._intervalTime = Date.now();
                //this._startTime = this._intervalTime;
                this._velocityCalculated = false;
                (0, util_js_1.equalizePoints)(this._intervalP1, this.p1);
                this.velocity.x = 0;
                this.velocity.y = 0;
                this.drag.start();
                this._rafStopLoop();
                this._rafRenderLoop();
            }
        }
        else if (this._numActivePoints > 1 && !this.isZooming) {
            this._finishDrag();
            this.isZooming = true;
            // Adjust starting points
            this._updateStartPoints();
            this.zoomLevels.start();
            this._rafStopLoop();
            this._rafRenderLoop();
        }
    };
    /**
     * @private
     */
    Gestures.prototype._finishDrag = function () {
        if (this.isDragging) {
            this.isDragging = false;
            // Try to calculate velocity,
            // if it wasn't calculated yet in drag.change
            if (!this._velocityCalculated) {
                this._updateVelocity(true);
            }
            this.drag.end();
            this.dragAxis = null;
        }
    };
    /**
     * @param {PointerEvent} e
     */
    Gestures.prototype.onPointerUp = function (e) {
        if (!this._numActivePoints) {
            return;
        }
        this._updatePoints(e, 'up');
        if (this.pswp.dispatch('pointerUp', { originalEvent: e }).defaultPrevented) {
            return;
        }
        if (this._numActivePoints === 0) {
            this._rafStopLoop();
            if (this.isDragging) {
                this._finishDrag();
            }
            else if (!this.isZooming && !this.isMultitouch) {
                //this.zoomLevels.correctZoomPan();
                this._finishTap(e);
            }
        }
        if (this._numActivePoints < 2 && this.isZooming) {
            this.isZooming = false;
            this.zoomLevels.end();
            if (this._numActivePoints === 1) {
                // Since we have 1 point left, we need to reinitiate drag
                this.dragAxis = null;
                this._updateStartPoints();
            }
        }
    };
    /**
     * @private
     */
    Gestures.prototype._rafRenderLoop = function () {
        if (this.isDragging || this.isZooming) {
            this._updateVelocity();
            if (this.isDragging) {
                // make sure that pointer moved since the last update
                if (!(0, util_js_1.pointsEqual)(this.p1, this.prevP1)) {
                    this.drag.change();
                }
            }
            else /* if (this.isZooming) */ {
                if (!(0, util_js_1.pointsEqual)(this.p1, this.prevP1)
                    || !(0, util_js_1.pointsEqual)(this.p2, this.prevP2)) {
                    this.zoomLevels.change();
                }
            }
            this._updatePrevPoints();
            this.raf = requestAnimationFrame(this._rafRenderLoop.bind(this));
        }
    };
    /**
     * Update velocity at 50ms interval
     *
     * @private
     * @param {boolean} [force]
     */
    Gestures.prototype._updateVelocity = function (force) {
        var time = Date.now();
        var duration = time - this._intervalTime;
        if (duration < 50 && !force) {
            return;
        }
        this.velocity.x = this._getVelocity('x', duration);
        this.velocity.y = this._getVelocity('y', duration);
        this._intervalTime = time;
        (0, util_js_1.equalizePoints)(this._intervalP1, this.p1);
        this._velocityCalculated = true;
    };
    /**
     * @private
     * @param {PointerEvent} e
     */
    Gestures.prototype._finishTap = function (e) {
        var _this = this;
        var mainScroll = this.pswp.mainScroll;
        // Do not trigger tap events if main scroll is shifted
        if (mainScroll.isShifted()) {
            // restore main scroll position
            // (usually happens if stopped in the middle of animation)
            mainScroll.moveIndexBy(0, true);
            return;
        }
        // Do not trigger tap for touchcancel or pointercancel
        if (e.type.indexOf('cancel') > 0) {
            return;
        }
        // Trigger click instead of tap for mouse events
        if (e.type === 'mouseup' || e.pointerType === 'mouse') {
            this.tapHandler.click(this.startP1, e);
            return;
        }
        // Disable delay if there is no doubleTapAction
        var tapDelay = this.pswp.options.doubleTapAction ? DOUBLE_TAP_DELAY : 0;
        // If tapTimer is defined - we tapped recently,
        // check if the current tap is close to the previous one,
        // if yes - trigger double tap
        if (this._tapTimer) {
            this._clearTapTimer();
            // Check if two taps were more or less on the same place
            if ((0, util_js_1.getDistanceBetween)(this._lastStartP1, this.startP1) < MIN_TAP_DISTANCE) {
                this.tapHandler.doubleTap(this.startP1, e);
            }
        }
        else {
            (0, util_js_1.equalizePoints)(this._lastStartP1, this.startP1);
            this._tapTimer = setTimeout(function () {
                _this.tapHandler.tap(_this.startP1, e);
                _this._clearTapTimer();
            }, tapDelay);
        }
    };
    /**
     * @private
     */
    Gestures.prototype._clearTapTimer = function () {
        if (this._tapTimer) {
            clearTimeout(this._tapTimer);
            this._tapTimer = null;
        }
    };
    /**
     * Get velocity for axis
     *
     * @private
     * @param {'x' | 'y'} axis
     * @param {number} duration
     * @returns {number}
     */
    Gestures.prototype._getVelocity = function (axis, duration) {
        // displacement is like distance, but can be negative.
        var displacement = this.p1[axis] - this._intervalP1[axis];
        if (Math.abs(displacement) > 1 && duration > 5) {
            return displacement / duration;
        }
        return 0;
    };
    /**
     * @private
     */
    Gestures.prototype._rafStopLoop = function () {
        if (this.raf) {
            cancelAnimationFrame(this.raf);
            this.raf = null;
        }
    };
    /**
     * @private
     * @param {PointerEvent} e
     * @param {'up' | 'down' | 'move'} pointerType Normalized pointer type
     */
    Gestures.prototype._preventPointerEventBehaviour = function (e, pointerType) {
        var preventPointerEvent = this.pswp.applyFilters('preventPointerEvent', true, e, pointerType);
        if (preventPointerEvent) {
            e.preventDefault();
        }
    };
    /**
     * Parses and normalizes points from the touch, mouse or pointer event.
     * Updates p1 and p2.
     *
     * @private
     * @param {PointerEvent | TouchEvent} e
     * @param {'up' | 'down' | 'move'} pointerType Normalized pointer type
     */
    Gestures.prototype._updatePoints = function (e, pointerType) {
        if (this._pointerEventEnabled) {
            var pointerEvent_1 = /** @type {PointerEvent} */ (e);
            // Try to find the current pointer in ongoing pointers by its ID
            var pointerIndex = this._ongoingPointers.findIndex(function (ongoingPointer) {
                return ongoingPointer.id === pointerEvent_1.pointerId;
            });
            if (pointerType === 'up' && pointerIndex > -1) {
                // release the pointer - remove it from ongoing
                this._ongoingPointers.splice(pointerIndex, 1);
            }
            else if (pointerType === 'down' && pointerIndex === -1) {
                // add new pointer
                this._ongoingPointers.push(this._convertEventPosToPoint(pointerEvent_1, { x: 0, y: 0 }));
            }
            else if (pointerIndex > -1) {
                // update existing pointer
                this._convertEventPosToPoint(pointerEvent_1, this._ongoingPointers[pointerIndex]);
            }
            this._numActivePoints = this._ongoingPointers.length;
            // update points that PhotoSwipe uses
            // to calculate position and scale
            if (this._numActivePoints > 0) {
                (0, util_js_1.equalizePoints)(this.p1, this._ongoingPointers[0]);
            }
            if (this._numActivePoints > 1) {
                (0, util_js_1.equalizePoints)(this.p2, this._ongoingPointers[1]);
            }
        }
        else {
            var touchEvent = /** @type {TouchEvent} */ (e);
            this._numActivePoints = 0;
            if (touchEvent.type.indexOf('touch') > -1) {
                // Touch Event
                // https://developer.mozilla.org/en-US/docs/Web/API/TouchEvent
                if (touchEvent.touches && touchEvent.touches.length > 0) {
                    this._convertEventPosToPoint(touchEvent.touches[0], this.p1);
                    this._numActivePoints++;
                    if (touchEvent.touches.length > 1) {
                        this._convertEventPosToPoint(touchEvent.touches[1], this.p2);
                        this._numActivePoints++;
                    }
                }
            }
            else {
                // Mouse Event
                this._convertEventPosToPoint(/** @type {PointerEvent} */ (e), this.p1);
                if (pointerType === 'up') {
                    // clear all points on mouseup
                    this._numActivePoints = 0;
                }
                else {
                    this._numActivePoints++;
                }
            }
        }
    };
    /** update points that were used during previous rAF tick
     * @private
     */
    Gestures.prototype._updatePrevPoints = function () {
        (0, util_js_1.equalizePoints)(this.prevP1, this.p1);
        (0, util_js_1.equalizePoints)(this.prevP2, this.p2);
    };
    /** update points at the start of gesture
     * @private
     */
    Gestures.prototype._updateStartPoints = function () {
        (0, util_js_1.equalizePoints)(this.startP1, this.p1);
        (0, util_js_1.equalizePoints)(this.startP2, this.p2);
        this._updatePrevPoints();
    };
    /** @private */
    Gestures.prototype._calculateDragDirection = function () {
        if (this.pswp.mainScroll.isShifted()) {
            // if main scroll position is shifted – direction is always horizontal
            this.dragAxis = 'x';
        }
        else {
            // calculate delta of the last touchmove tick
            var diff = Math.abs(this.p1.x - this.startP1.x) - Math.abs(this.p1.y - this.startP1.y);
            if (diff !== 0) {
                // check if pointer was shifted horizontally or vertically
                var axisToCheck = diff > 0 ? 'x' : 'y';
                if (Math.abs(this.p1[axisToCheck] - this.startP1[axisToCheck]) >= AXIS_SWIPE_HYSTERISIS) {
                    this.dragAxis = axisToCheck;
                }
            }
        }
    };
    /**
     * Converts touch, pointer or mouse event
     * to PhotoSwipe point.
     *
     * @private
     * @param {Touch | PointerEvent} e
     * @param {Point} p
     * @returns {Point}
     */
    Gestures.prototype._convertEventPosToPoint = function (e, p) {
        p.x = e.pageX - this.pswp.offset.x;
        p.y = e.pageY - this.pswp.offset.y;
        if ('pointerId' in e) {
            p.id = e.pointerId;
        }
        else if (e.identifier !== undefined) {
            p.id = e.identifier;
        }
        return p;
    };
    /**
     * @private
     * @param {PointerEvent} e
     */
    Gestures.prototype._onClick = function (e) {
        // Do not allow click event to pass through after drag
        if (this.pswp.mainScroll.isShifted()) {
            e.preventDefault();
            e.stopPropagation();
        }
    };
    return Gestures;
}());
exports.default = Gestures;
//# sourceMappingURL=gestures.js.map