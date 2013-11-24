<?php

class DB_Messages {

    protected $_name = 'messages';

    public function insert($DATA) {
        $response = array();
        $id_message = null;

        $id_account = (int) $DATA['id_account'];
        if (empty($response) && empty($id_account)) {
            $response['success'] = false;
            $response['message'] = "Required value ID_ACCOUNT is missing in DB_Messages->insert()";
            $response['err'] = 0;
        }

        if (empty($response)) {
            $insert_data = array(
                'id_account' => $id_account,
                'created' => $DATA['created'],
                'identifier' => $DATA['identifier'],
                'sender_id' => $DATA['sender_id'],
                'recipient_id' => $DATA['recipient_id'],
                'text' => $DATA['text']
            );
            
            getDatabase()->dbh = null;
            getDatabase()->execute('SET NAMES utf8mb4 COLLATE utf8mb4_bin;');
            $id_message = getDatabase()->execute(
                    'INSERT INTO ' . $this->_name . '(id_account, created, identifier, sender_id, recipient_id, text) VALUES(:id_account, :created, :identifier, :sender_id, :recipient_id, :text)', $insert_data
            );

            $response['success'] = true;
            $response['message'] = "A new message with id '$id_message' has been inserted [DB]";
            $response['id_message'] = $id_message;
        }

        return $response;
    }

}