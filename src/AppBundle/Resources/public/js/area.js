var React = require('react');

function getArea() {
    return function (location) {
        location.reduce(function(area, value) {
            area[value.id] = value.location.name;
        }).filter(function(id, index, self) {
            return self.indexOf(id) === index;
        });
    }
}

var Area = React.createClass({
    extract: function () {
        return this.props.media.filter(function(value) {
            return value.length > 0;
        }).map(getArea);
    },
    render: function () {

    }
});

module.exports = Area;
