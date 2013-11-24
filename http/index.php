<?php

include_once dirname(__DIR__).'/vendors/epi/Epi.php';
include_once dirname(__DIR__).'/vendors/phire/Phirehose.php';
include_once dirname(__DIR__).'/vendors/phire/UserstreamPhirehose.php';

Epi::setPath('root', dirname(__DIR__));
Epi::setPath('base', Epi::getPath('root') . '/vendors/epi/');
Epi::setPath('app', Epi::getPath('root') . '/app/');
Epi::setPath('config', Epi::getPath('app'));
Epi::setPath('data', Epi::getPath('root') . '/app/data/');
Epi::setPath('lib', Epi::getPath('root') . '/app/lib/');

Epi::init('config', 'cache', 'database');

getConfig()->load('config.ini');

EpiDatabase::employ(
        getConfig()->get('db')->type, getConfig()->get('db')->name, getConfig()->get('db')->host, getConfig()->get('db')->username, getConfig()->get('db')->password
);
getDatabase()->execute('SET NAMES utf8mb4 COLLATE utf8mb4_bin;');

EpiCache::employ(EpiCache::MEMCACHED);

define('TWITTER_CONSUMER_KEY', getConfig()->get('twitter')->consumer_key);
define('TWITTER_CONSUMER_SECRET', getConfig()->get('twitter')->consumer_secret);


include_once Epi::getPath('data') . 'db_accounts.php';
include_once Epi::getPath('data') . 'db_streams.php';
include_once Epi::getPath('data') . 'db_messages.php';

include_once Epi::getPath('data') . 'mc_accounts.php';
include_once Epi::getPath('data') . 'mc_streams.php';
include_once Epi::getPath('data') . 'mc_messages.php';

include_once Epi::getPath('lib') . 'Streaming.php';

$MC_Accounts = new MC_Accounts();
$DB_Streams = new DB_Streams();

$go = true;
if ($go) {
    $args = getopt("i:");
    if (empty($args))
        $go = false;
}

if ($go) {
    $r_selectOne = $MC_Accounts->selectOne($args['i']);
    if (!$r_selectOne['success']) {
        echo(json_encode($r_selectOne));
        $go = false;
    } else {
        $account_data = $r_selectOne['account_data'];
    }
}

if ($go) {
    $r_selectAll = $DB_Streams->selectAll($account_data['id_account']);
    if (!$r_selectAll['success']) {
        echo(json_encode($r_selectAll));
        $go = false;
    } else {
        $streams_data = $r_selectAll['streams_data'];
    }
}

if ($go && !empty($streams_data)) {
    echo "The account is currently being streamed.";
    $go = false;
}

if ($go) {
    $stream_data = array(
        'id_account' => $account_data['id_account']
    );
    $r_insert = $DB_Streams->insert($stream_data);
    if (!$r_insert['success']) {
        echo(json_encode($r_insert));
        $go = false;
    }
}

if ($go) {
    echo "The new stream has been registered";
    
    $Streaming = new Streaming($account_data['credentials']['oauth_token'], $account_data['credentials']['oauth_token_secret']);
    $Streaming->setAccount($account_data);
    $Streaming->consume();
}