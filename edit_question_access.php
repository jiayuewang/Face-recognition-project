<?php
	session_save_path("./session");
	session_start();
?>
<?php
	require_once("connect.php");
	$QUES=$_POST["question"]; 
	$CONT=$_POST["content"];
	$COMM=$_POST["comment"];
	$ANONYMOUS=$_POST["anonymous"];
	//$ACCOUNT=$_POST["ACCOUNT"];
	//$APIKEY = "AIzaSyCx31vQPS8SPtFBxPbfwKlHHKfvNDTtohk";
	$APIKEY = "AIzaSyCIdd_P990NSC55edBHrKdRLZjwuvb_ZM4";
	$db->exec('SET CHARACTER SET utf8');
	$db->query("SET NAMES utf8");
?>
<?
	if($QUES!=NULL){
		if($ANONYMOUS=='on') $ANONYMOUS = 1;
		else $ANONYMOUS = 0;
		
		$account = $_SESSION['account'];
		$id = $_SESSION['id'];
		$authority = $_SESSION['authority'];
		$question_id = $_GET['qid'];
		$sql = "SELECT * FROM QUESTION WHERE QID = ?";
		$sth = $db->prepare($sql);
		$sth->execute(array($question_id));
		$row = $sth->fetchObject();
		$course_id = $row->C_ID;
		
		$sql = "UPDATE QUESTION SET QUESTION = ?, CONTENT = ?, COMMENT = ?, ANONYMOUS = ? WHERE QID = ?";
		$sth=$db->prepare($sql);
		//echo 'QID: '.$QUES.'<br><br>';
		//echo 'CID: '.$course_id.'<br><br>';
		//echo 'AID: '.$id.'<br><br>';
		if($sth->execute(array($QUES,$CONT,$COMM,$ANONYMOUS,$question_id))===TRUE){
			echo '<script> confirm("Edit succeed!"); </script>';
			echo '<meta http-equiv="refresh" content="0;url=web_course_question.php?cid='.$course_id.'" />';
		}
		else{
			echo '<script> confirm("Edit to DB failed!"); </script>';
			echo '<meta http-equiv="refresh" content="0;url=web_edit_question.php?cid='.$course_id.'"/>'; 
		} 
		
		
	}
?>