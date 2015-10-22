var map = require('./map.js');
var React = require('react');
var Media = require('./media.js');
var Area = require('./area.js');

map.media_store.url = media_store_url;
map.media_store.token = token;
map.onLoad();

var Main = React.createClass({
    getInitialState: function() {
        return {
            media: []
        };
    },
    componentDidMount: function() {
        map.setMedia = this.setMedia;
        this.props.map = map;
        this.setMedia(map.media);
    },
    setMedia: function(media) {
        this.setState({
            media: media
        });
    },
    render: function() {
        return (
            <div>
                <Area media={this.state.media} />
                <Media media={this.state.media} />
            </div>
        );
    }
});

React.render(<Main />, document.getElementById('photo_box'));
