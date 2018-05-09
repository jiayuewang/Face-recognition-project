<?php
	require_once("connect.php");
	$db->exec('SET CHARACTER SET utf8');
	$db->query("SET NAMES utf8");
?>
<?php
	$question_id = $_POST['qid'];
	
	
	if($question_id!=NULL){
		$sql = "DELETE FROM QUESTION WHERE QID = ?";
		$sth = $db->prepare($sql);
		$sth->execute(array($question_id));
	}
?>