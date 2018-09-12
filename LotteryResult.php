<?php
session_start ();
require_once ("lib/php/dblink.php");

$Prizes = array(
    1 =>  "(SoundBot)可聽音樂的毛帽",
    2 =>  "(SoundBot)藍芽喇叭",
    3 =>  "(SoundBot)藍芽喇叭",
    4 =>  "(團圓堅果)咖啡禮盒",
    5 =>  "(團圓堅果)茶葉禮盒",
    6 =>  "(彼克蕾友善咖啡館) 140 元餐卷",
    7 =>  "(彼克蕾友善咖啡館) 140 元餐卷",
    8 =>  "(彼克蕾友善咖啡館) 140 元餐卷",
    9 =>  "(彼克蕾友善咖啡館) 140 元餐卷",
    10 => "(彼克蕾友善咖啡館) 140 元餐卷",
    11 => "(CANMAKE)指甲油",
    12 => "(CANMAKE)指甲油",
    13 => "(CANMAKE)指甲油",
    14 => "(CANMAKE)指甲油",
    15 => "(CANMAKE)指甲油",
    16 => "(CANMAKE)指甲油",
    17 => "(CANMAKE)指甲油",
    18 => "(CANMAKE)指甲油",
    19 => "(CANMAKE)指甲油",
    20 => "(團圓堅果)冰糖核桃",
    21 => "(團圓堅果)冰糖核桃",
    22 => "(團圓堅果)冰糖核桃",
    23 => "(團圓堅果)冰糖核桃",
    24 => "(團圓堅果)百元禮卷",
    25 => "(團圓堅果)百元禮卷",
    26 => "(團圓堅果)百元禮卷",
    27 => "(團圓堅果)百元禮卷",
    28 => "(團圓堅果)百元禮卷",
    29 => "(團圓堅果)百元禮卷",
    30 => "(團圓堅果)百元禮卷",
    31 => "(團圓堅果)百元禮卷",
    32 => "(團圓堅果)百元禮卷",
    33 => "(團圓堅果)百元禮卷",
);

//select adminuser data form sql 
$sql_cmd = "SELECT `Order`, `name` FROM LotteryResult ORDER BY `Order` ASC";
$sql_result = $db->query($sql_cmd);
if( $sql_result === false ) {
	print_r($db->errorInfo());
} else {
	$Lottery = $sql_result->fetchAll();
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
<div id="table-box" class="table-box">
			<table id="table" class="table table-hover">
				<thead>
					<tr>
						<th colspan="4"><h2 class="text-center">中獎名單</h2></th>
					</tr>
					<tr>
						<th>順位</th>
						<th>名字(Facebook)</th>
						<th>獎項</th>
					</tr>
				</thead>
				<tbody id="table_data">
                <?php
                    foreach ($Lottery as $result) {
                ?>
                        <tr>
                            <td id="table_UserName"><?=$result["Order"]?></td>
                            <td id="table_Name"><?=$result["name"]?></td>
                            <td id="table_Time"><?=$Prizes[$result["Order"]]?></td>
                        </tr>
                <?php
                    }
                ?>	
				</tbody>
				<tfoot>
					<tr>
						<th colspan="5"><h2 class="text-center"><a href="lib/file/Token.pdf">Tokens Detail</a></h2></th>
					</tr>
				</tfoot>
			</table>			
		</div>
	</div>
	<!-- footer -->
	<?php require ("html_footer.php") ?>
	<!-- javascript -->
	<?php include_once ("html_includejs.php"); ?>
</body>

</html>