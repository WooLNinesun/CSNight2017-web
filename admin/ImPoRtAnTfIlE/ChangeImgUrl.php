<?php 
session_start ();
if( !isset($_SESSION["adminlogin"]) or $_SESSION["adminlogin"] === false ) {
	$_SESSION["adminlogin"] = false;
	header("Location: LogIn.php");
}
// use sqlite3
require_once ("../lib/php/dblink.php");
require_once ("lib/php/func.php");
//select perform data form sql
$sql_cmd = "select * from worker ORDER BY worker_id ASC";
$sql_result = $db->query($sql_cmd);
if( $sql_result === false ) {
	print_r($db->errorInfo());
} else {
	$worker_data = $sql_result->fetchAll();
}

foreach ($worker_data as $result) {
    if( substr( $result["img_url"], 0, 8) === "lib/img/" and substr( $result["img_url"], 8, 13) != "head/" ) {
        $sql_cmd = "UPDATE worker SET img_url = 'lib/img/head/".substr( $result["img_url"], 8)."' WHERE worker_id = ".$result["worker_id"];
        $sql_result = $db->exec($sql_cmd);
        if( $sql_result === false ) {
            print_r($db->errorInfo());
        }
    }
}
?>