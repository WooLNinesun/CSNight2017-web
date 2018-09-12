<?php
session_start ();
// use sqlite3
require_once ("../lib/php/dblink.php");
require_once ("lib/php/func.php");

if( $_SESSION["adminlogin"] === true ) {
	response_msg ("使用者已經登入", "warning");
    exit();
}

// set datatype to json, code to utf-8
header('Content-Type: application/json; charset=UTF-8');
// check timestamp;
if( !checktimestamp ($_GET["t"]) ) {
	response_msg ("Timeout", "error");
	exit();
}

$data = array(
    ':username'   => $_POST["username"],
    ':password'   => $_POST["password"]
);

if( login_check($data[":username"]) ) {
    $sql_cmd = "select password from admin_user where username=:username and password=:password";
    $stmt = $db->prepare($sql_cmd);
    $e = $stmt->execute($data);
    if( !$e ) { print_r($db->errorInfo()); }
    $result = $stmt->fetch();

    if ( count($result) == 1 ) {
        // count($arrayname) no data => return 1;
        response_msg ("無此使用者", "warning");
    } else {
        if ( $data[":password"] != $result["password"] ) {
            response_msg ("密碼不正確", "warning");
        } else {
            $_SESSION["adminlogin"] = true;
            $_SESSION["username"] = $data[":username"];
            log_action($data[":username"], "login");
            response_msg ("登入成功", "success");
        }
    }
} else {
    response_msg ("使用者名稱或密碼只能含有中英文大小寫和數字", "warning");
}

?>