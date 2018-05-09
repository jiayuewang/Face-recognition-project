<?php
	require_once("connect.php");
	$db->exec('SET CHARACTER SET utf8');
	$db->query("SET NAMES utf8");
?>
<?php
	$QUES=$_POST["new_question"]; 
	$CONT=$_POST["new_content"];
	$question_id = $_POST['qid'];
	
	
	if($question_id!=NULL&&QUES!=NULL&&CONT!=NULL){
		$sql = "UPDATE QUESTION SET QUESTION = ?, CONTENT = ? WHERE QID = ?";
		$sth=$db->prepare($sql);
		$sth->execute(array($QUES,$CONT,$question_id));
	}
?>