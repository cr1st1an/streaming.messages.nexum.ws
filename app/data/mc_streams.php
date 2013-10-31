<?php

class MC_Streams {

    protected $_name = 'Streams_';

    public function selectAll($ID_ACCOUNT) {
        $DB_Streams = new DB_Streams();

        $response = array();
        $streams_data = array();

        $id_account = (int) $ID_ACCOUNT;
        if (empty($response) && empty($id_account)) {
            $response['success'] = false;
            $response['message'] = "Required value ID_ACCOUNT is missing in MC_Streams->selectAll()";
            $response['err'] = 0;
        }

        if (empty($response)) {
            $key = $this->_name . $id_account;

            $cached_data = getCache()->get($key);
            if (!$cached_data) {
                $r_selectAll = $DB_Streams->selectAll($id_account);
                if ($r_selectAll['success']) {
                    getCache()->set($key, $r_selectAll['streams_data']);
                }
                $response = $r_selectAll;
            } else {
                $streams_data = $cached_data;
            }
        }

        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = "Here are the streams for the account with id '$id_account' [MC]";
            $response['streams_data'] = $streams_data;
        }

        return $response;
    }

    public function insert($DATA) {
        $DB_Streams = new DB_Streams();

        $response = array();
        $id_stream = null;
        $message = '';

        if (empty($response)) {
            $r_insert = $DB_Streams->insert($DATA);
            if (!$r_insert['success']) {
                $response = $r_insert;
            } else {
                $id_stream = $r_insert['id_stream'];
                $message = $r_insert['message'];
            }
        }

        if (empty($response)) {
            $key = $this->_name . $id_stream;
            getCache()->delete($key);

            $response['success'] = true;
            $response['message'] = $message . " [MC]";
            $response['id_stream'] = $id_stream;
        }

        return $response;
    }

}