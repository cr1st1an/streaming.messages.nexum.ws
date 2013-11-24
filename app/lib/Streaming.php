<?php

class Streaming extends UserstreamPhirehose {

    private $_account;

    public function setAccount($ACCOUNT_DATA) {
        $this->_account = $ACCOUNT_DATA;
    }

    public function enqueueStatus($status) {
        if (0 === strpos($status, '{"direct_message')) {
            $status_data = json_decode($status, true);
            if (isset($status_data['direct_message'])) {
                $dm_data = $status_data['direct_message'];

                $MC_Messages = new MC_Messages();
                $message_data = array(
                    'id_account' => $this->_account['id_account'],
                    'created' => date("Y-m-d H:i:s", strtotime($dm_data['created_at'])),
                    'identifier' => $dm_data['id'],
                    'sender_id' => $dm_data['sender_id'],
                    'recipient_id' => $dm_data['recipient_id'],
                    'text' => $dm_data['text']
                );
                $r_insert = $MC_Messages->insert($message_data);
                if ($r_insert['success']) {
                    if ($dm_data['sender_id'] != $this->_account['identifier']) {
                        $id_message = $r_insert['id_message'];
                        exec('wget -bqc ' . getConfig()->get('app')->url . '/1.0/workers/02 --post-data "i01=' . $id_message . '&i02=' . md5($id_message . $message_data['identifier']) . '"');
                        echo 'wget -bqc ' . getConfig()->get('app')->url . '/1.0/workers/02 --post-data "i01=' . $id_message . '&i02=' . md5($id_message . $message_data['identifier']) . '"' . "\n";
                        echo json_encode($message_data) . "\n";
                    }
                }
            }
        }
    }

    protected function log($message, $level = 'notice') {
        echo 'Phirehose: ' . $message . "\n";
    }

}