<?php
	session_save_path("./session");
	session_start();
?>
<?php
	require_once("connect.php");
	$ANS=$_POST["answer"]; 
	$ANONYMOUS=$_POST["anonymous"];
	//$ACCOUNT=$_POST["ACCOUNT"];
	//$APIKEY = "AIzaSyCx31vQPS8SPtFBxPbfwKlHHKfvNDTtohk";
	$APIKEY = "AIzaSyCIdd_P990NSC55edBHrKdRLZjwuvb_ZM4";
	$db->exec('SET CHARACTER SET utf8');
	$db->query("SET NAMES utf8");
?>
<?
	if($ANS!=NULL){
		if($ANONYMOUS=='on') $ANONYMOUS = 1;
		else $ANONYMOUS = 0;
		$account = $_SESSION['account'];
		$id = $_SESSION['id'];
		$authority = $_SESSION['authority'];
		$answer_id = $_GET['aid'];
		
		$sql = "SELECT * FROM ANSWER WHERE AID = ?";
		$sth = $db->prepare($sql);
		$sth->execute(array($answer_id));
		$row = $sth->fetchObject();
		$question_id = $row->Q_ID;
		
		$sql = "UPDATE ANSWER SET ANSWER = ?, ANONYMOUS = ? WHERE AID = ?";
		$sth=$db->prepare($sql);
		//echo 'QID: '.$QUES.'<br><br>';
		//echo 'CID: '.$course_id.'<br><br>';
		//echo 'AID: '.$id.'<br><br>';
		if($sth->execute(array($ANS,$ANONYMOUS,$answer_id))===TRUE){
			echo '<script> confirm("Edit succeed!"); </script>';
			echo '<meta http-equiv="refresh" content="0;url=web_question_answer.php?qid='.$question_id.'" />';
		}
		else{
			echo '<script> confirm("Edit to DB failed!"); </script>';
			echo '<meta http-equiv="refresh" content="0;url=web_edit_answer.php?aid='.$answer_id.'"/>'; 
		} 
		
		
	}
?>