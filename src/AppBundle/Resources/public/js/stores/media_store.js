var MediaStore = (function() {
    function MediaStore(dispatcher) {
        this.url = '';
        this.token = '';
        dispatcher.on('fetch', this.find.bind(this));
    }

    MediaStore.prototype.find = function(data) {
        var self = this;
        data.query = {
            "appbundle_location[lat]": data.latLng.lat(),
            "appbundle_location[lng]": data.latLng.lng(),
        };
        $.ajax({
            url: self.url,
            type: 'GET',
            beforeSend: function(xhr) {xhr.setRequestHeader('X-CSRF-Token', self.token);},
            data: data.query,
            success: function(response) {
                data.callback(response);
            }
        });
    };

    return MediaStore;
})();

module.exports = MediaStore;
