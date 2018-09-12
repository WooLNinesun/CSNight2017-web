<?php
session_start ();
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
if(isset($_GET["mode"])) {
	$mode = $_GET["mode"];
} else if(isset($_POST["mode"])) {
	$mode = $_POST["mode"];
} else {
	response_msg ("mode error", "error");
	exit();
}
switch ($_POST["mode"]) {
	case 'add': {
		$data = array(
			':phone'      => $_GET["phone"],
			':work_time'  => $_GET["work_time"],
			':name'       => $_GET["name"],
			':address'    => $_GET["address"],
			':intro'      => $_GET["intro"],
			':site'       => $_GET["site"],
			':img_url'    => $_GET["img_url"],
		);
		$insert_result = sql_insert ("sponsor", $data);
		if( $insert_result != true ) { print_r($insert_result); }
		// log action
		log_action ($_SESSION["username"], "新增贊助商：".$data[":name"]." 的資料");	

		response_msg ("資料增加成功", "success");
		exit();
	}
	case 'update': {
		$data = array(
			':phone'      => $_GET["phone"],
			':work_time'  => $_GET["work_time"],
			':name'       => $_GET["name"],
			':address'    => $_GET["address"],
			':intro'      => $_GET["intro"],
			':site'       => $_GET["site"],
			':img_url'    => $_GET["img_url"],
		);
		if ( is_numeric( $_GET["sponsor_id"] ) ) {
			$sql_cmd = "select COUNT(sponsor_id) count from sponsor WHERE sponsor_id =:sponsor_id";
			$stmt = $db->prepare($sql_cmd);
			$e = $stmt->execute(
				array(
					':sponsor_id' => $_GET["sponsor_id"],
				)
			);
			if( !$e ) { print_r($db->errorInfo()); }
			$result = $stmt->fetch();
			if ( $result["count"] != 0 ) {
				$update_result = sql_update ("sponsor", $data, "sponsor_id =".$_GET["sponsor_id"]);
				if( $update_result != true ) { print_r($insert_result); }

				log_action ($_SESSION["username"], "更新贊助商：".$data[":name"]." 的資料");

				response_msg ("資料更新成功", "success");
			} else {
				response_msg ("sponsor_id error", "error");
			}
		}
		exit();
	}
	case 'del': {
		$data = array(
			':sponsor_id'  => $_POST["sponsor_id"],
			':name'  => $_POST["name"],
		);

		$sql_cmd = "DELETE FROM sponsor WHERE sponsor_id = :sponsor_id";
		$stmt = $db->prepare($sql_cmd);
		$e = $stmt->execute(
			array(
				':sponsor_id' => $data[":sponsor_id"],
			)
		);
		if( !$e ) { print_r($db->errorInfo()); }

		log_action ($_SESSION["username"], "刪除贊助商：".$data[":name"]." 的資料");

		response_msg ("資料已刪除", "success");
		exit();
	}
	case 'search': {
		$data = array(
			':sponsor_id'  => $_POST["sponsor_id"],
		);

		$sql_cmd = "SELECT intro FROM sponsor WHERE sponsor_id = :sponsor_id";
		$stmt = $db->prepare($sql_cmd);
		$e = $stmt->execute($data);
		if( !$e ) { print_r($db->errorInfo()); }
		$result = $stmt->fetch();

		// response data
		response_msg ($result["intro"], "success");
		exit();
	}
	default: {
		response_msg ("mode error", "error");
		exit();		
	}
}
?>