<?php

// Copyright 2009, FedEx Corporation. All rights reserved.
// Version 6.0.0

require_once('library/fedex-common.php5');


//The WSDL is not included with the sample code.
//Please include and reference in $path_to_wsdl variable.
$path_to_wsdl = "wsdl/TrackService_v6.wsdl";

ini_set("soap.wsdl_cache_enabled", "0");

$client = new SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information

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
	'Value' => getProperty('trackingnumber'), // Replace 'XXX' with a valid tracking identifier
	'Type' => 'TRACKING_NUMBER_OR_DOORTAG');
$request['IncludeDetailedScans'] = true;

try
{
	if(setEndpoint('changeEndpoint'))
	{
		$newLocation = $client->__setLocation(setEndpoint('endpoint'));
	}

	$response = $client ->track($request);

    if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR')
    {

    	echo '<table border="1">';
    	echo '<tr><th>Tracking Details</th><th>&nbsp;</th></tr>';
        trackDetails($response->TrackDetails, '');
		echo '</table>';

        printSuccess($client, $response);
    }
    else
    {
        printError($client, $response);
    }

    writeToLog($client);    // Write to log file

} catch (SoapFault $exception) {
    printFault($exception, $client);
}

?>