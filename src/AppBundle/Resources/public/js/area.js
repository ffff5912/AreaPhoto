var React = require('react');

function hasValues(value) {
    return value.length > 0;
}

function createLocation(Location) {
    return function(location) {
        return new Location(location[0].location.id, location[0].location.name)
    };
}

var Location = (function(){
    function Location(id, name) {
        this.id = id;
        this.name = name;
    }

    return Location;
})();

var Area = React.createClass({
    data: {},
    extract: function(media) {
        return media.filter(hasValues).map(createLocation(Location));
    },
    _onClick: function(e) {
        var id = React.findDOMNode(this.refs.location_id).value;
        this.props.findByLocationId(id);
    },
    render: function () {
        var self = this;
        var rows = this.extract(this.props.media).map(function(location) {
            return (
                <button className="btn btn-default" value={location.id} onClick={self._onClick} ref="location_id">{location.name}</button>
            );
        });
        return (
            <div className="">
                {rows}
            </div>
        )
    }
});

module.exports = Area;
