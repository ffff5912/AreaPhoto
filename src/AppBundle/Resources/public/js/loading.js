var React = require('react');

var Loading = React.createClass({
    render: function () {
        if (this.props.loading) {
            return (
                <div className="loading">
                    <img className="loadingMsg" src="/AreaPhoto/web/bundles/app/images/gif-load.gif" />
                </div>
            );
        }
        return (
            <div></div>
        );
    }
});

module.exports = Loading;
