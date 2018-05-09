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
		$question_id = $_GET['qid'];
		$sql = "INSERT INTO ANSWER(AID,Q_ID,A_ID,ANSWER,ANONYMOUS)
				VALUES (NULL, ?, ?, ?, ?)";
		$sth=$db->prepare($sql);
		//echo 'QID: '.$question_id.'<br><br>';
		//echo 'AID: '.$id.'<br><br>';
		//echo 'ANSWER: '.$ANS.'<br><br>';
		if($sth->execute(array($question_id,$id,$ANS,$ANONYMOUS))===TRUE){
			echo '<script> confirm("Answer succeed!"); </script>';
			echo '<meta http-equiv="refresh" content="0;url=web_question_answer.php?qid='.$question_id.'" />';
		}
		else{
			echo '<script> confirm("Add to DB failed!"); </script>';
			echo '<meta http-equiv="refresh" content="0;url=web_add_answer.php?qid='.$question_id.'"/>'; 
		} 
		
		
	}
?>