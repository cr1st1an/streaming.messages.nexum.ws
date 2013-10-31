<?php

class Streaming extends UserstreamPhirehose {

    private $_account;

    public function setAccount($ACCOUNT_DATA) {
        $this->_account = $ACCOUNT_DATA;
    }

    public function enqueueStatus($status) {
        if (0 === strpos($status, '{"direct_message')) {
            $status_data = json_decode($status, true);
            $identifier = ($status_data['direct_message']['sender_id'] == $this->_account['identifier']) ? null : $status_data['direct_message']['sender_id'];
            if (null != $identifier) {
                $MC_Threads = new MC_Threads();
                $thread_data = array(
                    'id_account' => $this->_account['id_account'],
                    'identifier' => $identifier,
                    'network' => 'twitter',
                    'opened' => false,
                    'outdated' => true
                );
                $MC_Threads->insert($thread_data);
                
                exec('wget -bqc ' . getConfig()->get('app')->url . '/1.0/services/01?id_account='.$this->_account['id_account']);
                echo json_encode($thread_data) . "\n";
            }
        }
    }

    protected function log($message, $level = 'notice') {
        if ('error' == $level || 'server' == $level)
            echo 'Phirehose: ' . $message;
    }

}