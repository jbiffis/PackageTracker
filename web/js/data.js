//data.js
define([
    'jquery',
    'ko'
    ], function($,ko) {

        return {

            prices: ko.observableArray(),

            defaultParams: {
            },

            // -------------------------------------
            // Public Methods
            // -------------------------------------
            get: function(trackingNumber, shippingCompany) {



            },

            getWithParams: function(params) {



            },

            addPrice: function(obj) {

            },


            // -------------------------------------
            // Private Methods
            // -------------------------------------
            sendRequest: function(obj) {


                params = $.extend(true, {
                    // Default parameters
                    'method': 'POST'
                }, obj);


                return $.ajax(params);
            }


        };

    }
);