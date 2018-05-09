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
		$course_id = $_GET['cid'];
		$sql = "INSERT INTO QUESTION(QID,C_ID,A_ID,QUESTION,CONTENT,COMMENT,ANONYMOUS)
				VALUES (NULL, ?, ?, ?, ?, ?, ?)";
		$sth=$db->prepare($sql);
		//echo 'QID: '.$QUES.'<br><br>';
		//echo 'CID: '.$course_id.'<br><br>';
		//echo 'AID: '.$id.'<br><br>';
		if($sth->execute(array($course_id,$id,$QUES,$CONT,$COMM,$ANONYMOUS))===TRUE){
			echo '<script> confirm("Ask succeed!"); </script>';
			echo '<meta http-equiv="refresh" content="0;url=web_course_question.php?cid='.$course_id.'" />';
		}
		else{
			echo '<script> confirm("Add to DB failed!"); </script>';
			echo '<meta http-equiv="refresh" content="0;url=web_add_question.php?cid='.$course_id.'"/>'; 
		} 
		
		
	}
?>