<?php
	require_once("connect.php");
	$db->exec('SET CHARACTER SET utf8');
	$db->query("SET NAMES utf8");
?>
<html>
	<?php
		$code=$_GET["code"];
		$account=$_POST["account"];
		if($code!=NULL){
			$sql = "SELECT * FROM TEMP WHERE CODE = ?";
			$sth = $db->prepare($sql);
			$sth->execute(array($code));
			$row = $sth->fetchObject();
			if($row!=NULL){
				$sql = "UPDATE TEMP SET ACCOUNT = ? WHERE TID = ?";
				$sth = $db->prepare($sql);
				$sth->execute(array($account, $row->TID));
				echo 'DONE~!';
				
				
			}
		}
	?>
</html> 