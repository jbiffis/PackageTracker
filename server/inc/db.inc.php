<?php

require_once('libs\sag-0.8.0\src\sag.php');

class db {

    public function __construct() {
        $this->sag = new Sag('oberst.backfire.ca');
        //$sag->login('admin', 'password');
        //$sag->createDatabase('test_from_php');
        $this->sag->setDatabase('packages');
    }

    public function doInsert($object, $id = null) {

        if (!$id) {
            $result = $this->sag->post($object);

            if ($result) {
                return true;
            }
        }

    }
}

?>