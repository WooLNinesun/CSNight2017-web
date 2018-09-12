<?php
	session_start ();
	require_once ("../lib/php/dblink.php");
	require_once ("lib/php/func.php");

	//log user action
	log_action($_SESSION["username"], "logout");

	//set client unlogin and unset session
	$_SESSION["adminlogin"] = false;
	unset($_SESSION["username"]);

	//return index
	header("Location: LogIn.php");
?>
