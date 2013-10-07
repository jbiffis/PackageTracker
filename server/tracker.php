<?php
// main tracker file
require_once('inc\main.inc.php');
require_once('fetchData.php');
require_once('dataOps.php');


// set the json output header
header('Content-type: application/json');


$dataOps = new DataOps();

// get the incoming tracking number, shipping company
$trackingNumber = isset($_REQUEST['t']) ? $_REQUEST['t'] : null;
$shipper = isset($_REQUEST['s']) ? $_REQUEST['s'] : null;

if (!$trackingNumber || !$shipper) {
    die('Need tracking Number');
}

$result = fetchData($trackingNumber, $shipper);
//print_r($result);

// Add location details to the object now that we have it
addGeocodeData($result);
$dataOps->saveUpdate($result);
// Get the package details

// if something useful comes back, save/update the package in db

// get all similar trips from the db

// do maths

// send back results

$outData = array('packageDetails' => $result);

$jsonOut = json_encode($outData);


echo $jsonOut;


// This function attaches geocoded long & lat for the start and end positions as well as each event.
function addGeocodeData(&$trackingObject) {

    global $mapFuncs;

    $trackingObject['loc_start_position'] = $mapFuncs->geocodeAddress($trackingObject['loc_start']);
    $trackingObject['loc_end_position'] = $mapFuncs->geocodeAddress($trackingObject['loc_end']);

    foreach ($trackingObject['events'] as &$event) {
        $event['addr_position'] = $mapFuncs->geocodeAddress($event['address']);
    }

    return true;
}

?>