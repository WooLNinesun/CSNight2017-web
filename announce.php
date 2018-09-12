<?php
session_start ();
require_once ("lib/php/dblink.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<?php include_once ("html_header.php"); ?>
	<link href="lib/css/announce.css" rel="stylesheet">
</head>

<body>
	<!-- mean -->
	<?php require ("html_menu.php") ?>
	<!-- page -->
	<div class="container" style="padding-top: 55px;">
		<div class="row title-row">
			<div class="col-xs-12 col-sm-3 col-lg-3">
				<div class="row title-info">
					<div class="col-xs-4 col-sm-12 title-pic"></div>
					<div class="col-xs-8 col-sm-12 title-name"></div>
				</div>
			</div>
			<div class="hidden-xs col-sm-9 title-cover">
			</div>
			<div class="col-xs-12">
				<hr>
				<iframe src="https://www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F390769774648470&width=450&layout=standard&action=like&size=small&show_faces=false&share=true&height=35&appId=646223165562165" width="350" height="35" style="border:none;overflow:hidden;width:100%;" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
			</div>
		</div>
		<div id="post-container" class="row"></div>
	</div>

	<!-- footer -->
	<?php require ("html_footer.php") ?>
	<!-- javascript -->
	<?php include_once ("html_includejs.php"); ?>
	<script src="lib/js/announce.js"></script>
</body>

</html>