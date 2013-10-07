<?php
// Data Operations

class DataOps {

    public $data;

    public function saveUpdate($object) {

        $this->db = new db();

        $result = $this->db->doInsert($object);


        if ($result) {
            $this->data['trackingObject'] = $object;
        }
    }
}
?>