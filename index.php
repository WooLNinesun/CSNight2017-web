<?php
session_start ();
require_once ("lib/php/dblink.php");
?>
<!DOCTYPE html>
<html lang="en">
 <!--
  ┌───┐   ┌───┬───┬───┬───┐ ┌───┬───┬───┬───┐ ┌───┬───┬───┬───┐ ┌───┬───┬───┐
  │Esc│   │ F1│ F2│ F3│ F4│ │ F5│ F6│ F7│ F8│ │ F9│F10│F11│F12│ │P/S│S L│P/B│  ┌┐    ┌┐    ┌┐
  └───┘   └───┴───┴───┴───┘ └───┴───┴───┴───┘ └───┴───┴───┴───┘ └───┴───┴───┘  └┘    └┘    └┘
  ┌───┬───┬───┬───┬───┬───┬───┬───┬───┬───┬───┬───┬───┬───────┐ ┌───┬───┬───┐ ┌───┬───┬───┬───┐
  │~ `│! 1│@ 2│# 3│$ 4│% 5│^ 6│& 7│* 8│( 9│) 0│_ -│+ =│ BacSp │ │Ins│Hom│PUp│ │N L│ / │ * │ - │
  ├───┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─────┤ ├───┼───┼───┤ ├───┼───┼───┼───┤
  │ Tab │ Q │ W │ E │ R │ T │ Y │ U │ I │ O │ P │{ [│} ]│ | \ │ │Del│End│PDn│ │ 7 │   │ 9 │   │
  ├─────┴┬──┴┬──┴┬──┴┬──┴┬──┴┬──┴┬──┴┬──┴┬──┴┬──┴┬──┴┬──┴─────┤ └───┴───┴───┘ ├───┼───┼───┤ + │
  │ Caps │ A │ S │ D │ F │ G │ H │ J │ K │ L │: ;│" '│ Enter  │               │ 4 │   │ 6 │   │
  ├──────┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴─┬─┴────────┤     ┌───┐     ├───┼───┼───┼───┤
  │ Shift  │ Z │ X │ C │ V │ B │ N │ M │< ,│> .│? /│  Shift   │     │ ↑ │     │   │   │   │   │
  ├─────┬──┴─┬─┴──┬┴───┴───┴───┴───┴───┴──┬┴───┼───┴┬────┬────┤ ┌───┼───┼───┐ ├───┴───┼───┤ E││
  │ Ctrl│    │Alt │         Space         │ Alt│    │    │Ctrl│ │ ← │ ↓ │ → │ │   0   │ . │←─┘│
  └─────┴────┴────┴───────────────────────┴────┴────┴────┴────┘ └───┴───┴───┘ └───────┴───┴───┘
-->
<head>
	<?php include_once ("html_header.php"); ?>
</head>

<body>
	<!-- mean -->
	<?php require ("html_menu.php") ?>
    <!-- Full Page Image Background Carousel Header -->
    <header id="myCarousel" class="carousel slide">
<?php
            $sql_cmd = "select * from index_fullimg ORDER BY fillimg_id ASC";
            $sql_result = $db->query($sql_cmd) or die();
            $result_array = $sql_result->fetchall();
?>

        <!-- Indicators -->
        <ol class="carousel-indicators">
<?php
			$num = sizeof($result_array);
			if($num > 6) {
				$num = 6;
			}
			for($i = 0; $i < $num; ++$i) {
				$active = "";
				if($i == 0) {
					$active="active";
				}
?>
			<li data-target="#myCarousel" data-slide-to="<?= $i ?>" class="<?= $active ?>"></li>
<?php
			}
?>
            <!-- <li data-target="#myCarousel" data-slide-to="0" class="active"></li> -->
            <!-- <li data-target="#myCarousel" data-slide-to="1"></li> -->
            <!-- <li data-target="#myCarousel" data-slide-to="2"></li> -->
        </ol>
        <!-- Wrapper for Slides -->
        <div class="carousel-inner">
<?php

            foreach ($result_array as $key => $result) {
				if($key >= 6) {
					break;
				}
                $first = ($key == 0)? 'active':'';
?>
            <div class="item <?=$first?>">
                <div class="fill" style="background-image:url('<?=$result["img_url"]?>');"></div>
                <div class="carousel-caption">
                    <h2><?=$result["caption"]?></h2>
                </div>
            </div>
<?php
            }
?>
        </div>

        <!-- Controls -->
        <a class="left carousel-control" href="#myCarousel" data-slide="prev">
            <span class="icon-prev"></span>
        </a>
        <a class="right carousel-control" href="#myCarousel" data-slide="next">
            <span class="icon-next"></span>
        </a>

    </header>
	<!-- page -->
	<div class="container avoid_navbar" id="perform">
    	<div class="row">
            <div class="col-lg-12">
				<h1 class="text-center">資訊之夜表演</h1>
            </div>
        </div>
        <hr>
        <div class="row">
        <script>
            var part = [];
        </script>
<?php
            $sql_cmd = "select * from part WHERE order_id != 0 ORDER BY order_id ASC";
            $sql_result = $db->query($sql_cmd) or die();
            $result_array = $sql_result->fetchall();

            foreach ($result_array as $key => $result) {
                $pos = ($key % 2)? 3:1;
?>
                <div class="col-sm-8 col-sm-offset-<?=$pos?>">
                    <div id="banner_<?=$key?>" class="banner" style="background-image:url(<?=$result["img_url"]?>);">
                    </div>
                </div>
                <script>
                    part[<?=$key?>] = {
                        part_id:    "<?= $result["part_id"] ?>",
						order:		"<?= $result["order_id"] ?>",
                        caption:    "<?= $result["caption"] ?>",
                        intro:      "<?= str_replace(array("\r\n","\r","\n"),"<br/>", $result["intro"]) ?>",
                        img_url:    "<?= $result["img_url"] ?>",
					};
                </script>
<?php
            }
?>
        </div>
        <div id="livebox">
        	<div class="livebox_pic">
                <div class="fill" style="background-image:url('');"></div>
        	</div><!-- to erase the blank between two inline-block
         --><div class="livebox_text">
        		<h2>大二舞</h2>
        		<p>description</p>
        	</div>
        </div>
        <div class="livebox_shadow"></div>
    </div>
    <!-- footer -->
    <?php require ("html_footer.php") ?>
	<!-- javascript -->
	<?php include_once ("html_includejs.php"); ?>
    <script src="lib/js/index.js?ver1"></script>
	<script>
	    $('.carousel').carousel({
	        interval: 5000 //changes the speed
	    })
    </script>
</body>

</html>
