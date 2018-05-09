<?php
	require_once("connect.php");
	$ACCOUNT=$_POST["ACCOUNT"];
	$db->exec('SET CHARACTER SET utf8');
	$db->query("SET NAMES utf8");
?>

<?php
	send_password($ACCOUNT, $db);
?>
<?php
	function send_password($A, $db){
		$sql = "SELECT PASSWORD FROM ACCOUNT WHERE NAME = ?";
		$sth = $db->prepare($sql);
		$sth->execute(array($A));
		$row = $sth->fetchObject();
		$output_password = array();
		$i=1;
		if($row != NULL){
			$output_password[$i]["output_password"]=$row->PASSWORD;
			echo json_encode($output_password);
		}
		else{
			$output_password[$i]["output_password"]=" ";
			echo json_encode($output_password);
		}
	}
?>