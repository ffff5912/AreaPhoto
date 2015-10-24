var Map = require('./map.js');
var React = require('react');
var Media = require('./media.js');
var Area = require('./area.js');
var Loading = require('./loading.js');
var EventEmitter = require('./event_emitter.js');
var MapAction = require('./actions/map_action.js');
var MediaStore = require('./stores/media_store.js');

var event_emitter = new EventEmitter();
var media_store = new MediaStore(event_emitter);
var action = new MapAction(event_emitter);
var map = new Map(action, media_store);

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
    findByLocationId: function(id) {
        action.findByLocationId(id, this.setMedia, this.setLoading);
    },
    render: function() {
        return (
            <div>
                <Loading loading={this.state.loading} />
                <Area media={this.state.media} findByLocationId={this.findByLocationId}/>
                <Media media={this.state.media} />
            </div>
        );
    }
});

React.render(<Main />, document.getElementById('photo_box'));
