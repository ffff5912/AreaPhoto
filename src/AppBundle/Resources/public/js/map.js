var Map = (function() {
    function Map(action, media_store) {
        this.action = action;
        this.media_store = media_store;
        this.media = [];
        this.loading = false;
        this.distance = 100;
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
        data.distance = this.distance;
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

module.exports = Map;
