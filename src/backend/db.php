<?php
header("Content-Type: text/html; charset=utf-8");
$connection_id = new mysqli("localhost", "root", "", "shy_calendar_v1");
//$connection_id = new mysqli("localhost", "szumcom_root", "1BitBitbit7", "szumcom_shycalendar");
if ($connection_id->connect_errno) {
    echo "Failed to connect to MySQL: (" . $connection_id->connect_errno . ") " . $connection_id->connect_errno;
}
$connection_id->set_charset("utf8");
?>