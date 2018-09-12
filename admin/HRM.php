<?php
session_start ();
if( !isset($_SESSION["adminlogin"]) or $_SESSION["adminlogin"] === false ) {
	$_SESSION["adminlogin"] = false;
	header("Location: LogIn.php");
}
require_once ("../lib/php/dblink.php");

$msg = "訊息編輯完成後，點擊新增按鈕。如果要編輯已存在的資料，請點選資料後方的 edit";

//select perform data form sql 
$sql_cmd = "SELECT A1.data_id ID, A2.caption PartName, A3.name WorkerName, A1.priority Priority
			FROM HRM A1, part A2, worker A3
			WHERE A1.part_id = A2.part_id and A1.worker_id = A3.worker_id
			ORDER BY A1.worker_id DESC, A1.part_id ASC";
$sql_result = $db->query($sql_cmd);
if( $sql_result === false ) {
	print_r($db->errorInfo());
} else {
	$HRM_data = $sql_result->fetchAll();
}

$sql_cmd = "SELECT part_id ID, caption PartName, order_id OrderID FROM part ORDER BY part_id ASC";
$sql_result = $db->query($sql_cmd);
if( $sql_result === false ) {
	print_r($db->errorInfo());
} else {
	$part_data = $sql_result->fetchAll();
}

$sql_cmd = "SELECT worker_id ID, name WorkerName FROM worker ORDER BY worker_id ASC";
$sql_result = $db->query($sql_cmd);
if( $sql_result === false ) {
	print_r($db->errorInfo());
} else {
	$worker_data = $sql_result->fetchAll();
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
						<label>工人或表演者</label>
						<input list="Worker" class="form-control" id="form_WorkerID" placeholder="選擇工人或表演者" autocomplete="off">
						<datalist id="Worker">
<?php
						foreach ($worker_data as $result) {
?>
							<option value=<?=$result["ID"]?> ><?=$result["WorkerName"]?></option>
<?php
						}
?>
						</datalist>
					</div>
					<div class="col-sm-4">
						<label>部門或表演</label>
						<select class="form-control" id="form_PartID">
							<option value="" disabled selected>選擇部門或表演</option>
							<optgroup label="部門">
<?php
							foreach ($part_data as $result) {
								if ($result["OrderID"] == 0) {
?>
									<option value=<?=$result["ID"]?> ><?=$result["PartName"]?></option>
<?php
							} }
?>
							</optgroup>
							<optgroup label="表演">
<?php
							foreach ($part_data as $result) {
								if ($result["OrderID"] != 0) {
?>
									<option value=<?=$result["ID"]?> ><?=$result["PartName"]?></option>
<?php
							} }
?>
							</optgroup>
						</select>			
					</div>
					<div class="col-sm-4">
						<label>優先度</label>
						<input class="form-control" id="form_Priority" placeholder="填入優先度">
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
						<th colspan="5"><h2 class="text-center">人事清單</h2></th>
					</tr>
					<tr>
						<th colspan="5">
							<input type="text" class="form-control" id="search_input" placeholder="Search for names, department or time.">
						</th>
					</tr>
					<tr>
						<th>ID</th>
						<th>名字</th>
						<th>部門</th>
						<th>優先度</th>
						<th></th>
					</tr>
				</thead>
				<tbody id="HRM_data">
<?php
				foreach ($HRM_data as $result) {
?>
					<tr>
						<td id="table_ID"><?=$result["ID"]?></td>
						<td id="table_WorkerName"><?=$result["WorkerName"]?></td>
						<td id="table_PartName"><?=$result["PartName"]?></td>
						<td id="table_Priority"><?=$result["Priority"]?></td>
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
	<script src="lib/js/HRM.js"></script>
</body>

</html>