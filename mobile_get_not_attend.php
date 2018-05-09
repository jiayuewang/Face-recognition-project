<?php
	require_once("connect.php");
	$db->exec('SET CHARACTER SET utf8');
	$db->query("SET NAMES utf8");
?>
<?php
	$course_id = $_POST['course_id'];
	$student_id = $_POST['student_name'];
	
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
	
	$output_not_attend=array();
	$i=1;
	
	$sql = "SELECT * FROM ATTENDANCE_RECORDS WHERE A_ID = ? AND C_ID = ? AND ATTEND = 0 ORDER BY DATE";
	$sth = $db->prepare($sql);
	$sth->execute(array($student_id,$course_id));
	while($row = $sth->fetchObject()){
		$output_not_attend[$i]['DATE']=$row->DATE;
		$i++;
	}
	if($i!==1) echo json_encode($output_not_attend);
	else{
		$output_not_attend[$i]['DATE']=" ";
		echo json_encode($output_not_attend);
	}
	
	
?>