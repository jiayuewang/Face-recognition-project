<?php
	require_once("connect.php");
	$db->exec('SET CHARACTER SET utf8');
	$db->query("SET NAMES utf8");
?>
<?php
	$QUES=$_POST["question"]; 
	$CONT=$_POST["content"];
	$course_id = $_POST['course_id'];
	$account = $_POST['account'];
	
	if($course_id!=NULL&&account!=NULL&&QUES!=NULL&&CONT!=NULL){
		$sql = "SELECT * FROM COURSE WHERE ID = ?";
		$sth = $db->prepare($sql);
		$sth->execute(array($course_id));
		$row = $sth->fetchObject();
		$course_id = $row->CID;
		
		$sql = "SELECT * FROM ACCOUNT WHERE NAME = ?";
		$sth = $db->prepare($sql);
		$sth->execute(array($account));
		$row = $sth->fetchObject();
		$account_id = $row->AID;
		
		$sql = "INSERT INTO QUESTION(QID,C_ID,A_ID,QUESTION,CONTENT)
				VALUES (NULL, ?, ?, ?, ?)";
		$sth = $db->prepare($sql);
		$sth->execute(array($course_id,$account_id,$QUES,$CONT));
	}
?>