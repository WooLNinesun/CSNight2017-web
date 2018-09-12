<?php
date_default_timezone_set("Asia/Taipei");

/*
	@author: WooLNinesun 
	@depiction: user login, check username prevent sql injection
	@param: $username -> user input
 */
function login_check ($username) {
	$regex = "/^([0-9A-Za-z]+)$/";
	return preg_match($regex, $username, $tmp );
}

/*
	@author: WooLNinesun 
	@depiction: insert data to sql
	@param: $table -> what sql table to insert in
			$data -> what data to insert
 */
function sql_insert ($table, $data) {
	// generate sql cmd
	$sql_cmd = "INSERT INTO $table (";
	$first = true;
	foreach ($data as $key=>$value) {
		if ( !$first ) { $sql_cmd .= ", "; }
		$first = false;
		$sql_cmd .= substr($key, 1);
	}
	$sql_cmd .= ") VALUES (";

	$first = true;
	foreach ($data as $key=>$value) {
		if ( !$first ) { $sql_cmd .= ", "; }
		$first = false;
		$sql_cmd .=$key;
	}
	$sql_cmd .= ")";
	// sql insert by pdo
	$db = $GLOBALS['db'];
	$stmt = $db->prepare($sql_cmd);
	$e = $stmt->execute($data);
	// return result
	if( !$e ) {
		return $db->errorInfo();
	} else {
		return true;
	}
}

/*
	@author: WooLNinesun 
	@depiction: log user action to sql
	@param: $username -> user
			$action -> user to do
 */
function log_action ($username, $action) {
	$data = array(
		':username' => $username,
		':action'   => $action,
		':time'     => date('Y-m-d H:i:s'),
		':IP'       => $_SERVER["REMOTE_ADDR"],
	);

	$insert_result = sql_insert("admin_log", $data);
	return $insert_result; 
}

/*
	@author: WooLNinesun 
	@depiction: update data to sql
	@param: $table -> what sql table to update in
			$data -> what data to update
			$condition -> WHERE condition
 */
function sql_update ($table, $data, $condition) {
	$sql_cmd = "update $table set ";
	$first = 1;
	foreach ($data as $key=>$value)
	{
		if (!$first) { $sql_cmd .= ", "; }
		$first = 0;
		$sql_cmd .= "`".substr($key, 1)."`=" . $key;
	}
	$sql_cmd .= " where $condition";

	$db = $GLOBALS['db'];
	$stmt = $db->prepare($sql_cmd);
	$e = $stmt->execute($data);
	// return result
	if( !$e ) {
		return $db->errorInfo();
	} else {
		return true;
	}
}

/*
	@author: WooLNinesun 
	@depiction: get msg to callback
	@param: $msg -> what msg to return
 */
function response_msg ($msg, $mode) {
	$response = array(
		"mode" => $mode,
		"msg" => $msg
	);
	echo json_encode($response);
}

/*
	@author: WooLNinesun 
	@depiction: check timestamp
	@param: $sendtime -> data send time form client
 */
function checktimestamp ($sendtime) {
	$Sendtime = substr($sendtime, 0, 10); $Recvtime = time() + 300; $Errtime = time() - 300; // 600 sec;
	if( $Sendtime <= $Recvtime and $Sendtime >= $Errtime ) {
		return true;
	} else {
		return false;
	}
}
?>
