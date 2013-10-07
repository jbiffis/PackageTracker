// app.js
define([
    'jquery',
    'data',
    'state',
    'map'
	], function($, data, State, Map) {

      var app = {

        initialize: function(){

          var self = this;

          this.viewModels = {
            state: new State(),
            map: new Map()
          };

          $("document").ready(function() {
            //self.mapsInit();

            // data is saved in the data object.
            //data.submitTrackingNumber('', 'FedEx').done(self.loadDataOnPage);
            $.getJSON('sample.json').done(function(response) {
              self.loadDataOnPage(response);
            });
          });

        },

        loadDataOnPage: function(response) {
          var self = this;

          self.viewModels.map.plotTripOnMap(response.packageDetails);

        }

      };

      return app;

    }
);