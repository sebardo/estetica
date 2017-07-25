
jQuery.fn.extend({
    addMap: function () {


        //get this current element added map
        var $this = this,map=null;

        var options = {
            lat_field:   $this.data('lat-field'),
            lng_field:   $this.data('lng-field'),
            map_id:      $this.data('map-id'),
            token:       $this.data('token'),
            url:         $this.data('url'),
            imagePath:   $this.attr('data-imagepath'),
            heigth:      $this.data('heigth'),
            soon:        $this.data('soon'),
            marker: {
                color:$this.data('marker-color'),
                icon:$this.data('marker-icon')
            }
        };

        //set default imagePath
        L.Icon.Default.imagePath = options.imagePath;

        //hidden label field lat&lng
        $('label[for=' + options.lat_field + ']').addClass('hidden');
        $('label[for=' + options.lng_field + ']').addClass('hidden');

        //lat field is hidden
        var lat_field = $('#' + options.lat_field);
        lat_field.addClass('hidden');

        //lng field is hidden
        var lng_field = $('#' + options.lng_field);
        lng_field.addClass('hidden');

        //get lat field is show
        var lat_map = $this.find('#map-lat');
        lat_map.val(lat_field.val());

        //get lng field is show
        var lng_map = $this.find('#map-lng');
        lng_map.val(lng_field.val());

        //get dashboard map
        var map_container = $this.find('#container-map');
        map_container.empty();
        map_container.html('<div id="map-dashboard"></div>');
        $('#map-dashboard').css('min-height',options.heigth);

        //create leaflet object latLng
        var latLng = L.latLng(lat_field.val(), lng_field.val());

        var soon = parseInt(options.soon);

        map = L.map('map-dashboard').setView(latLng, soon);

        var myLayer = L.tileLayer(options.url, {
            mapId: options.map_id,
            token: options.token
        }).addTo(map);

        //create custom marker
        var kursaalMarker = L.VectorMarkers.icon({
            icon: options.marker.icon,
            markerColor: options.marker.color
        });

        var marker = L.marker(latLng, {draggable: true,icon:kursaalMarker}).addTo(map);

        var ondragend = function () {
            lat_field.val(marker.getLatLng().lat);
            lat_map.val(marker.getLatLng().lat);

            lng_field.val(marker.getLatLng().lng);
            lng_map.val(marker.getLatLng().lng);
        };

        var move_marker=function(){
            try{
                latLng = L.latLng(lat_map.val(), lng_map.val());
                marker.setLatLng(latLng);
                map.setView(latLng,map.getZoom());

                lat_field.val(latLng.lat);
                lng_field.val(latLng.lng);

            }
            catch(err) {
               console.log('Invalid LatLng object');
            }


        };

        //event for update map
        marker.on('dragend', ondragend);

        lat_map.on('keyup',move_marker);

        lng_map.on('keyup',move_marker);

        lat_map.on('change',move_marker);

        lng_map.on('change',move_marker);

        ondragend();
    }
});
