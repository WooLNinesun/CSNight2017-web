<?php
session_start ();
if( !isset($_SESSION["adminlogin"]) or $_SESSION["adminlogin"] == false ) {
	$_SESSION["adminlogin"] = false;
	header("Location: LogIn.php");
}
require_once ("../lib/php/dblink.php");

$msg = "訊息編輯完成後，直接 submit 即可。如果要編輯已存在的資料，請點選資料後方的 edit";

//select perform data form sql
$sql_cmd = "select * from worker ORDER BY worker_id DESC";
$sql_result = $db->query($sql_cmd);
if( $sql_result === false ) {
	print_r($db->errorInfo());
} else {
	$worker_data = $sql_result->fetchAll();
}
// cut intro and img_url to read more;
$showIntroChar = 25;
$showImgURLChar = 25;
$showSmallImgURLChar = 25;
foreach ($worker_data as $key => $result) {
	// self_intro
	if ( strlen($result["self_intro"]) > $showIntroChar ) {
		$worker_data[$key]["self_intro_cut"] = substr($result["self_intro"], 0, $showIntroChar)."&nbsp;...";
	} else {
		$worker_data[$key]["self_intro_cut"] = $result["self_intro"];
	}
	// img_url
	if ( strlen($result["img_url"]) > $showImgURLChar ) {
		$worker_data[$key]["img_url_cut"] = substr($result["img_url"], 0, $showImgURLChar)."&nbsp;...";
	} else {
		$worker_data[$key]["img_url_cut"] = $result["img_url"];
	}
	// small_img_url
	if ( strlen($result["small_img_url"]) > $showSmallImgURLChar ) {
		$worker_data[$key]["small_img_url_cut"] = substr($result["small_img_url"], 0, $showSmallImgURLChar)."&nbsp;...";
	} else {
		$worker_data[$key]["small_img_url_cut"] = $result["small_img_url"];
	}
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
		<div class="row msg-box">
			<p class="bg-info msg-box-text"><?=$msg?></p>
		</div>
		<button type="button" class="btn btn-info btn-block">填資料</button>
		<div class="form-box collapse"><hr>
			<form id="form" role="form">
				<div class="form-group row">
					<div class="col-sm-3">
						<label>ID</label>
						<input list="Worker" class="form-control" id="form_worker_id" placeholder="選擇工人或表演者" autocomplete="off">
						<datalist id="Worker">
							<option value='a'>新增人員</option>
<?php
						foreach ($worker_data as $result) {
?>
							<option value=<?=$result["worker_id"]?> ><?=$result["name"]?></option>
<?php
						}
?>
						</datalist>
					</div>
					<div class="col-sm-9">
						<label>名字</label>
						<input type="text" class="form-control" id="form_name">					
					</div>
				</div>
				<div class="form-group">
					<label>自我介紹</label>
					<textarea class="form-control intro" id="form_self_intro" rows="10"></textarea> 
				</div>
				<div class="form-group">
					<label>部門職位</label>
					<input type="text" class="form-control" id="form_part">
				</div>
				<div class="form-group">
					<label>圖片連結</label>
					<input type="text" class="form-control" id="form_img_url">
				</div>
				<div class="form-group">
					<label>小圖連結</label>
					<input type="text" class="form-control" id="form_small_img_url">
				</div>
				<center>
					<button type="submit" class="btn btn-primary btn-block">submit</button>
				</center>
			</form>
		</div>
		<div id="table-box" class="table-box">
			<table class="table table-hover">
				<thead>
					<tr>
						<th colspan="7"><h2 class="text-center">工作人員資料</h2></th>
					</tr>
					<tr>
						<th colspan="7">
							<input type="text" class="form-control" id="search_input" placeholder="Search for ID or name.">
						</th>
					</tr>
					<tr>
						<th>ID</th>
						<th>名字</th>
						<th>部門</th>
						<th>介紹</th>
						<th>圖片</th>
						<th>小圖</th>
						<th></th>
					</tr>
				</thead>
				<tbody id="worker_data">
<?php
				foreach ($worker_data as $result) {
?>
					<tr>
						<td id="table_worker_id"><?=$result["worker_id"]?></td>
						<td id="table_name"><?=$result["name"]?></td>
						<td id="table_part"><?=$result["self_part"]?></td>
						<td id="table_self_intro"><?=$result["self_intro_cut"]?></td>
						<td id="table_img_url" title="<?=$result["img_url"]?>">
							<a href="<?=$result["img_url"]?>" target="_blank"><?=$result["img_url_cut"]?></a>
						</td>
						<td id="table_small_img_url" title="<?=$result["small_img_url"]?>">
							<a href="<?=$result["small_img_url"]?>" target="_blank"><?=$result["small_img_url_cut"]?></a>
						</td>
						<td id="action" class="action_icon text-right">
							<a id="edit_data" href=""><i class="fa fa-pencil fa-lg fa-fw"></i></a>
							<a id="del_data" href=""><i class="fa fa-trash fa-lg fa-fw"></i></a>
							<a id="part_data" href=""><i class="fa fa-search fa-lg fa-fw"></i></a>
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
	<script src="lib/js/WorkerInfo.js"></script>
</body>

</html>