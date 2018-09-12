<?php
session_start ();
if( !isset($_SESSION["adminlogin"]) or $_SESSION["adminlogin"] == false ) {
	$_SESSION["adminlogin"] = false;
	header("Location: LogIn.php");
}
require_once ("../lib/php/dblink.php");
require_once ("../lib/php/func.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<?php include_once ("html_header.php"); ?>

</head>

<body>
	<!-- mean -->
	<?php require ("html_menu.php") ?>
	<!-- page -->
	<div> 

	</div>
	<!-- footer -->
	<?php require ("html_footer.php") ?>
	<!-- javascript -->
	<?php include_once ("html_includejs.php"); ?>
</body>

</html>
