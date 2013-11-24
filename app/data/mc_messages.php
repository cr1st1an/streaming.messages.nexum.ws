<?php

class MC_Messages {

    protected $_name = 'Messages_';

    public function insert($DATA) {
        $DB_Messages = new DB_Messages();

        $response = array();
        $id_message = null;

        $id_account = (int) $DATA['id_account'];
        if (empty($response) && empty($id_account)) {
            $response['success'] = false;
            $response['message'] = "Required value ID_ACCOUNT is missing in MC_Messages->insert()";
            $response['err'] = 0;
        }

        if (empty($response)) {
            $r_insert = $DB_Messages->insert($DATA);
            if (!$r_insert['success']) {
                $response = $r_insert;
            } else {
                $id_message = $r_insert['id_message'];
            }
        }

        if (empty($response)) {
            $key = $this->_name . $id_account;
            getCache()->delete($key);

            $response['success'] = true;
            $response['message'] = "A new message with id '$id_message' has been inserted [MC]";
            $response['id_message'] = $id_message;
        }

        return $response;
    }

}