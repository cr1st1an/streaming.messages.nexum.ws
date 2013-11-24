<?php
$id_account = $_GET['i'];
exec('nohup php ' . dirname(__DIR__) . '/http/index.php > ' . dirname(__DIR__) . '/logs/' . $id_account . '.nohup -i ' . $id_account . ' &');
?>