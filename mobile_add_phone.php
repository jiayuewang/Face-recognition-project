<?php
	require_once("connect.php");
	$PHONE=$_POST["PHONE"];
	$db->exec('SET CHARACTER SET utf8');
	$db->query("SET NAMES utf8");
?>
<?php
	if($PHONE!=NULL){ //regist user ID
		regist_ID($PHONE,$db);
	}
?>
<?php
	function regist_ID($P,$db){
		/*check if the owner exists*/
		$sql = "SELECT PHONE FROM `PHONE` WHERE PHONE = ?";
		$sth = $db->prepare($sql);
		$sth->execute(array($P));
		$row = $sth->fetchObject();
		if(isempty($row)){
			$sql = "INSERT INTO PHONE (PID, PHONE) 
					VALUES (NULL, ?) ON DUPLICATE KEY UPDATE PID=PID";
			$sth = $db->prepare($sql);
			$sth->execute(array($P);
		}
	}
?>