var Action = (function() {
    function Action(dispatcher) {
        this.dispatcher = dispatcher;
    }

    Action.prototype.fetch = function(data) {
        this.dispatcher.emit('fetch', data);
    };

    return Action;
})();

module.exports = Action;
