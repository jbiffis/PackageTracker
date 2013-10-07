<?php
// log trip to database

include_once('inc\main.inc.php');

$point1 = array(
    "location" => "San Leandro, CA, United States",
    "first_date"    => "2013-03-08T00:23-05:00",
    "last_date"     => "2013-03-08T10:23-05:00",
    "activity" => array(array(
                "date" => "2013-03-08T00:23-05:00",
                "note" => "Package processed by UPS Mail Innovations origin facility"
            ), array(
                "date" => "2013-03-08T10:23-05:00",
                "note" => "Package transferred to destination UPS Mail Innovations facility"
            )
        )
);

$point2 = array(
    "location" => "Urbancrest, OH, United States",
    "first_date"    => "2013-03-09T00:23-05:00",
    "last_date"     => "2013-03-09T10:23-05:00",
    "activity" => array(array(
                "date" => "2013-03-09T00:23-05:00",
                "note" => "Postage Paid/Ready for destination post office entry"
            )
    )
);

$point3 = array(
    "location" => "SYRACUSE, NY, United States",
    "first_date"    => "2013-03-11T23:10-05:00",
    "last_date"     => "2013-03-12T04:23-05:00",
    "activity" => array(array(
                "date" => "2013-03-11T23:10-05:00",
                "note" => "Electronic Shipment Information Received for Package by Post Office"
            ), array(
                "date" => "2013-03-12T04:23-05:00",
                "note" => "Shipment Acceptance at Post Office"
            )
        )
);

$point4 = array(
    "location" => "OGDENSBURG, NY, United States",
    "first_date"    => "2013-03-13T10:10-05:00",
    "last_date"     => "2013-03-13T16:16-05:00",
    "activity" => array(array(
                "date" => "2013-03-11T23:10-05:00",
                "note" => "Received by the local post office"
            ), array(
                "date" => "2013-03-12T04:23-05:00",
                "note" => "Package delivered by local post office"
            )
        )
);

$doc = array("trackingCode"     => "9102999998767961107126",
                "carrier"       => "UPS",
                "loc_start"     => "San Leandro, CA, United States",
                "loc_end"       => "OGDENSBURG, NY, United States",
                "pkg_weight"    => "0.112 lb",
                "status"        => "Delivered",
                "date_added"    =>  "2013-03-15T00:23-05:00",
                "ip_added"      => "96.24.185.224",
                "points"    => array($point1, $point2, $point3, $point4)
            );

$result = $db->doInsert($doc);

if ($result) {
    echo "added ok";
}

?>