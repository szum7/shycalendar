<?php
header("Content-Type: text/html; charset=utf-8");
require_once 'db.php';
require_once 'class/modify.php';
if(isset($_POST["type"])){
    $post = $_POST["type"];
    switch ($post) {
        case "dayModify":
            
            $date = $_POST["date"];
            $originalMysql = $_POST["originalMysql"];
            $mysql = $_POST["mysql"];
            
            $modi = new modify();
            $modi->modifyAll($connection_id, $originalMysql, $mysql, $date);
            
            break;
        default:
            break;
    }
}

/* Modify day
 * 1 - added new line
 *      - keep already existing ones (match date, intro, content)
 *      - insert non-existing
 * 2 - deleted line
 *      - delete missing line
 * 3 - modified a line
 *      - delete modified (missing) line
 *      - insert modified (new) line
 * 4 - modified line, added new
 *      - delete missing ones
 *      - insert new ones
 * 
 * - get new lines
 * - get deleted lines
 * 
 */
?>