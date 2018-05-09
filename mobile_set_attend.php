<?php
	session_save_path("./session");
	session_start();
?>
<!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />-->
<?php
	require_once("connect.php");
	$student_id=$_POST['student_name'];
	$course_id=$_POST['course_id'];
	$class_date=$_POST['class_date'];
	$db->exec('SET CHARACTER SET utf8');
	$db->query("SET NAMES utf8");
?>
<?php
	$sql = "SELECT * FROM ACCOUNT WHERE NAME = ?";
	$sth = $db->prepare($sql);
	$sth->execute(array($student_id));
	$row = $sth->fetchObject();
	$student_id = $row->AID;
	
	$sql = "SELECT * FROM COURSE WHERE ID = ?";
	$sth = $db->prepare($sql);
	$sth->execute(array($course_id));
	$row = $sth->fetchObject();
	$course_id = $row->CID;
	
	$sql = "UPDATE ATTENDANCE_RECORDS SET ATTEND = 1 WHERE A_ID = ? AND C_ID = ? AND DATE = ?";
	$sth = $db->prepare($sql);
	$sth->execute(array($student_id,$course_id,$class_date));
?>