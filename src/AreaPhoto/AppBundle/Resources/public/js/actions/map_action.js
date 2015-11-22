var Action = (function() {
    function Action(dispatcher) {
        this.dispatcher = dispatcher;
    }

    Action.prototype.fetch = function(data) {
        this.dispatcher.emit('fetch', data);
    };

    Action.prototype.findByLocationId = function(id, setMedia, setLoading) {
        var data = {id: id, callback: setMedia, setLoading: setLoading};
        this.dispatcher.emit('findByLocationId', data);
    };

    return Action;
})();

module.exports = Action;
