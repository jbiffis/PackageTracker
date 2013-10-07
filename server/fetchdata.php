<?php
// fetch the tracking info
function fetchData($trackingNumber, $shippingCompany) {

    $dataSource = null;

    switch ($shippingCompany) {
        case 'FedEx':
            require_once('providerInterfaces/fedex.php');
            $dataSource = new FedEx();
            break;

    }

    return $dataSource->getTrackingInfo($trackingNumber);



}

?>