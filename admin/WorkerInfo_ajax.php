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
			':name'       => $_GET["name"],
			':self_part'       => $_GET["part"],
			':self_intro' => $_GET["self_intro"],
			':img_url'    => $_GET["img_url"],
			':small_img_url'    => $_GET["small_img_url"],
		);
		$insert_result = sql_insert ("worker", $data);
		if( $insert_result != true ) { print_r($insert_result); }
		// log action
		log_action ($_SESSION["username"], "新增工作人員：".$data[":name"]." 的資料");
		// response ajax
		response_msg ("資料增加成功", "success");
		exit();
	}
	case 'update': {
		$data = array(
			':name'       => $_GET["name"],
			':self_part' => $_GET["part"],
			':self_intro' => $_GET["self_intro"],
			':img_url'    => $_GET["img_url"],
			':small_img_url'    => $_GET["small_img_url"],
		);
		if ( is_numeric( $_GET["worker_id"] ) ) {
			$sql_cmd = "select COUNT(worker_id) count from worker WHERE worker_id =:worker_id";
			$stmt = $db->prepare($sql_cmd);
			$e = $stmt->execute(
				array(
					':worker_id' => $_GET["worker_id"],
				)
			);
			if( !$e ) { print_r($db->errorInfo()); }
			$result = $stmt->fetch();
			if ( $result["count"] != 0 ) {
				$update_result = sql_update ("worker", $data, "worker_id =".$_GET["worker_id"]);
				if( $update_result != true ) { print_r($insert_result); }
				
				log_action ($_SESSION["username"], "更新工作人員：".$data[":name"]." 的資料");

				response_msg ("資料更新成功", "success");
			} else {
				response_msg ("part_id error", "error");
			}
		} else {
			response_msg ("part_id error", "error");
		}
		exit();
	}
	case 'del': {
		$data = array(
			':worker_id' => $_POST["worker_id"],
			':name'      => $_POST["name"],
		);

		$sql_cmd = "DELETE FROM worker WHERE worker_id = :worker_id";
		$stmt = $db->prepare($sql_cmd);
		$e = $stmt->execute(
			array(
				':worker_id' => $data[":worker_id"],
			)
		);
		if( !$e ) { print_r($db->errorInfo()); }

		log_action ($_SESSION["username"], "刪除工作人員：".$data[":name"]." 的資料");

		response_msg ("資料已刪除", "success");
		exit();
	}
	case 'search_part': {
		$data = array(
			':worker_id'  => $_POST["worker_id"],
		);

		$sql_cmd = "SELECT caption FROM part WHERE part_id IN (SELECT part_id FROM HRM WHERE worker_id =:worker_id)";
		$stmt = $db->prepare($sql_cmd);
		$e = $stmt->execute($data);
		if( !$e ) { print_r($db->errorInfo()); }
		$result = $stmt->fetchAll();

		if( count($result) ) {
			$first = true;
			$response = "";
			foreach ($result as $value) {
				if( !$first ) {
					$response .= " | ";
				}
				$first = false;
				$response .= $value["caption"];
			}
			response_msg($response, "success");
		} else {
			response_msg("沒有表演或部門！", "warning");
		}

		exit();
	}
	case 'search_detail': {
		$data = array(
			':worker_id'  => $_POST["worker_id"],
		);

		$sql_cmd = "SELECT self_intro FROM worker WHERE worker_id =:worker_id";
		$stmt = $db->prepare($sql_cmd);
		$e = $stmt->execute($data);
		if( !$e ) { print_r($db->errorInfo()); }
		$result = $stmt->fetchAll();

		// response data
		response_msg ($result[0]["self_intro"], "success");
		exit();
	}
	default: {
		response_msg ("mode error", "error");
		exit();		
	}
}
?>