<?php
session_start ();
if( !isset($_SESSION["adminlogin"]) or $_SESSION["adminlogin"] === false ) {
	$_SESSION["adminlogin"] = false;
	header("Location: LogIn.php");
}
require_once ("../lib/php/dblink.php");

$msg = "資料編輯完成後，Submit 即可。請點選 edit，會自動把該筆資料填入表單";

//select perform data form sql 
$sql_cmd = "SELECT A.part_id, A.order_id, A.caption, A.intro, A.img_url, A.catg_id, B.catg_name
			FROM part A, category B
			WHERE A.catg_id = B.catg_id
			ORDER BY A.order_id ASC";
$sql_result = $db->query($sql_cmd);
if( $sql_result === false ) {
	print_r($db->errorInfo());
} else {
	$part_data = $sql_result->fetchAll();
}

$sql_cmd = "select catg_id, catg_name 
			from category
			ORDER BY catg_id ASC";
$sql_result = $db->query($sql_cmd);
if( $sql_result === false ) {
	print_r($db->errorInfo());
} else {
	$catg_data = $sql_result->fetchAll();
}

// cut intro and img_url to read more;
$showIntroChar = 20;
$showImgURLChar = 20; 
foreach ($part_data as $key => $result) {
	if ( strlen($result["intro"]) > $showIntroChar ) {
		$part_data[$key]["intro_cut"] = substr($result["intro"], 0, $showIntroChar)."&nbsp;...";
	} else {
		$part_data[$key]["intro_cut"] = $result["intro"];
	}
	if ( strlen($result["img_url"]) > $showImgURLChar ) {
		$part_data[$key]["img_url_cut"] = substr($result["img_url"], 0, $showImgURLChar)."&nbsp;...";
	} else {
		$part_data[$key]["img_url_cut"] = $result["img_url"];
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
						<select class="form-control" id="form_part_id">
							<optgroup label="預設">	
								<option value="a" selected>a - 新增</option>
							</optgroup>
							<optgroup label="表演">
<?php
							foreach ($part_data as $result) {
								if ( $result["order_id"] != 0 ) {
?>
									<option value=<?=$result["part_id"]?> ><?=$result["part_id"]." - ".$result["caption"]?></option>
<?php
							} }
?>
							</optgroup>
							<optgroup label="部門">
<?php
							foreach ($part_data as $result) {
								if ( $result["order_id"] == 0 ) {
?>
									<option value=<?=$result["part_id"]?>><?=$result["part_id"] . " - " . $result["caption"]?></option>
<?php
							} }
?>
							</optgroup>
						</select>
					</div>
					<div class="col-sm-4">
						<label>類別</label>
						<select class="form-control" id="form_catg_id">
							<option value="" disabled selected>選擇類別</option>
<?php
						foreach ($catg_data as $result) {
?>
							<option value=<?=$result["catg_id"]?> ><?=$result["catg_name"]?></option>
<?php
						}
?>
						</select>		
					</div>
					<div class="col-sm-4">
						<label>順序</label>
						<input type="text" class="form-control" id="form_order_id">		
					</div>
				</div>
				<div class="form-group">
					<label>名稱</label>
					<input type="text" class="form-control" id="form_caption">
				</div>
				<div class="form-group">
					<label>介紹</label>
					<textarea class="form-control intro" id="form_intro" rows="10"></textarea> 
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
						<th colspan="7"><h2 class="text-center">表演資料</h2></th>
					</tr>
					<tr>
						<th>ID</th>
						<th>順序</th>
						<th>類別</th>
						<th>名稱</th>
						<th>介紹</th>
						<th>圖片</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
<?php
				foreach ($part_data as $result) {
					if ( $result["order_id"] != 0 ) { // order_id = 0 -> not perform part
?>
					<tr>
						<td id="table_part_id"><?=$result["part_id"]?></td>
						<td id="table_order_id"><?=$result["order_id"]?></td>
						<td id="table_catg_name" CatgID="<?=$result["catg_id"]?>"><?=$result["catg_name"]?></td>
						<td id="table_caption"><?=$result["caption"]?></td>
						<td id="table_intro" title="<?=$result["intro_cut"]?>"><?=$result["intro_cut"]?></td>
						<td id="table_img_url" title="<?=$result["img_url"]?>">
							<a href="<?=$result["img_url"]?>" target="_blank"><?=$result["img_url_cut"]?></a>
						</td>
						<td id="action" class="action_icon text-right">
							<a id="edit_data" href=""><i class="fa fa-pencil fa-lg fa-fw"></i></a>
							<a id="del_data" href=""><i class="fa fa-trash fa-lg fa-fw"></i></a>
						</td>
					</tr>
<?php
				} }
?>					
				</tbody>

				<thead>
					<tr>
						<th colspan="7"><h2 class="text-center">部門資料</h2></th>
					</tr>
					<tr>
						<th>ID</th>
						<th>順序</th>
						<th>類別</th>
						<th>名稱</th>
						<th>介紹</th>
						<th>圖片</th>
						<th>動作</th>
					</tr>
				</thead>
				<tbody>
<?php
				foreach ($part_data as $result) {
					if ( $result["order_id"] == 0 ) { // order_id = 0 -> not perform part
?>
					<tr>
						<td id="table_part_id"><?=$result["part_id"]?></td>
						<td id="table_order_id"><?=$result["order_id"]?></td>
						<td id="table_catg_name" CatgID="<?=$result["catg_id"]?>"><?=$result["catg_name"]?></td>
						<td id="table_caption"><?=$result["caption"]?></td>
						<td id="table_intro"><?=$result["intro_cut"]?></td>
						<td id="table_img_url" title="<?=$result["img_url"]?>">
							<a href="<?=$result["img_url"]?>" target="_blank"><?=$result["img_url_cut"]?></a>
						</td>
						<td id="action" class="action_icon text-right">
							<a id="edit_data" href=""><i class="fa fa-pencil fa-lg fa-fw"></i></a>
							<a id="del_data" href=""><i class="fa fa-trash fa-lg fa-fw"></i></a>
						</td>
					</tr>
<?php
				} }
?>					
				</tbody>
			</table>			
		</div>
	</div>
	<!-- footer -->
	<?php require ("html_footer.php") ?>
	<!-- javascript -->
	<?php include_once ("html_includejs.php"); ?>
	<script src="lib/js/PartInfo.js"></script>
</body>

</html>