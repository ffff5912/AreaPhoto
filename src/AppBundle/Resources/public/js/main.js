var map = require('./map.js');
var React = require('react');
var Media = require('./media.js');
var Area = require('./area.js');
var Loading = require('./loading.js');

map.media_store.url = media_store_url;
map.media_store.token = token;
map.onLoad();

var Main = React.createClass({
    getInitialState: function() {
        return {
            media: [],
            loading: false
        };
    },
    componentDidMount: function() {
        map.setMedia = this.setMedia;
        map.setLoading = this.setLoading;
        this.props.map = map;
        this.setMedia(map.media);
    },
    setMedia: function(media) {
        this.setState({
            media: media
        });
    },
    setLoading: function(loading) {
        this.setState({
            loading: loading
        });
    },
    render: function() {
        return (
            <div>
                <Loading loading={this.state.loading} />
                <Area media={this.state.media} />
                <Media media={this.state.media} />
            </div>
        );
    }
});

React.render(<Main />, document.getElementById('photo_box'));
