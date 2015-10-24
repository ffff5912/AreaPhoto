var EventEmitter = require('./event_emitter.js');
var MapAction = require('./actions/map_action.js');
var MediaStore = require('./stores/media_store.js');

var event_emitter = new EventEmitter();
var media_store = new MediaStore(event_emitter);
var action = new MapAction(event_emitter);

var Map = (function() {
    function Map(action, media_store) {
        this.action = action;
        this.media_store = media_store;
        this.media = [];
        this.loading = false;
    }

    Map.prototype.onLoad = function() {
        google.maps.event.addDomListener(window, 'load', this.initialize.bind(this));
    };

    Map.prototype.initialize = function() {
        var canvas = document.getElementById('map-canvas') ;
        var latlng = new google.maps.LatLng( 35.792621 , 139.806513 );
        var mapOptions = {
            zoom: 15 ,
            center: latlng ,
        };
        this.map = new google.maps.Map(canvas, mapOptions);
        this.map.addListener('click', this.onClick.bind(this));
    };

    Map.prototype.onClick = function(data) {
        data.callback = this.setMedia;
        data.setLoading = this.setLoading;
        this.action.fetch(data);
    };

    Map.prototype.setMedia = function(data) {
        this.media = data;
    };

    Map.prototype.setLoading = function (data) {
        this.loading = data;
    };

    return Map;
})();

module.exports = new Map(action, media_store);
