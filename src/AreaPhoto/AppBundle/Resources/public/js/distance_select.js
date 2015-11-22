var React = require('react');

var DistanceSelect = React.createClass({
    getDefaultProps: function() {
        return {
            location_distance: [100, 200, 300, 400, 500]
        };
    },
    getInitialState: function() {
        return {
            select_value: 100
        };
    },
    _onChange: function(e) {
        this.setState({select_value: e.target.value});
        this.props.map.distance = e.target.value;
    },
    render: function () {
        var options = this.props.location_distance.map(function(distance) {
            return <option value={distance} key={distance}>{distance}</option>;
        });
        return (
            <select value={this.state.select_value} onChange={this._onChange}>
                {options}
            </select>
        );
    }
});

module.exports = DistanceSelect;
