<?php

class DB_Streams {

    protected $_name = 'streams';

    public function selectAll($ID_ACCOUNT) {
        $response = array();
        $streams_data = array();

        $id_account = (int) $ID_ACCOUNT;
        if (empty($response) && empty($id_account)) {
            $response['success'] = false;
            $response['message'] = "Required value ID_ACCOUNT is missing in DB_Streams->selectAll()";
            $response['err'] = 0;
        }

        if (empty($response)) {
            $select_data = array(
                'id_account' => $id_account
            );
            $streams_data = getDatabase()->all(
                    'SELECT * FROM ' . $this->_name . ' WHERE id_account=:id_account', $select_data
            );
        }

        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = "Here are the streams for the account with id '$id_account' [DB]";
            $response['streams_data'] = $streams_data;
        }

        return $response;
    }

    public function insert($DATA) {
        getDatabase()->dbh = null;
        
        $response = array();
        $id_stream = null;
        $message = "";

        $id_account = (int) $DATA['id_account'];
        if (empty($response) && empty($id_account)) {
            $response['success'] = false;
            $response['message'] = "Required value ID_ACCOUNT is missing in DB_Streams->insert()";
            $response['err'] = 0;
        }

        $created = date("Y-m-d H:i:s");
        $updated = date("Y-m-d H:i:s");

        if (empty($response)) {
            $select_data = array(
                'id_account' => $id_account
            );
            $stream_data = getDatabase()->one('SELECT * FROM ' . $this->_name . ' WHERE id_account=:id_account', $select_data);

            if (empty($stream_data)) {
                $insert_data = array(
                    'id_account' => $id_account,
                    'created' => $created
                );
                $id_stream = getDatabase()->execute(
                        'INSERT INTO ' . $this->_name . '(id_account, created) VALUES(:id_account, :created)', $insert_data
                );
                $message = "A new stream with id '$id_stream' has been inserted [DB]";
            } else {
                $id_stream = $stream_data['id_stream'];
                $update_data = array(
                    'id_stream' => $id_stream,
                    'id_account' => $id_account,
                    'updated' => $updated
                );
                getDatabase()->execute('UPDATE ' . $this->_name . ' SET id_account=:id_account, updated=:updated WHERE id_stream=:id_stream', $update_data);
                $message = "The stream with id '$id_stream' has been updated [DB]";
            }

            $response['success'] = true;
            $response['message'] = $message;
            $response['id_stream'] = $id_stream;
        }

        return $response;
    }

}