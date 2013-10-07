<?php
// UPS Shipping Connection

$ups = new UPS();
$result = $ups->getTrackingInfo('1Z12345E6892410845');
print_r($result);

class UPS {
    private $access = "BCB197ED7E3DEB32";
    private $userid = "thefog";
    private $passwd = "Debrief123";

    //  Get the shipment info from the provider
    //  Return the standard tracking object
    public function getTrackingInfo($trackingNumber) {
        $xml = '<?xml version="1.0"?>
                  <AccessRequest xml:lang="en-US">
                    <AccessLicenseNumber>'.$this->access.'</AccessLicenseNumber>
                    <UserId>'.$this->userid.'</UserId>
                    <Password>'.$this->passwd.'</Password>
                  </AccessRequest>
                  <?xml version="1.0"?>
                    <TrackRequest xml:lang="en-US">
                      <Request>
                        <TransactionReference>
                          <XpciVersion>1.0</XpciVersion>
                        </TransactionReference>
                        <RequestAction>Track</RequestAction>
                        <RequestOption>03</RequestOption>
                      </Request>
                      <TrackingNumber>'.$trackingNumber.'</TrackingNumber>
                   </TrackRequest>';
        $path = "https://wwwcie.ups.com/ups.app/xml/Track";
        //$path = "https://onlinetools.ups.com/ups.app/xml/Track";


        $ch = curl_init ();
        $ch1=curl_setopt ($ch, CURLOPT_URL, $path);
        $ch2=curl_setopt ($ch, CURLOPT_POST, 1);
        $ch3=curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        $ch4=curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        $ch5=curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $ch5 =curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close ($ch);

        $result = new SimpleXMLElement($result);

        $dest_city          = (string) $result->Shipment->ShipTo->Address->City;
        $dest_state_prov    = (string) $result->Shipment->ShipTo->Address->StateProvinceCode;
        $dest_country       = (string) $result->Shipment->ShipTo->Address->CountryCode;
        $destination        = $dest_city . ', ' . $dest_state_prov . ', ' .  $dest_country;

        $outArray = array(
            "trackingCode"  => $trackingNumber,
            "carrier"       => "UPS",
            "loc_start"     => "",
            "loc_end"       => $destination,
            //"pkg_weight"    => "0.112 lb",
            "status"        => "Delivered",
            "date_added"    =>  "2013-03-15T00:23-05:00",
            //"ip_added"      => "96.24.185.224"
          );
        print_r($outArray);

        return $result;
    }
}
?>