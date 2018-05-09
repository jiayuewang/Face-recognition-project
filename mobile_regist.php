<?php
	require_once("connect.php");
	$db->exec('SET CHARACTER SET utf8');
	$db->query("SET NAMES utf8");
	$ACCOUNT = $_POST["ACCOUNT"];
	$PASSWORD = $_POST["PASSWORD"];
	$IS_TEACHER = $_POST["IS_TEACHER"];
?>
<?php
	if($ACCOUNT!=NULL){
		$AUTHORITY;
		if($IS_TEACHER == "on") $AUTHORITY="teacher";
		else $AUTHORITY="student";
		
		$space = ' ';
		$pos = strpos($ACCOUNT, $space);
		$pos2 = strpos($PASSWORD, $space);
		
		if($pos === false && $pos2 === false){
			/*insert into account*/
			insert_account($ACCOUNT, $PASSWORD, $AUTHORITY, $db);
		}
		else{
			$i=1;
			$output=array();
			$output[$i]["reg_exist"]=0;
			$output[$i]["reg_space"]=1;
			echo json_encode($output);
		}
	}
?>
<?php
	function insert_account($ACCOUNT,$PASSWORD,$AUTHORITY,$db){
		$sql = "SELECT * FROM ACCOUNT WHERE NAME = ?";
		$sth = $db->prepare($sql);
		$sth->execute(array($ACCOUNT));
		$row = $sth->fetchObject();
		//$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		if($row != NULL){
			$i=1;
			$output=array();
			$output[$i]["reg_exist"]=1;
			$output[$i]["reg_space"]=0;
			echo json_encode($output);
		}
		else{
			/*insert ACCOUNT*/
			$sql = "INSERT INTO ACCOUNT (AID, NAME, PASSWORD, AUTHORITY)
					VALUES (NULL, ?, ?, ?) ON DUPLICATE KEY UPDATE AID=AID";
			$sth = $db->prepare($sql);			
			$sth->execute(array($ACCOUNT,$PASSWORD,$AUTHORITY));
			
			$i=1;
			$output=array();
			$output[$i]["reg_exist"]=0;
			$output[$i]["reg_space"]=0;
			echo json_encode($output);
		}
	}
?>