// priceTable.js
define([
    'jquery',
    'ko',
    'data'
    ], function($, ko, data) {

        return function PriceTable(collaborators) {

            var self = this;


            // subscribe to any changes to the data
            data.prices.subscribe(function() {
                // update the table data.
            });


            // Handle the click events
            this.selectBeerType = function() {

            };



            // ----------------------------------------
            // Private Functions
            // ----------------------------------------

            // make an array of the viewmodels for each row
            // add all of those items to the table.





        };

    }
);