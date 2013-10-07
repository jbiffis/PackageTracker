function(doc) {
    if (doc.events) {
        var length = doc.events.length;
        var _points = [];

        _points.push({
            "location": doc.loc_start,
            "position": doc.loc_start_position,
            "events": []
        });

        for (var i=0; i < doc.events.length; ++i) {
            var evnt = doc.events[i];
            if (_points[_points.length-1].location !== evnt.address) {
                _points.push({
                    "location": evnt.address,
                    "position": evnt.addr_position,
                    "date_in": evnt.date,
                    "date_out": evnt.date,
                    "events": []
                });
            }

            _points[_points.length-1].events.push(evnt);
            _points[_points.length-1].date_out = evnt.date;
        }

        log('points length' + _points.length);

        var length = _points.length;
        // Essentially we are creating a tree of all possible hops in a trip.
        // So for every point, make a new 'sub-trip' for that point to the end
        // With a seperate record for each possibility of hops.
        for (var i = 0, point; point = _points[i]; ++i) {

            for (var j = i+1; j < length; ++j) {

                var _value = [];
                for (var k = i; k <= j; k++) {
                    _value.push( _points[k]);

                }
                // Attach the associated trip
                _value.push({
                    'trip': doc
                });
log(_value);
                var _key = [_value[0].location, _value[_value.length-2].location];

                emit(_key, _value);
            }


        }

    }
}

