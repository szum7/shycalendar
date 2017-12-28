<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
$backEndRoot = '../../';
require_once $backEndRoot . 'db.php';
require_once $backEndRoot . 'misc.php';
require_once $backEndRoot . 'viewmodels.php';
require_once $backEndRoot . 'actions.php';
require_once $backEndRoot . 'calendar.php';

$b = new calender();
$b->SetRelativeMasterDate(60 * 60 * 24 * 90, 60 * 60 * 24 * 60);
echo json_encode($b->getCalendarData($connection_id), JSON_UNESCAPED_UNICODE);
