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
			':worker_id'  => $_POST["WorkerID"],
			':part_id'    => $_POST["PartID"],
			':priority'    => $_POST["Priority"],
		);
		// execute sql
		$insert_result = sql_insert("HRM", $data);
		if( $insert_result != true ) { print_r($insert_result); }
		// log action
		$stmt = null;
		$sql_cmd = "SELECT A.name, B.caption FROM worker A, part B WHERE A.worker_id = :worker_id AND B.part_id = :part_id";
		$stmt = $db->prepare($sql_cmd);
		$stmt->execute(
			array(
				':worker_id'  => $data[":worker_id"],
				':part_id'    => $data[":part_id"],
			)
		);
		$result = $stmt->fetch();
		log_action ($_SESSION["username"], "新增人事：".$result["name"]." - ".$result["caption"]." 的資料");
		// response ajax
		response_msg ("資料增加成功", "success");
		exit();
	}
	case 'del': {
		$data = array(
			':worker_id'  => $_POST["ID"],
			':name' => $_POST["WorkerName"],
			':caption' => $_POST["PartName"],
		);
		// execute sql
		$sql_cmd = "DELETE FROM HRM WHERE data_id = :worker_id";
		$stmt = $db->prepare($sql_cmd);
		$e = $stmt->execute(
			array(
				':worker_id'  => $data[":worker_id"],
			)
		);
		if( !$e ) { print_r($db->errorInfo()); }
		// log action
		log_action ($_SESSION["username"], "刪除人事：".$data[":name"]." - ".$data[":caption"]." 的資料");
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