<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
$backEndRoot = '../../';
require_once $backEndRoot . 'db.php';
require_once $backEndRoot . 'viewmodels.php';
require_once $backEndRoot . 'actions.php';
require_once $backEndRoot . 'calendar.php';

$date = $_POST["date"];
$originalMysql = $_POST["originalMysql"];
$mysql = $_POST["mysql"];
