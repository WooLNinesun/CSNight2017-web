<?php
	//$dsn='sqlite:'.__DIR__.'/../sqldata/2017Night.db'; //sqlite data location
	$dsn='mysql:host=localhost;dbname=csnight2017;charset=utf8'; //mysql data location

	try{
		//$db = new PDO($dsn); //connect to sqlite database
		$db = new PDO($dsn, "csnight2017", "f186c021c25e217893c9d5bb8409"); //connect to mysql database
	} catch(PDOException $e){  //if can't connect
		// show error
		$error = 'connection_unsuccessful: '.$e->getMessage();
		print_r($error);
		// close sql pdo
		$db=null;
	}
?>