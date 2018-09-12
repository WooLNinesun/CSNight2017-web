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
if(isset($_GET["mode"])) {
	$mode = $_GET["mode"];
} else if(isset($_POST["mode"])) {
	$mode = $_POST["mode"];
} else {
	response_msg ("mode error", "error");
	exit();
}
switch ($mode) {
	case 'add': {
		$data = array(
			':order_id' => $_GET["order_id"],
			':catg_id' => $_GET["catg_id"],
			':caption'  => $_GET["caption"],
			':intro'    => $_GET["intro"],
			':img_url'  => $_GET["img_url"],
		);
		// execute sql
		$insert_result = sql_insert ("part", $data);
		if( $insert_result != true ) { print_r($insert_result); }
		// log action
		if($data[":order_id"] == 0) {
			log_action ($_SESSION["username"], "新增部門：".$data[":caption"]." 的資料");			
		} else {
			log_action ($_SESSION["username"], "新增表演：".$data[":caption"]." 的資料");
		}
		// response ajax
		response_msg ("資料增加成功", "success");
		exit();
	}
	case 'update': {
		$data = array(
			':order_id' => $_GET["order_id"],
			':catg_id' => $_GET["catg_id"],
			':caption'  => $_GET["caption"],
			':intro'    => $_GET["intro"],
			':img_url'  => $_GET["img_url"],
		);
		if ( is_numeric( $_GET["part_id"] ) ) {
			
			$sql_cmd = "select COUNT(part_id) count from part WHERE part_id = :part_id";
			$stmt = $db->prepare($sql_cmd);
			$e = $stmt->execute(
				array(
					':part_id' => $_GET["part_id"],
				)
			);
			if( !$e ) { print_r($db->errorInfo()); }
			$result = $stmt->fetch();
			if ( $result["count"] != 0 ) {
				// execute sql
				$update_result = sql_update ("part", $data, "part_id = ".$_GET["part_id"]);
				if( $update_result != true ) { print_r($insert_result); }
				// log action
				if($data[":order_id"] == 0) {
					log_action ($_SESSION["username"], "更新部門：".$data[":caption"]." 的資料");			
				} else {
					log_action ($_SESSION["username"], "更新表演：".$data[":caption"]." 的資料");
				}
				// response ajax
				response_msg ("資料更新成功", "success");
			} else {
				response_msg ("part_id error", "error");
			}
		}
		exit();
	}
	case 'del': {
		$data = array(
			':part_id'  => $_POST["part_id"],
			':caption'  => $_POST["caption"]
		);

		$sql_cmd = "DELETE FROM part WHERE part_id = :part_id";
		$stmt = $db->prepare($sql_cmd);
		$e = $stmt->execute(
			array(
				':part_id' =>$data[":part_id"],
			)
		);
		if( !$e ) { print_r($db->errorInfo()); }

		log_action ($_SESSION["username"], "刪除部門：".$data[":caption"]." 的資料");

		response_msg ("資料已刪除", "success");
		exit();
	}
	case 'search': {
		$data = array(
			':part_id'  => $_POST["part_id"],
		);

		$sql_cmd = "SELECT intro FROM part WHERE part_id = :part_id";
		$stmt = $db->prepare($sql_cmd);
		$e = $stmt->execute(
			array(
				':part_id' =>$data[":part_id"],
			)
		);
		if( !$e ) { print_r($db->errorInfo()); }
		$result = $stmt->fetchAll();

		// response data
		response_msg ($result[0]["intro"], "success");
		exit();
	}
	default: {
		response_msg ("mode error", "error");
		exit();		
	}
}
?>