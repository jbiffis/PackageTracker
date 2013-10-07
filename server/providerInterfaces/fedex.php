<?php
// fedex.php
require_once('libs/providers/FedEx/library/fedex-common.php5');

class FedEx {

    //The WSDL is not included with the sample code.
    //Please include and reference in $path_to_wsdl variable.
    private $path_to_wsdl = "libs/providers/FedEx/wsdl/TrackService_v6.wsdl";
    private $eventTypes = array("DL" => 'DELIVERED', "IT" => "IN TRANSIT", "DP"=> "DEPARTED");

    public function getTrackingInfo($trackingNumber) {

        $request = $this->__prepareRequest($trackingNumber);

        ini_set("soap.wsdl_cache_enabled", "0");

        // Get the xml of the response
        $this->data = $this->__sendRequest($request);

        //print_r($this->data);

        $outArray = array(
            "trackingCode"  => $trackingNumber,
            "carrier"       => "FedEx",
            "loc_start"     => $this->__getAddress($this->data->TrackDetails->ShipperAddress),
            "loc_end"       => $this->__getAddress($this->data->TrackDetails->DestinationAddress),
            //"pkg_weight"    => "0.112 lb",
            "status"        => $this->__getDeliveryStatus(),
            "date_added"    =>  "2013-03-15T00:23-05:00",
            //"ip_added"      => "96.24.185.224"
            "events"        => $this->__getEvents()
            );

        return $outArray;

    }

    /* ------------------------------------
    *   PRIVATE FUNCTIONS
    *  ------------------------------------*/
    private function __prepareRequest($num) {

        // these properties are in the library file
        $request['WebAuthenticationDetail'] = array(
            'UserCredential' =>array(
                'Key' => getProperty('key'),
                'Password' => getProperty('password')
            )
        );
        $request['ClientDetail'] = array(
            'AccountNumber' => getProperty('shipaccount'),
            'MeterNumber' => getProperty('meter')
        );
        $request['TransactionDetail'] = array('CustomerTransactionId' => '*** Track Request v6 using PHP ***');
        $request['Version'] = array(
            'ServiceId' => 'trck',
            'Major' => '6',
            'Intermediate' => '0',
            'Minor' => '0'
        );
        $request['PackageIdentifier'] = array(
            'Value' => $num, // Replace 'XXX' with a valid tracking identifier
            'Type' => 'TRACKING_NUMBER_OR_DOORTAG');
        $request['IncludeDetailedScans'] = true;

        return $request;

    }

    private function __sendRequest($request) {

        $client = new SoapClient($this->path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information

        try
        {
            if(setEndpoint('changeEndpoint'))
            {
                $newLocation = $client->__setLocation(setEndpoint('endpoint'));
            }

            $response = $client ->track($request);

            if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR')
            {

                return $response;

                //printSuccess($client, $response);
            }
            else
            {
                printError($client, $response);
            }

            writeToLog($client);    // Write to log file

        } catch (SoapFault $exception) {
            printFault($exception, $client);
        }
    }

    // Address will be an array of various things, make a string out of it.
    private function __getAddress($addr, $withZip = false) {


        if (!isset($addr->City) || !isset($addr->StateOrProvinceCode) ) {
            return '';
        }


        $addrAry = array();
        if (isset($addr->City)) {
            $addrAry[] = $addr->City;
            isset($addr->StateOrProvinceCode) ? $addrAry[] = $addr->StateOrProvinceCode : null;
        } else {
            isset($addr->PostalCode) ? $addrAry[] = $addr->PostalCode : null;
        }
        isset($addr->CountryCode) ? $addrAry[] = $addr->CountryCode : null;

        $address = strToUpper(implode(', ', $addrAry));

        return $address;
    }

    // returns the delivery status
    private function __getDeliveryStatus() {
        return $this->data->TrackDetails->StatusDescription;
    }

    private function __getEvents() {

        $events = array();

        // for each event
        foreach (array_reverse($this->data->TrackDetails->Events) as $event) {

            $address = $this->__getAddress($event->Address, true);

            if ($address == '') {
                continue;
            }

            $newEvent = array(
                "date" => $event->Timestamp,
                "note" => $event->EventDescription,
                "eventType" => isset($this->eventTypes[$event->EventType]) ? $this->eventTypes[$event->EventType] : '',
                "moreDesc" => isset($event->StatusExceptionDescription) ? $event->StatusExceptionDescription : '',
                'address' => strToUpper($address)
            );

            $events[] = $newEvent;
        }

        return $events;

    }

}
?>