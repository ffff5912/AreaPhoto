var EventEmitter = (function() {
    function EventEmitter() {
        this._handlers = [];
    }
    EventEmitter.prototype.on = function(type, handler) {
        if (typeof this._handlers[type] === 'undefined') {
            this._handlers[type] = [];
        }

        this._handlers[type].push(handler);
    };

    EventEmitter.prototype.emit = function(type, data) {
        var handlers = this._handlers[type] || [];
        for (var i = 0; i < handlers.length; i++) {
            var handler = handlers[i];
            handler.call(this, data);
        }
    };

    return EventEmitter;
})();

module.exports = EventEmitter;
