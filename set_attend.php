<?php
	session_save_path("./session");
	session_start();
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
	require_once("connect.php");
	$student_id=$_GET['aid'];
	$course_id=$_GET['cid'];
	$class_date=$_GET['class_date'];
	$attend = $_GET['attend'];
	$db->exec('SET CHARACTER SET utf8');
	$db->query("SET NAMES utf8");
?>
<?php
//echo 'SET ATTEND! <br>';
	$account = $_SESSION['account'];
	$id = $_SESSION['id'];
	$authority = $_SESSION['authority'];
	if($id==null || $account==null || $authority!="teacher")
	{
		echo '<script>alert("You cannot set!")</script>';
		echo '<meta http-equiv="refresh" content="0;url=web_attend_record.php?cid='.$course_id.'&class_date='.$class_date.'" />';
	}
	else{
		$sql = "UPDATE ATTENDANCE_RECORDS SET ATTEND = 1 WHERE A_ID = ? AND C_ID = ? AND DATE = ?";
		$sth = $db->prepare($sql);
		if ($sth->execute(array($student_id,$course_id,$class_date))===TRUE){
			if($attend==2){
				echo '<script> confirm("Set succeed!"); </script>';
				echo '<meta http-equiv="refresh" content="0;url=web_one_attend_record.php?cid='.$course_id.'&aid='.$student_id.'" />';
			}
			else{
				echo '<script> confirm("Set succeed!"); </script>';
				echo '<meta http-equiv="refresh" content="0;url=web_attend_record.php?cid='.$course_id.'&class_date='.$class_date.'" />';
			}
		}
		else{
			if($attend==2){
				echo '<script> confirm("Set failed!"); </script>';
				echo '<meta http-equiv="refresh" content="0;url=web_one_attend_record.php?cid='.$course_id.'&aid='.$student_id.'" />';
			}
			else{
				echo '<script> confirm("Set failed!"); </script>';
				echo '<meta http-equiv="refresh" content="0;url=web_attend_record.php?cid='.$course_id.'&class_date='.$class_date.'" />';
			}
		} 
	}
?>