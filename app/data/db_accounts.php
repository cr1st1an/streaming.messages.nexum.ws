<?php

class DB_Accounts {

    protected $_name = 'accounts';

    public function selectOne($ID_ACCOUNT) {
        $response = array();
        $account_data = array();

        $id_account = (int) $ID_ACCOUNT;
        if (empty($response) && empty($id_account)) {
            $response['success'] = false;
            $response['message'] = "Required value ID_ACCOUNT is missing in DB_Accounts->selectOne()";
            $response['err'] = 0;
        }

        if (empty($response)) {
            $select_data = array(
                'id_account' => $id_account
            );
            $account_data = getDatabase()->one(
                    'SELECT * FROM ' . $this->_name . ' WHERE id_account=:id_account', $select_data
            );

            if (empty($account_data)) {
                $response['success'] = false;
                $response['message'] = "The requested account with id '$id_account' was not found [DB]";
                $response['err'] = 0;
            } else {
                $account_data['credentials'] = json_decode($account_data['credentials'], true);
            }
        }

        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = "Here is the account with id '$id_account' [DB]";
            $response['account_data'] = $account_data;
        }

        return $response;
    }

}