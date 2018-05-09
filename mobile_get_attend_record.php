<?php
	require_once("connect.php");
	$db->exec('SET CHARACTER SET utf8');
	$db->query("SET NAMES utf8");
?>
<?php
	$course_id = $_POST['course_id'];
	$class_date = $_POST['class_date'];
	
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
	
	$output_attend_record=array();
	$i=1;
	
	$sql = "SELECT * FROM ACCOUNT WHERE AID = 
			ANY(SELECT A_ID FROM ATTENDANCE_RECORDS WHERE DATE = ? AND C_ID = ?) ORDER BY NAME";
	$sth = $db->prepare($sql);
	$sth->execute(array($class_date,$course_id));
	while($row = $sth->fetchObject()){
		$sql_s = "SELECT * FROM ATTENDANCE_RECORDS WHERE A_ID = ? AND C_ID = ? AND DATE = ?";
		$sth_s = $db->prepare($sql_s);
		$sth_s->execute(array($row->AID,$course_id,$class_date));
		$row_s=$sth_s->fetchObject();
		
		$output_attend_record[$i]['NAME']=$row->NAME;
		$output_attend_record[$i]['REALNAME']=$row->REALNAME;
		$output_attend_record[$i]['ATTEND']=$row_s->ATTEND;
		$output_attend_record[$i]['TIME']=$row->TIME;
		$i++;
	}
	if($i!==1) echo json_encode($output_attend_record);
	else{
		$output_attend_record[$i]['NAME']=" ";
		$output_attend_record[$i]['REALNAME']=" ";
		$output_attend_record[$i]['ATTEND']=" ";
		$output_attend_record[$i]['TIME']=" ";
		echo json_encode($output_attend_record);
	}
	
?>