<?php
	require_once("connect.php");
	$ACCOUNT=$_POST["ACCOUNT"];
	$NEW_PASSWORD=$_POST["NEW_PASSWORD"];
	$db->exec('SET CHARACTER SET utf8');
	$db->query("SET NAMES utf8");
?>
<?php
	update_password($ACCOUNT, $NEW_PASSWORD, $db);
?>
<?php
	function update_password($A,$NP,$db){
		/*check if ACCOUNT exist*/
		
		$sql = "SELECT NAME FROM `ACCOUNT` WHERE NAME =?";
		$sth = $db->prepare($sql);
		$sth->execute(array($A));
		$row = $sth->fetchObject();
		
		if($row !== NULL){
			/*update ACCOUNT*/
			$sql = "UPDATE ACCOUNT SET PASSWORD = ? WHERE NAME = ?";
			$sth = $db->prepare($sql);
			$sth->execute(array($NP,$A));
		}
	}
?>