var Action = (function() {
    function Action(dispatcher) {
        this.dispatcher = dispatcher;
    }

    Action.prototype.findByPopular = function(setMedia) {
        this.dispatcher.on('findByPopular', setMedia);
    };

    return Action;
})();

module.exports = Action;
