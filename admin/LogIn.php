<?php
session_start ();
require_once ("../lib/php/dblink.php");

if( $_SESSION["adminlogin"] === true ) {
	header("Location: index.php");
}

$msg = "輸入帳號密碼登入後台";
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<?php include_once ("html_header.php"); ?>
	<link rel="stylesheet" type="text/css" href="lib/css/LogIn.css">
</head>

<body>
	<!-- mean -->
	<?php require ("html_menu.php") ?>
	<!-- page -->
	<div class="container avoid_navbar">
		<div class="msg-box">
			<p class="bg-info msg-box-text"><?=$msg?></p>
		</div>
		<div class="form-box"><hr>
			<form id="form" class="form" role="form">
				<div class="form-group row">
					<div class="col-sm-6">
						<input type="text" class="form-control" id="form_username" placeholder="輸入帳號">
					</div>
					<div class="col-sm-6">
						<input type="password" class="form-control" id="form_password" placeholder="輸入密碼">
					</div>
				</div>
				<center>
					<button type="submit" class="btn btn-primary btn-block">submit</button>
				</center>
			</form>
		</div>
	</div>
	<!-- footer -->
	<?php require ("html_footer.php") ?>
	<!-- javascript -->
	<?php include_once ("html_includejs.php"); ?>
	<script src="lib/js/LogIn.js"></script>
	<script src="lib/js/md5.min.js"></script>
</body>

</html>