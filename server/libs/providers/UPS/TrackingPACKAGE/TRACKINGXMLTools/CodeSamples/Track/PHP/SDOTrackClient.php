<?php

      //Configuration
      $access = " Add License Key Here";
      $userid = " Add User Id Here";
      $passwd = " Add Password Here";

      $accessSchemaFile = " Add AccessRequest Schema File";
      $requestSchemaFile = " Add TrackRequest Schema File";
      $responseSchemaFile = " Add TrackResponse Schema File";

      $endpointurl = ' Add URL Here';
      $outputFileName = "XOLTResult.xml";


      try
      {
         //create AccessRequest data object
         $das = SDO_DAS_XML::create("$accessSchemaFile");
    	 $doc = $das->createDocument();
         $root = $doc->getRootDataObject();
         $root->AccessLicenseNumber=$access;
         $root->UserId=$userid;
         $root->Password=$passwd;
         $security = $das->saveString($doc);

         //create TrackRequest data oject
         $das = SDO_DAS_XML::create("$requestSchemaFile");
         $requestDO = $das->createDataObject('','Request');
         $requestDO->RequestAction='Track';
         $requestDO->RequestOption='activity';

         $doc = $das->createDocument();
         $root = $doc->getRootDataObject();
         $root->Request = $requestDO;
         $root->TrackingNumber = '';
         $request = $das->saveString($doc);

         //create Post request
         $form = array
         (
             'http' => array
             (
                 'method' => 'POST',
                 'header' => 'Content-type: application/x-www-form-urlencoded',
                 'content' => "$security$request"
             )
         );

         //print form request
         print_r($form);


         $request = stream_context_create($form);
         $browser = fopen($endpointurl , 'rb' , false , $request);
         if(!$browser)
         {
             throw new Exception("Connection failed.");
         }

         //get response
         $response = stream_get_contents($browser);
         fclose($browser);

         if($response == false)
         {
            throw new Exception("Bad data.");
         }
         else
         {
            //save request and response to file
  	    $fw = fopen($outputFileName,'w');
            fwrite($fw , "Response: \n" . $response . "\n");
            fclose($fw);

            //get response status
            $resp = new SimpleXMLElement($response);
            echo $resp->Response->ResponseStatusDescription . "\n";
         }
      }
      catch(SDOException $sdo)
      {
      	 echo $sdo;
      }
      catch(Exception $ex)
      {
      	 echo $ex;
      }

?>

