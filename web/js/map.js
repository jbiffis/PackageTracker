// map.js
define([
    'jquery'
    ], function($) {

      return function Map() {

        this.mapContainerSelector= '#map-container';
        this.directionsService = new google.maps.DirectionsService();

        /******************************************
        *  Public Functions
        ******************************************/

        this.plotTripOnMap = function(trip) {
          var self = this;


          self.renderMap();

          // given start and end points, determine the center of the map and the zoom level
          self.__setBounds(trip.loc_start_position, trip.loc_end_position);

          self.tripPoints = self.__getPointsFromTrip(trip);

          self.plotTripSoFar(self.tripPoints);

        };

        // Given an array of points, plot the trip so far
        this.plotTripSoFar = function(points) {
          var self = this;

          $.each(points, function(i, point) {
            var _html = "<b>" + point.loc_name + "</b>";
            $.each(point.events, function(i, evt) {
              _html += "<br>" + evt.date + " - " + evt.note;
            });

            // Show the point on the map.
            point.mapPoint = self.__plotPoint([point.lat, point.lng], _html);

          });

          self.trackSoFar = self.__drawTrackSoFar(points);

        };

        // Draws the map
        this.renderMap = function() {
          var self=this;

          var mapOptions = {
            center: new google.maps.LatLng(properties.DEFAULT_LOC_LNG, properties.DEFAULT_LOC_LAT),
            zoom: properties.DEFAULT_MAP_ZOOM,
            mapTypeId: google.maps.MapTypeId.TERRAIN
          };
          this.map = new google.maps.Map($(self.mapContainerSelector)[0],
              mapOptions);
        };


        /******************************************
        *  Private Functions
        ******************************************/

        //  The center will be simply middle of both lats and both longs
        this.__setBounds = function(start, end) {
          var bounds = new google.maps.LatLngBounds();

          bounds.extend(new google.maps.LatLng(start.lat, start.lng));
          bounds.extend(new google.maps.LatLng(end.lat, end.lng));

          this.map.fitBounds(bounds);

          return bounds;
        };

        // Given a trip, put all of the points (start, middle, end) into an array for further use
        this.__getPointsFromTrip = function(trip) {
          var tripPoints = [];

          tripPoints.push({
            'lat': trip.loc_start_position.lat,
            'lng': trip.loc_start_position.lng,
            'loc_name': trip.loc_start,
            'desription': 'Delivery Origin',
            'events': []
          });

          $.each(trip.events, function(i, evt){
            if (tripPoints[tripPoints.length-1].loc_name !== evt.address) {
              // new point on the trip
              tripPoints.push({
                'lat': evt.addr_position.lat,
                'lng': evt.addr_position.lng,
                'loc_name': evt.address,
                'events': []
              });
            }

            tripPoints[tripPoints.length-1].events.push({
              'date': evt.date,
              'note': evt.note
            });
          });

          tripPoints.push({
            'lat': trip.loc_end_position.lat,
            'lng': trip.loc_end_position.lng,
            'loc_name': trip.loc_end,
            'desription': 'Delivery Destination',
            'events': []
          });

          return tripPoints;
        };

        // Plots a single point with HTML for pop up
        this.__plotPoint = function(point, html) {
          var self = this;

          var infowindow = new google.maps.InfoWindow({content: html});

          var _marker = new google.maps.Marker({
              'position': new google.maps.LatLng(point[0], point[1]),
              'clickable': true,
              'map': this.map
          });

          google.maps.event.addListener(_marker, 'click', function() {
            infowindow.open(self.map,_marker);
          });

          return

        };

        // make a point
        this.__makePoint = function(position, html) {


        };


        // Show the track so far
        this.__drawTrackSoFar = function(points) {

          var self=this;

          // Set up the directions renderer and attach to the map.
          var directionsDisplay = new google.maps.DirectionsRenderer();
          directionsDisplay.setMap(self.map);

          // Splice out the first and last points, the rest become waypoints.
          var _start = points.splice(0,1)[0];
          var _end = points.splice(points.length-1, 1)[0];

          $.each(points, function(i, point) {
            points[i] = {
              'location': new google.maps.LatLng(point.lat, point.lng),
              'stopover': false
            };
          });

          var _request = {
            origin: new google.maps.LatLng(_start.lat, _start.lng),
            destination: new google.maps.LatLng(_end.lat, _end.lng),
            travelMode: google.maps.TravelMode.DRIVING,
            waypoints: points,
            optimizeWaypoints: false,
            provideRouteAlternatives: false,
            avoidHighways: false,
            avoidTolls: false
          };

          self.directionsService.route(_request, function(result, status) {
            if (status == google.maps.DirectionsStatus.OK) {
              directionsDisplay.setDirections(result);
            } else {
              console.log('issue loading your map - could not draw track');
            }
          });

        };

    };

});
