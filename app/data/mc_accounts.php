<?php

class MC_Accounts {

    protected $_name = 'Accounts_';

    public function selectOne($ID_ACCOUNT) {
        $DB_Accounts = new DB_Accounts();

        $response = array();
        $account_data = array();

        $id_account = (int) $ID_ACCOUNT;
        if (empty($response) && empty($id_account)) {
            $response['success'] = false;
            $response['message'] = "Required value ID_ACCOUNT is missing in MC_Accounts->selectOne()";
            $response['err'] = 0;
        }

        if (empty($response)) {
            $key = $this->_name . $id_account;

            $cached_data = getCache()->get($key);
            if (!$cached_data) {
                $r_selectOne = $DB_Accounts->selectOne($id_account);
                if ($r_selectOne['success']) {
                    getCache()->set($key, $r_selectOne['account_data']);
                }
                $response = $r_selectOne;
            } else {
                $account_data = $cached_data;
            }
        }

        if (empty($response) && empty($account_data)) {
            $response['success'] = false;
            $response['message'] = "The requested account with id '$id_account' was not found [MC]";
            $response['err'] = 0;
        }

        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = "Here is the account with id '$id_account' [MC]";
            $response['account_data'] = $account_data;
        }

        return $response;
    }

}