<?php
	require_once("connect.php");
	$db->exec('SET CHARACTER SET utf8');
	$db->query("SET NAMES utf8");
?>
<?php
	$answer_id = $_POST['aid'];
	
	
	if($answer_id!=NULL){
		$sql = "DELETE FROM ANSWER WHERE AID = ?";
		$sth = $db->prepare($sql);
		$sth->execute(array($answer_id));
	}
?>