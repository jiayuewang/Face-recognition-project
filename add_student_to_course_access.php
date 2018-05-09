<?php
	session_save_path("./session");
	session_start();
?>
<?php
	require_once("connect.php");
	$NAME=$_POST["name"];
	$course_id=$_GET['cid'];
	//$ACCOUNT=$_POST["ACCOUNT"];
	//$APIKEY = "AIzaSyCx31vQPS8SPtFBxPbfwKlHHKfvNDTtohk";
	$APIKEY = "AIzaSyCIdd_P990NSC55edBHrKdRLZjwuvb_ZM4";
	$db->exec('SET CHARACTER SET utf8');
	$db->query("SET NAMES utf8");
?>
<?
	if($NAME!=NULL){
		
		$sql = "SELECT * FROM ACCOUNT WHERE NAME = ?";
		$sth = $db->prepare($sql);
		$sth->execute(array($NAME));
		$row = $sth->fetchObject();
		
		if($row->AUTHORITY=="student"){
			$AID = $row->AID;
			$sql = "SELECT * FROM COURSE_TO_STUDENT WHERE A_ID = ? AND C_ID = ?";
			$sth = $db->prepare($sql);
			$sth->execute(array($AID, $course_id));
			$row = $sth->fetchObject();
			
			if($row == NULL){
				$sql = "INSERT INTO COURSE_TO_STUDENT (C_T_S_ID, C_ID, A_ID)
						VALUES (NULL, ?, ?)";
				$sth = $db->prepare($sql);
				if ($sth->execute(array($course_id,$AID))=== TRUE){
					echo '<script> confirm("Add succeed!"); </script>';
					echo '<meta http-equiv="refresh" content="0;url=web_course_student.php?cid='.$course_id.'"/>';
				}
				else{
					echo '<script> confirm("Add failed!"); </script>';
					echo '<meta http-equiv="refresh" content="0;url=web_add_student_to_course.php?cid='.$course_id.'"/>'; 
				} 
				
			}
			else{
				echo '<script> confirm("Student already in!"); </script>';
				echo '<meta http-equiv="refresh" content="0;url=web_add_student_to_course.php?cid='.$course_id.'"/>'; 
				
			}
		}
		else{
			echo '<script> confirm("Invalid student ID!"); </script>';
			echo '<meta http-equiv="refresh" content="0;url=web_add_student_to_course.php?cid='.$course_id.'"/>'; 
		}
	}
	else{
		echo '<script> confirm("Please input student ID!"); </script>';
		echo '<meta http-equiv="refresh" content="0;url=web_add_student_to_course.php?cid='.$course_id.'"/>'; 
	} 
?>