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
    extract: function (media) {
        return media.filter(hasValues).map(createLocation(Location));
    },
    render: function () {
        var rows = this.extract(this.props.media).map(function(location) {
            return (
                <p>{location.name}</p>
            )
        });
        return (
            <div>
                {rows}
            </div>
        )
    }
});

module.exports = Area;
