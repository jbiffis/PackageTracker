<?php
// Maps functions

class MapFuncs {

    private $GOOGLE_MAPS_API_BASE_URL = 'http://maps.googleapis.com/maps/api/';
    private $GOOGLE_MAPS_GEOCODE_URL = 'geocode/';

    // takes an address string and converts to a lat long array
    public function geocodeAddress($address) {

        $curlURL = $this->GOOGLE_MAPS_API_BASE_URL .$this->GOOGLE_MAPS_GEOCODE_URL. 'xml?sensor=false&address=' . urlencode($address);

        $ch = curl_init ();
        $ch1=curl_setopt ($ch, CURLOPT_URL, $curlURL);
        $ch5=curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $ch5 =curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close ($ch);

        $result = new SimpleXMLElement($result);

        $lat = (string) $result->result->geometry->location->lat;
        $lng = (string) $result->result->geometry->location->lng;

        return array('lat'=>$lat, 'lng'=>$lng);

    }
}



?>