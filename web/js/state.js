// state.js
define([
    'jquery',
    'ko',
    'data'
    ], function($, ko, data) {

        return function State() {

            var self = this;

            this.location = ko.observable();

            // -----------------------------------
            // Handle the click events
            // -----------------------------------

            // User selects a specific kind of beer
            this.selectSpecificBeer = function(beerName) {


            };

            // When the user clicks on one of the predefined buttons (Aylmer, Gatineau, Hull)
            // or moves the map is relocated and requres more data
            this.setLocation = function(location) {
                // send a query to the data layer
                // each view model should be listening for changes on that observable.
            };

            // Filter out by beer type (light or reg)
            this.selectBeerType = function(type) {

            };

            // Get by store
            this.selectStore = function(storeName) {

            };

        };

    }
);