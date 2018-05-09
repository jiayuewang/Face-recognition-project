<?php
	session_save_path("./session");
	session_start();
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
	require_once("connect.php");
	$course_id=$_GET['cid'];
	$class_date=$_GET['class_date'];
	$db->exec('SET CHARACTER SET utf8');
	$db->query("SET NAMES utf8");
?>
<?php
	$account = $_SESSION['account'];
	$id = $_SESSION['id'];
	$authority = $_SESSION['authority'];
	if($id==null || $account==null || $authority!="teacher")
	{
		echo '<script>alert("You cannot set!")</script>';
		echo '<meta http-equiv="refresh" content="0;url=web_view_student.php?cid='.$course_id.'" />';
	}
	else{
		$sql = "UPDATE ATTENDANCE_RECORDS SET ATTEND = 1 WHERE C_ID = ? AND DATE = ?";
		$sth = $db->prepare($sql);
		if ($sth->execute(array($course_id,$class_date))===TRUE){
			echo '<script> confirm("Set succeed!"); </script>';
			echo '<meta http-equiv="refresh" content="0;url=web_attend_record.php?cid='.$course_id.'&class_date='.$class_date.'" />';
		}
		else{
			echo '<script> confirm("Set failed!"); </script>';
			echo '<meta http-equiv="refresh" content="0;url=web_attend_record.php?cid='.$course_id.'&class_date='.$class_date.'" />';
		} 
	}
?>