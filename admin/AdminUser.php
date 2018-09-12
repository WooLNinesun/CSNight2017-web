<?php
session_start ();
if( !isset($_SESSION["adminlogin"]) or $_SESSION["adminlogin"] === false ) {
	$_SESSION["adminlogin"] = false;
	header("Location: LogIn.php");
}
require_once ("../lib/php/dblink.php");

$msg = "資料填寫好後直接 submit 即可，每筆資料只能刪除不能編輯。";

//select adminuser data form sql 
$sql_cmd = "SELECT username, name, creat_time FROM admin_user ORDER BY username ASC";
$sql_result = $db->query($sql_cmd);
if( $sql_result === false ) {
	print_r($db->errorInfo());
} else {
	$adminuser_data = $sql_result->fetchAll();
}
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
		<div class="msg-box">
			<p class="bg-info msg-box-text"><?=$msg?></p>
		</div>
		<button type="button" class="btn btn-info btn-block">填資料</button>
		<div class="form-box collapse"><hr>
			<form id="form" class="form" role="form">
				<div class="form-group row">
					<div class="col-sm-4">
						<label>帳號</label>
						<input type="text" class="form-control" id="form_username" placeholder="輸入帳號(只能英數)">
					</div>
					<div class="col-sm-4">
						<label>密碼</label>
						<input type="password" class="form-control" id="form_password" placeholder="輸入密碼">
					</div>
					<div class="col-sm-4">
						<label>名字</label>
						<input type="text" class="form-control" id="form_name" placeholder="輸入名字">
					</div>
				</div>
				<center>
					<button type="submit" class="btn btn-primary btn-block">submit</button>
				</center>
			</form>
		</div>
		<div id="table-box" class="table-box">
			<table id="table" class="table table-hover">
				<thead>
					<tr>
						<th colspan="4"><h2 class="text-center">管理員名冊</h2></th>
					</tr>
					<tr>
						<th colspan="4">
							<input type="text" class="form-control" id="search_input" placeholder="Search for usernames, names or times.">
						</th>
					</tr>
					<tr>
						<th>UserName</th>
						<th>名字</th>
						<th>建立時間</th>
						<th></th>
					</tr>
				</thead>
				<tbody id="table_data">
			<?php
				foreach ($adminuser_data as $result) {
			?>
					<tr>
						<td id="table_UserName"><?=$result["username"]?></td>
						<td id="table_Name"><?=$result["name"]?></td>
						<td id="table_Time"><?=$result["creat_time"]?></td>
						<td id="action" class="action_icon text-right">
							<a id="del_data" href=""><i class="fa fa-trash fa-lg fa-fw"></i></a>
						</td>
					</tr>
			<?php
				}
			?>						
				</tbody>
				<tfoot id="NoResult" style="display: none">
					<tr>
						<th colspan="5"><h2 class="text-center">No result</h2></th>
					</tr>
				</tfoot>
			</table>			
		</div>
	</div>
	<!-- footer -->
	<?php require ("html_footer.php") ?>
	<!-- javascript -->
	<?php include_once ("html_includejs.php"); ?>
	<script src="lib/js/AdminUser.js"></script>
	<script src="lib/js/md5.min.js"></script>
</body>

</html>