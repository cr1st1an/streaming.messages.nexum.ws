<?php

class DB_Threads {

    protected $_name = 'threads';

    public function insert($DATA) {
        $response = array();
        $id_thread = null;
        $message = "";

        $id_account = (int) $DATA['id_account'];
        if (empty($response) && empty($id_account)) {
            $response['success'] = false;
            $response['message'] = "Required value ID_ACCOUNT is missing in DB_Threads->insert()";
            $response['err'] = 0;
        }

        $identifier = (int) $DATA['identifier'];
        if (empty($response) && empty($identifier)) {
            $response['success'] = false;
            $response['message'] = "Required value IDENTIFIER is missing in DB_Threads->insert()";
            $response['err'] = 0;
        }

        $network = (string) $DATA['network'];
        if (empty($response) && empty($network)) {
            $response['success'] = false;
            $response['message'] = "Required value NETWORK is missing in DB_Threads->insert()";
            $response['err'] = 0;
        }

        if (empty($response)) {
            $select_data = array(
                'id_account' => $id_account,
                'identifier' => $identifier,
                'network' => $network
            );
            $thread_data = getDatabase()->one('SELECT * FROM ' . $this->_name . ' WHERE id_account=:id_account AND identifier=:identifier AND network=:network', $select_data);
            if (empty($thread_data)) {
                $insert_data = array(
                    'id_account' => $id_account,
                    'identifier' => $identifier,
                    'network' => $network,
                    'opened' => $DATA['opened'],
                    'outdated' => $DATA['outdated']
                );
                $id_thread = getDatabase()->execute(
                        'INSERT INTO ' . $this->_name . '(id_account, identifier, network, opened, outdated) VALUES(:id_account, :identifier, :network, :opened, :outdated)', $insert_data
                );
                $message = "A new thread with id '$id_thread' has been inserted [DB]";
            } else {
                $id_thread = $thread_data['id_thread'];
                $update_data = array(
                    'id_thread' => $id_thread,
                    'opened' => $DATA['opened'],
                    'outdated' => $DATA['outdated']
                );
                getDatabase()->execute('UPDATE ' . $this->_name . ' SET opened=:opened, outdated=:outdated WHERE id_thread=:id_thread', $update_data);
                $message = "The thread with id '$id_thread' has been updated [DB]";
            }

            $response['success'] = true;
            $response['message'] = $message;
            $response['id_thread'] = $id_thread;
        }

        return $response;
    }

}