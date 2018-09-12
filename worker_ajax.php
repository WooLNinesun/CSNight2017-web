<?php

session_start();

function return_worker_detail($worker_id) {
	require_once ("lib/php/dblink.php");

	// save the worker_id the user click
	// and let number of worker_id ++
	if( isset($_SESSION["num_worker"]) ) {
		if(! isset($_SESSION["worker"][$worker_id]) ) {
			++ $_SESSION["num_worker"];
			$_SESSION["worker"][$worker_id] = 1;
		}
	} else {
		$_SESSION["num_worker"] = 1;
		$_SESSION["worker"] = [];
		$_SESSION["worker"][$worker_id] = 1;
	}

	// get all worker's number
	$sql_cmd = "SELECT COUNT(*) AS Count FROM worker";
	$sql_result = $db->query($sql_cmd) or die();
	$num_worker = $sql_result->fetchall()[0]['Count'];

	// set datatype to json, code to utf-8
	// header('Content-Type: application/json; charset=UTF-8');

	if( is_numeric($worker_id) && intval($worker_id) < 1000 ) {
		$worker = [];
		if($_SESSION["num_worker"] == $num_worker) {
			// set the Token
			$worker["name"]				= "TOKEN!";
			$worker["img_url"]			= "lib/img/token/allworker.jpg";
			$worker["self_part"]		= "TOKEN!!";
			$worker["self_intro"]		= "有TOKEN耶！<br>趕快把TOKEN記下來(筆記~";
			$worker["reload_all_worker"]= 1;

			$_SESSION["num_worker"] = NULL;
		} else {
			// select the actual info of worker
			$sql_cmd = "SELECT name,img_url,self_part,self_intro FROM worker WHERE worker_id=" . $worker_id;
			$sql_result = $db->query($sql_cmd) or die();
			$result_array = $sql_result->fetchall();

			$worker = $result_array[0];
		}

		$response = array(
			"mode" => "success",
			"msg" => "資料傳遞成功: worker_id(" . $worker_id . ")",
			"worker" => json_encode($worker)
		);
	} else {
		$response = array(
			"mode" => "error",
			"msg" => "ERROR: worker_id (" . $worker_id . ") is not a legal format!!"
		);
	}

	echo json_encode($response);
}

function return_worker($worker_id) {

	require_once ("lib/php/dblink.php");

	// set datatype to json, code to utf-8
	// header('Content-Type: application/json; charset=UTF-8');

	if( is_numeric($worker_id) && intval($worker_id) < 1000 ) {
		$sql_cmd = "SELECT small_img_url,name FROM worker WHERE worker_id=" . $worker_id;
		$sql_result = $db->query($sql_cmd) or die();
		$result_array = $sql_result->fetchall();

		$worker = $result_array[0];

		$response = array(
			"mode" => "success",
			"msg" => "資料傳遞成功: worker_id(" . $worker_id . ")",
 			"worker" => json_encode($worker)
		);
	} else {
		$response = array(
			"mode" => "error",
			"msg" => "ERROR: worker_id (" . $worker_id . ") is not a legal format!!"
		);
	}

	echo json_encode($response);

}

function return_hrm($part_id) {

	require_once ("lib/php/dblink.php");

	// set datatype to json, code to utf-8
	// header('Content-Type: application/json; charset=UTF-8');

	if( is_numeric($part_id) && intval($part_id) < 1000 ) {
		$sql_cmd = "SELECT worker_id,priority FROM HRM WHERE part_id=" . $part_id;
		$sql_result = $db->query($sql_cmd) or die();
		$hrm = $sql_result->fetchall();

		$response = array(
			"mode" => "success",
			"msg" => "資料傳遞成功: part_id(" . $part_id . ")",
			"hrm" => json_encode($hrm)
		);
	} else {
		$response = array(
			"mode" => "error",
			"msg" => "ERROR: part_id (" . $part_id . ") is not a legal format!!"
		);
	}

	echo json_encode($response);

}

function return_part($catg_id) {

	require_once ("lib/php/dblink.php");

	// set datatype to json, code to utf-8
	// header('Content-Type: application/json; charset=UTF-8');

	if( is_numeric($catg_id) && intval($catg_id) < 1000 ) {
		$sql_cmd = "SELECT part_id,caption,img_url FROM part WHERE catg_id=" . $catg_id;
		$sql_result = $db->query($sql_cmd) or die();
		$part = $sql_result->fetchall();

		$response = array(
			"mode" => "success",
			"msg" => "資料傳遞成功: catg_id(" . $catg_id . ")",
			"part" => json_encode($part)
		);
	} else {
		$response = array(
			"mode" => "error",
			"msg" => "ERROR: catg_id (" . $catg_id . ") is not a legal format!!"
		);
	}

	echo json_encode($response);

}

?>

<?php

// server side code here

if( isset($_GET["catg_id"]) ) {
	$catg_id = $_GET["catg_id"];
	return_part($catg_id);
} else if( isset($_GET["part_id"]) ) {
	$part_id = $_GET["part_id"];
	return_hrm($part_id);
} else if( isset($_GET["worker_id"]) ) {
	$worker_id = $_GET["worker_id"];
	if( isset($_GET["detail"]) ) {
		return_worker_detail($worker_id);
	} else {
		return_worker($worker_id);
	}
}

?>
