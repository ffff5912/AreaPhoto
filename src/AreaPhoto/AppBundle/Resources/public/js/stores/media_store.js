var MediaStore = (function() {
    function MediaStore(dispatcher) {
        this.url = '';
        this.token = '';
        dispatcher.on('fetch', this.find.bind(this));
        dispatcher.on('findByLocationId', this.findByLocationId.bind(this));
    }

    MediaStore.prototype.find = function(data) {
        var self = this;
        self.setLoading = data.setLoading;
        self.setLoading(true);
        $.ajax({
            url: self.url,
            type: 'GET',
            beforeSend: function(xhr) {xhr.setRequestHeader('X-CSRF-Token', self.token);},
            data: {
                lat: data.latLng.lat(),
                lng: data.latLng.lng(),
                distance: data.distance,
            },
            success: function(response) {
                data.callback(response);
            },
            error: function(data) {
            },
            complete: function(data) {
                self.setLoading(false);
            }
        });
    };

    MediaStore.prototype.findByLocationId = function(data) {
        var self = this;
        var url = self.url + '/recent';
        self.setLoading = data.setLoading;
        self.setLoading(true);
        $.ajax({
            url: url,
            type: 'GET',
            beforeSend: function(xhr) {xhr.setRequestHeader('X-CSRF-Token', self.token);},
            data: {id: data.id},
            success: function(response) {
                data.callback([response]);
            },
            error: function(data) {

            },
            complete: function(data) {
                self.setLoading(false);
            }
        });
    };

    return MediaStore;
})();

module.exports = MediaStore;
