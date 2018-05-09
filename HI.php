<?php
	require_once("connect.php");
	$db->exec('SET CHARACTER SET utf8');
	$db->query("SET NAMES utf8");
?>
<html>
	<?php
		$account=$_GET["account"];
		if($account!=NULL){
			echo 'HI!, '.$account;
			$sql = "DELETE FROM TEMP WHERE ACCOUNT = ?";
			$sth = $db->prepare($sql);
			$sth->execute(array($account));
		}
	?>
</html> 