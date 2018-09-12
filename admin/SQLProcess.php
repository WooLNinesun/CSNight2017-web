<?php
session_start ();
if( !isset($_SESSION["adminlogin"]) or $_SESSION["adminlogin"] === false ) {
	$_SESSION["adminlogin"] = false;
	header("Location: LogIn.php");
}
require_once ("../lib/php/dblink.php");

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
	<div class="container avoid_navbar">
		<div class="form-box">
			<div class="row">
				<div class="col-sm-12">
					<h1>#Rejudge</h1>
				</div>
			</div><hr>
			<form id="Rejudge" class="form" role="form">
				<div class="form-group row">
					<div class="col-sm-4">
						<label>TimeStamp ( Ex: 2017-03-07 13:11:48 )</label>
						<input type="text" class="form-control" id="form_Time">
					</div>
					<div class="col-sm-4">
						<label>QuestionID</label>
						<input type="test" class="form-control" id="form_QID">
					</div>
					<div class="col-sm-4">
						<label>原本的答案</label>
						<input type="text" class="form-control" id="form_Ans">
					</div>
				</div>
				<div class="form-group row">
					<div class="col-sm-12">
						<label>要判定程對的回答，用『,』隔開多個答案</label>
						<input type="text" class="form-control" id="form_Right">
					</div>
				</div>
				<center>
					<button type="submit" class="btn btn-primary btn-block">submit</button>
				</center>
			</form>
			<hr>
		</div>
		<div class="form-box">
			<div class="row">
				<div class="col-sm-12">
					<h1>#ChangeImgUrl</h1>
				</div>
			</div><hr>
			<form id="ChangeImgUrl" class="form" role="form">
				<div class="form-group row">
					<div class="col-sm-4">
						<label>TimeStamp</label>
						<input type="text" class="form-control" id="form_Time">
					</div>
					<div class="col-sm-4">
						<label>ProblemID</label>
						<input type="test" class="form-control" id="form_PID">
					</div>
					<div class="col-sm-4">
						<label>Answer</label>
						<input type="text" class="form-control" id="form_Ans">
					</div>
				</div>
				<center>
					<button type="submit" class="btn btn-primary btn-block">submit</button>
				</center>
			</form>
			<hr>
		</div>
	</div>
	<!-- footer -->
	<?php require ("html_footer.php") ?>
	<!-- javascript -->
	<?php include_once ("html_includejs.php"); ?>
	<script src="lib/js/SQLProcess.js"></script>
</body>

</html>