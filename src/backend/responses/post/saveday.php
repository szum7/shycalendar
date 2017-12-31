<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Content-Type: application/json; charset=UTF-8");
$backEndRoot = '../../';
require_once $backEndRoot . 'db.php';
require_once $backEndRoot . 'misc.php';
require_once $backEndRoot . 'viewmodels.php';
require_once $backEndRoot . 'actions.php';
require_once $backEndRoot . 'calendar.php';

$data = json_decode(file_get_contents('php://input'));

$a = new actions();
$result = $a->SaveDay($connection_id, $data);

echo json_encode($a->response);
