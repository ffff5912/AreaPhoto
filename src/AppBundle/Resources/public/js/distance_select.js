var React = require('react');

var location_distance = [100, 200, 300, 400, 500];

var DistanceSelect = React.createClass({
    render: function () {
        var options = location_distance.map(function(distance) {
            return <option value={distance} key={distance}>{distance}</option>;
        });
        return (
            <select>
                {options}
            </select>
        );
    }
});

module.exports = DistanceSelect;
