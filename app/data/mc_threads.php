<?php

class MC_Threads {

    protected $_name = 'Threads_';

    public function insert($DATA) {
        $DB_Threads = new DB_Threads();

        $response = array();
        $id_thread = null;

        $id_account = (int) $DATA['id_account'];
        if (empty($response) && empty($id_account)) {
            $response['success'] = false;
            $response['message'] = "Required value ID_ACCOUNT is missing in MC_Threads->insert()";
            $response['err'] = 0;
        }

        if (empty($response)) {
            $r_insert = $DB_Threads->insert($DATA);
            if (!$r_insert['success']) {
                $response = $r_insert;
            } else {
                $id_thread = $r_insert['id_session'];
            }
        }

        if (empty($response)) {
            $key = $this->_name . $id_account;
            getCache()->delete($key);
            
            $response['success'] = true;
            $response['message'] = "A new thread with id '$id_thread' has been inserted [MC]";
            $response['id_thread'] = $id_thread;
        }

        return $response;
    }

}