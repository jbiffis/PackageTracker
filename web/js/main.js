// Main.js

properties = {
    DB_URL: 'http://oberst.backfire.ca:5984/',
    DB_NAME: 'beerdb',
    DB_DESIGN: 'prices',

    DEFAULT_LOC_LNG: 45.399655,
    DEFAULT_LOC_LAT: -75.845361,
    DEFAULT_MAP_ZOOM: 14
};


require.config({
  paths: {
      // jquery
      jquery: '../lib/jquery',
      ko: '../lib/knockout-2.2.1'
  }
});

require([

  'app'

  ], function(App){

	App.initialize();

});
