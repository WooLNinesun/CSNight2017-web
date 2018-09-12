<?php 
session_start ();
if( !isset($_SESSION["adminlogin"]) or $_SESSION["adminlogin"] === false ) {
	$_SESSION["adminlogin"] = false;
	header("Location: LogIn.php");
}
// use sqlite3
require_once ("../../lib/php/dblink.php");
require_once ("../lib/php/func.php");

$data = array(
	':Time'  => $_GET["Time"],
	':QID'   => $_GET["QID"],
	':Ans'   => $_GET["Ans"],
	':Right' => explode(",", $_GET["Right"]),
);

$sql_cmd = 'SELECT rewardpoint AC, punishpoint WA FROM questions WHERE id = :QID';
$stmt = $db->prepare($sql_cmd);
$e = $stmt->execute( array( ':QID' => $data[":QID"] ) );
if( !$e ) { print_r($db->errorInfo()); }
$Point = $stmt->fetch();
$ACScore = $Point["AC"]; $WAScore = $Point["WA"];
echo "QID = ".$data[":QID"].", AC score = ".$ACScore.", WA score = ".$WAScore."\n"; 

$sql_cmd = 'SELECT DISTINCT user_id FROM submissions WHERE question_id = :QID AND updated_at < :Time ORDER BY user_id ASC';
$stmt = $db->prepare($sql_cmd);
$e = $stmt->execute(
	array(
		':QID' => $data[":QID"],
		':Time' => $data[":Time"],
	)
);
if( !$e ) { print_r($db->errorInfo()); }
$UIDs = $stmt->fetchAll();

foreach ($UIDs as $value) {
	echo "==================== UID = ".$value["user_id"]." ====================\n";
	$sql_cmd = 'SELECT id, answer, state, updated_at FROM submissions WHERE question_id = :QID AND user_id = :UID AND updated_at < :Time ORDER BY updated_at ASC';
	$stmt = $db->prepare($sql_cmd);
	$e = $stmt->execute(
		array(
			':QID' => $data[":QID"],
			':UID' => $value["user_id"],
			':Time' => $data[":Time"],
		)
	);
	if( !$e ) { print_r($db->errorInfo()); }
	$QUResult = $stmt->fetchAll();

	$TotalAddScore = 0; $Old_AC = false; $New_AC = false;
	foreach ($QUResult as $Submit) {
		echo "SubmitID = ".$Submit["id"].", Ans is ".$Submit["answer"].", State is ".$Submit["state"]." and Time is ".$Submit["updated_at"]."\n";
		$RightAC = in_array($Submit["answer"], $data[":Right"]);
		switch ($Submit["state"]) {
			case 'AC': {
				if(!$Old_AC) {
					$TotalAddScore += (-$ACScore);
					echo "First old AC (-".$ACScore.")\n";
				}
				if($RightAC and !$New_AC){
					$TotalAddScore += ($ACScore);
					$New_AC = true; echo "First new AC with old AC (+".$ACScore.")\n";
				}
				$Old_AC = true;
				break;
			}
			case 'WA': {
				if($RightAC and !$New_AC){
					$New_AC = true;
					$TotalAddScore += ($ACScore);
					echo "First new AC with old WA (+".$ACScore.")\n";
				}
				if($New_AC and !$Old_AC) {
					$TotalAddScore += (-$WAScore);
					echo "After new AC and before old AC with old WA (+".-$WAScore.")\n";
				}
				break;
			}
			default: {
				# nothing to do...
				break;
			}
		}
	}
	$sql_cmd = 'SELECT COUNT(id) FROM submissions WHERE question_id = :QID AND user_id = :UID AND updated_at >= :Time AND state = :state';
	$stmt = $db->prepare($sql_cmd);
	$e = $stmt->execute(
		array(
			':QID' => $data[":QID"],
			':UID' => $value["user_id"],
			':Time' => $data[":Time"],
			':state' => "AC",
		)
	);
	if( !$e ) { print_r($db->errorInfo()); }
	$HasAC = $stmt->fetchColumn();
	if($New_AC && $Old_AC) {
		echo "Has new AC and has old AC, Question is AC\n";
	} else if(!$New_AC && $Old_AC) {
		if( $HasAC ) {
			echo "After time has AC, Question is AC\n";
		} else {
			echo "Not new AC but has old AC, Question is WA\n";
		}
	} else if($New_AC && !$Old_AC) {
		echo "Has new AC but not old AC, Question is AC\n";
	} else if(!$New_AC && !$Old_AC) {
		if( $HasAC ) {
			echo "After time has AC, Question is AC\n";
		} else {
			echo "Not new AC and not old AC, Question is WA\n";
		}
	}
	echo "TotalAddScore = ".$TotalAddScore."\n";
}

?>