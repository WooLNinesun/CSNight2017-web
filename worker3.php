<?php
session_start ();
require_once ("lib/php/dblink.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<?php include_once ("html_header.php"); ?>
	<link href="lib/css/worker3.css?ver1"		media="(min-width: 768px)"	rel="stylesheet">
	<link href="lib/css/worker3_small.css?ver1"	media="(max-width: 768px)"	rel="stylesheet">
	<link href="lib/css/worker_content.css?ver1" rel="stylesheet">
	<link href="lib/css/lightbox.css?ver1" rel="stylesheet">
</head>

<body>
	<!-- mean -->
	<?php require ("html_menu.php") ?>
	<!-- page -->
	<div class="avoid_navbar"> 

		<?php
			$sql_cmd = "SELECT * FROM category";
			$sql_result = $db->query($sql_cmd) or die();
			$result_array = $sql_result->fetchall();
		?>

		<div id="all_filter">
			<div id="outside_folder">
				<ul id="folder_ul">
					<li id="go_to_top">
						<span class="glyphicon glyphicon-chevron-up"></span>
					</li>
					<?php
						foreach ($result_array as $key => $result) {
							$active = "";
							$long = "";
							if($key == 0)
								$active = " active";
							if(mb_strlen($result["catg_name"]) > 2)
								$long = " long_text";
					?>
					<li id="folder_<?= $result["catg_id"] ?>" class="filter_folder<?= $long ?><?= $active ?>"><?= $result["catg_name"] ?></li>
					<?php
						}
					?>
					<li id="search" style="display: none">
						<input type="text" name="search" placeholder="在找人嗎？"><!--
					 --><button type="submit" form="search">
							<span class="glyphicon glyphicon-search"></span>
						</button>
					</li>
					<li id="search_circle" style="display: none">
						<span class="glyphicon glyphicon-search"></span>
					</li>
				</ul>
			</div><!--

		 --><div id="outside_filter">
				<ul id="filter_ul">
					<!-- js will add li here -->
				</ul>
			</div>
		</div>
		<div id="small_all_filter" class="lightbox_click" data-lightbox="0">
			<div id="small_folder">
				<?= $result_array[0]["catg_name"] ?>
			</div><!--
		 --><div id="small_filter">&nbsp;</div>
		</div>
		<div id="filter_blank"></div>

		<!-- worker_content -->

		<div id="content">
			<!-- js will add worker_boxes here -->
		</div>

		<!-- LIGHTBOX -->
		<!-- small pop out filter -->
		<div class="lightbox_shadow" id="lightbox_shadow_0">
		</div>
		<div class="lightbox_pop" id="lightbox_0">
			<div id="pop_folder">
				<div class="table_wrapper">
				<div class="table_cell">
					<ul>
						<li id="pop_go_to_top">
							<span class="glyphicon glyphicon-chevron-up"></span>
						</li>
						<?php
							foreach ($result_array as $key => $result) {
								$active = "";
								if($key == 0)
									$active = " active";
						?>
						<li id="pop_folder_<?= $result["catg_id"] ?>" class="pop_filter_folder<?= $active ?>"><?= $result["catg_name"] ?></li>
						<?php
							}
						?>
					</ul>
				</div>
				</div>
			</div><!--
		 --><div id="pop_filter">
				<div class="table_wrapper">
				<div class="table_cell">
					<ul id="pop_filter_ul">
						<!-- js will add li here -->
					</ul>
				</div>
				</div>
			</div>
		</div>
		<!-- worker pop-out -->
		<div class="lightbox_shadow" id="lightbox_shadow_1">
		</div>
		<div class="lightbox_pop" id="lightbox_1">
			<div id="pop_img">
				<!-- small image -->
				<div id="small_img" class="img" style="background-image:url('')">
					<!-- big image -->
					<div id="big_img" class="img" style="background-image:url('')">
					</div>
				</div>
			</div>
			<div id="pop_text">
				<div class="table_wrapper">
				<div class="table_cell">
					<div id="pop_inside_text">
						<h2></h2>
						<!-- <h3 class="short">學術部</h3> -->
						<h3></h3>
						<p>
							<span class="underline"></span>
						</p>
					</div>
				</div>
				</div>
			</div>
		</div>
	</div>
	<!-- footer -->
	<?php require ("html_footer.php") ?>
	<!-- javascript -->
	<?php include_once ("html_includejs.php"); ?>

    <script src="lib/js/worker_ajax.js?ver1"></script>
    <script src="lib/js/worker3_small.js?ver1"></script>
    <script src="lib/js/worker3.js?ver1"></script>
	<!-- lightbox -->
    <script src="lib/js/worker_content.js?ver1"></script>
    <script src="lib/js/fadeInOut.js?ver1"></script>
    <script src="lib/js/lightbox.js?ver1"></script>
</body>

</html>
