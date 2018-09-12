<?php
session_start ();
if( !isset($_SESSION["adminlogin"]) or $_SESSION["adminlogin"] === false ) {
	$_SESSION["adminlogin"] = false;
	header("Location: LogIn.php");
}
// use sqlite3
require_once ("../lib/php/dblink.php");
require_once ("lib/php/func.php");
// set datatype to json, code to utf-8
header('Content-Type: application/json; charset=UTF-8');
// check timestamp;
if(!checktimestamp ($_GET["t"])) {
	response_msg ("Timeout", "error");
	exit();
}
// recv action mode;
$mode = $_POST["mode"];
switch ($mode) {
	case 'add': {
		$data = array(
			':username'   => $_POST["username"],
			':password'   => $_POST["password"],
			':name'       => $_POST["name"],
			':creat_time' => date('Y-m-d H:i:s'),
		);
		// execute sql
		$insert_result = sql_insert("admin_user", $data);
		if( $insert_result != true ) { print_r($insert_result); }
		// log action
		log_action ($_SESSION["username"], "新增管理員：".$data[":name"]." - ".$data[":username"]." 的帳號");
		// response ajax
		response_msg ("資料增加成功", "success");
		exit();
	}
	case 'del': {
		$data = array(
			':username' => $_POST["UserName"],
			':name' => $_POST["Name"],
		);
		// execute sql
		$sql_cmd = "DELETE FROM admin_user WHERE username = :username and name = :name";
		$stmt = $db->prepare($sql_cmd);
		$e = $stmt->execute($data);
		if( !$e ) { print_r($db->errorInfo()); }
		// log action
		log_action ($_SESSION["username"], "刪除管理員：".$data[":username"]." - ".$data[":name"]." 的帳號");
		// response ajax
		response_msg ("資料已刪除", "success");
		exit();
	}
	default: {
		response_msg ("mode error", "error");
		exit();		
	}
}
?>