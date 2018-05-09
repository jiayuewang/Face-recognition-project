<?php
	require_once("connect.php");
	$db->exec('SET CHARACTER SET utf8');
	$db->query("SET NAMES utf8");
?>
<?php
	$ANS=$_POST["answer"]; 
	$question_id = $_POST['qid'];
	$account = $_POST['account'];
	
	if($question_id!=NULL&&account!=NULL&&ANS!=NULL){
		
		$sql = "SELECT * FROM ACCOUNT WHERE NAME = ?";
		$sth = $db->prepare($sql);
		$sth->execute(array($account));
		$row = $sth->fetchObject();
		$account_id = $row->AID;
		
		$sql = "INSERT INTO ANSWER(AID,Q_ID,A_ID,ANSWER)
				VALUES (NULL, ?, ?, ?)";
		$sth = $db->prepare($sql);
		$sth->execute(array($question_id,$account_id,$ANS));
	}
?>