var React = require('react');
var Media = require('./media.js');
var EventEmitter = require('./event_emitter.js');
var MediaStore = require('./stores/media_store.js');
var PopularAction = require('./actions/popular_action.js');

var event_emitter = new EventEmitter();
var media_store = new MediaStore(event_emitter);
var action = new PopularAction(event_emitter);
media_store.url = media_store_popular_url;

var Popular = React.createClass({
    getInitialState: function() {
        return {
            media: []
        };
    },
    setMedia: function(media) {
        this.setState({
            media: media
        });
    },
    _onClick: function() {
        action.findByPopular(this.setMedia);
    },
    render: function() {
        return (
            <li>
                <span onClick={this._onClick}>Popular</span>
            </li>
        );
    }
});

React.render(<Popular />, document.getElementById('nav-area'));
