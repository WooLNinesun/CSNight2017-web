<?php
session_start ();
if( !isset($_SESSION["adminlogin"]) or $_SESSION["adminlogin"] === false ) {
	$_SESSION["adminlogin"] = false;
	header("Location: LogIn.php");
}
require_once ("../lib/php/dblink.php");

$msg = "訊息編輯完成後，點擊新增按鈕。如果要編輯已存在的資料，請點選資料後方的 edit";

//select perform data form sql 
$sql_cmd = "select sponsor_id, name, intro, img_url, site, address, phone, work_time
			from sponsor
			ORDER BY sponsor_id ASC";
$sql_result = $db->query($sql_cmd) or die($sql_cmd);
if( $sql_result === false ) {
	print_r($db->errorInfo());
} else {
	$sponsor_data = $sql_result->fetchAll();
}

// cut intro and img_url to read more;
$showIntroChar = 10;
$showImgURLChar = 10; 
$showAddressChar = 10;
$showSiteChar = 10;
foreach ($sponsor_data as $key => $result) {
	if ( strlen($result["intro"]) > $showIntroChar ) {
		$sponsor__data[$key]["intro_cut"] = substr($result["intro"], 0, $showIntroChar)."&nbsp;...";
	} else {
		$sponsor_data[$key]["intro_cut"] = $result["intro"];
	}
	if ( strlen($result["img_url"]) > $showImgURLChar ) {
		$sponsor_data[$key]["img_url_cut"] = substr($result["img_url"], 0, $showImgURLChar)."&nbsp;...";
	} else {
		$sponsor_data[$key]["img_url_cut"] = $result["img_url"];
	}
	if ( strlen($result["address"]) > $showAddressChar ) {
		$sponsor_data[$key]["address_cut"] = substr($result["address"], 0, $showAddressChar)."&nbsp;...";
	} else {
		$sponsor_data[$key]["address_cut"] = $result["address"];
	}
	if ( strlen($result["site"]) > $showSiteChar ) {
		$sponsor_data[$key]["site_cut"] = substr($result["site"], 0, $showSiteChar)."&nbsp;...";
	} else {
		$sponsor_data[$key]["site_cut"] = $result["site"];
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
					<div class="col-sm-4">
						<label>ID</label>
						<select class="form-control" id="form_sponsor_id">
							<optgroup label="預設">	
								<option value="a" selected>新增</option>
							</optgroup>
							<optgroup label="店家">
<?php
							foreach ($sponsor_data as $result) {
?>
								<option value=<?=$result["sponsor_id"]?> ><?=$result["name"]?></option>
<?php
							}
?>
							</optgroup>
						</select>
					</div>
					<div class="col-sm-4">
						<label>電話</label>
						<input type="text" class="form-control" id="form_phone">		
					</div>
					<div class="col-sm-4">
						<label>營業時間</label>
						<input type="text" class="form-control" id="form_work_time">		
					</div>
				</div>
				<div class="form-group">
					<label>名稱</label>
					<input type="text" class="form-control" id="form_name">
				</div>
				<div class="form-group">
					<label>地址</label>
					<input type="text" class="form-control" id="form_address">
				</div>
				<div class="form-group">
					<label>介紹</label>
					<textarea class="form-control intro" id="form_intro" rows="10"></textarea> 
				</div>
				<div class="form-group">
					<label>網站</label>
					<input type="text" class="form-control" id="form_site">
				</div>
				<div class="form-group">
					<label>圖片連結</label>
					<input type="text" class="form-control" id="form_img_url">
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
						<th colspan="9"><h2 class="text-center">贊助商資料</h2></th>
					</tr>
					<tr>
						<th>ID</th>
						<th>名稱</th>
						<th>介紹</th>
						<th>網站</th>
						<th>電話</th>
						<th>營業時間</th>
						<th>地址</th>
						<th>圖片</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
<?php
				foreach ($sponsor_data as $result) {
?>
					<tr>
						<td id="table_sponsor_id"><?=$result["sponsor_id"]?></td>
						<td id="table_name"><?=$result["name"]?></td>
						<td id="table_intro" title="<?=$result["intro_cut"]?>"><?=$result["intro_cut"]?></td>
						<td id="table_site" title="<?=$result["site"]?>">
							<a href="<?=$result["site"]?>" target="_blank"><?=$result["site_cut"]?></a>	
						</td>
						<td id="table_phone"><?=$result["phone"]?></td>
						<td id="table_work_time"><?=$result["work_time"]?></td>
						<td id="table_address" title="<?=$result["address"]?>"><?=$result["address_cut"]?></td>
						<td id="table_img_url" title="<?=$result["img_url"]?>">
							<a href="<?=$result["img_url"]?>" target="_blank"><?=$result["img_url_cut"]?></a>	
						</td>
						<td id="action" class="action_icon text-right">
							<a id="edit_data" href=""><i class="fa fa-pencil fa-lg fa-fw"></i></a>
							<a id="del_data" href=""><i class="fa fa-trash fa-lg fa-fw"></i></a>
						</td>
					</tr>
<?php
				}
?>					
				</tbody>
			</table>			
		</div>
	</div>
	<!-- footer -->
	<?php require ("html_footer.php") ?>
	<!-- javascript -->
	<?php include_once ("html_includejs.php"); ?>
	<script src="lib/js/SponsorInfo.js"></script>
</body>

</html>