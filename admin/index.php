<?php
session_start ();
if( !isset($_SESSION["adminlogin"]) or $_SESSION["adminlogin"] == false ) {
	$_SESSION["adminlogin"] = false;
	header("Location: LogIn.php");
}
require_once ("../lib/php/dblink.php");

$sql_cmd = "select * from admin_log ORDER BY time DESC, username ASC LIMIT 50";
$sql_result = $db->query($sql_cmd);
if( $sql_result === false ) {
	print_r($db->errorInfo());
} else {
	$log_data = $sql_result->fetchAll();
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
		<h2 class="text-center">操作紀錄</h2>
		<table class="table table-hover log_table">
			<thead>
				<tr>
					<th colspan="5">
						<input type="text" class="form-control" id="search_input" placeholder="Search for all col.">
					</th>
				</tr>
				<tr>
					<th>時間</th>
					<th>使用者</th>
					<th>操作</th>
					<th>IP</th>
				</tr>				
			</thead>
			<tbody id="table_data">
<?php
		foreach ($log_data as $result) {
?>
			<tr>
				<td id="time"><?=$result["time"]?></td>
				<td id="username"><?=$result["username"]?></td>
				<td id="action"><?=$result["action"]?></td>
				<td id="IP"><?=$result["IP"]?></td>
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
	<!-- footer -->
	<?php require ("html_footer.php") ?>
	<!-- javascript -->
	<?php include_once ("html_includejs.php"); ?>
	<script src="lib/js/index.js"></script>
</body>

</html>