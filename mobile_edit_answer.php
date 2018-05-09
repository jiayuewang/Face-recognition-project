<?php
	require_once("connect.php");
	$db->exec('SET CHARACTER SET utf8');
	$db->query("SET NAMES utf8");
?>
<?php
	$ANS=$_POST["new_answer"]; 
	$answer_id = $_POST['aid'];
	
	
	if($answer_id!=NULL&&ANS!=NULL){
		$sql = "UPDATE ANSWER SET ANSWER = ? WHERE AID = ?";
		$sth=$db->prepare($sql);
		$sth->execute(array($ANS,$answer_id));
	}
?>