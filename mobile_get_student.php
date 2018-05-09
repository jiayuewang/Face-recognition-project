<?php
	require_once("connect.php");
	$db->exec('SET CHARACTER SET utf8');
	$db->query("SET NAMES utf8");
?>
<?php
	$output_student=array();
	$i=1;
	$course_id = $_POST['course_id'];
	$sql = "SELECT * FROM COURSE WHERE ID = ?";
	$sth = $db->prepare($sql);
	$sth->execute(array($course_id));
	$row = $sth->fetchObject();
	$course_id = $row->CID;
	
	//check class day or not
	date_default_timezone_set('Asia/Taipei');
	$day = date('w');
	$day = "%".$day."%";
	$sql = "SELECT * FROM COURSE WHERE CID = ? AND TIME LIKE ?";
	$sth = $db->prepare($sql);
	$sth->execute(array($course_id,$day));
	$row = $sth->fetchObject();
	$class_date;
	if($row==NULL){//not on class day
		$sql = "SELECT MAX(DATE) FROM ATTENDANCE_RECORDS WHERE C_ID = ?";
		$sth = $db->prepare($sql);
		$sth->execute(array($course_id));
		$row1 = $sth->fetchObject();
		if($row1==NULL){
			$class_date=NULL;
		}
		else{
			$class_date=$row1->DATE;
		}
	}
	else{//class day
		$date = date('Y-m-d');
		$sql = "SELECT * FROM ATTENDANCE_RECORDS WHERE C_ID = ? AND DATE = ?";
		$sth = $db->prepare($sql);
		$sth->execute(array($course_id,$date));
		$row2 = $sth->fetchObject();
		if($row2==NULL){ //need to create table
			$sql = "SELECT * FROM COURSE_TO_STUDENT WHERE C_ID = ?";
			$sth = $db->prepare($sql);
			$sth->execute(array($course_id));
			while($row3 = $sth->fetchObject()){
				$sql = "INSERT INTO ATTENDANCE_RECORDS (A_R_ID, C_ID, A_ID, ATTEND, DATE, TIME)
						VALUES (NULL, ?, ?, 0, ?, NULL)";
				$sth1 = $db->prepare($sql);
				$sth1->execute(array($row3->C_ID,$row3->A_ID,$date));
			}
			$class_date=$date;
		}
		else{
			$class_date=$date;
		}
	}
	
	
	
	$sql = "SELECT * FROM ACCOUNT WHERE AID = 
			ANY(SELECT A_ID FROM COURSE_TO_STUDENT WHERE C_ID = ?) ORDER BY NAME";
	$sth = $db->prepare($sql);
	$sth->execute(array($course_id));
	while($row = $sth->fetchObject()){
		$sql_s = "SELECT * FROM COURSE_TO_STUDENT WHERE A_ID = ? AND C_ID = ?";
		$sth_s = $db->prepare($sql_s);
		$sth_s->execute(array($row->AID,$course_id));
		$row_s=$sth_s->fetchObject();
		
		//$ATTEND="";
		//if($row_s->ATTEND==1) $ATTEND=1;
		//else $ATTEND=0;
		//count total attend time
		$sql_c = "SELECT * FROM ATTENDANCE_RECORDS WHERE A_ID = ? AND C_ID = ?";
		$sth_c = $db->prepare($sql_c);
		$sth_c->execute(array($row->AID,$course_id));
		$TOTAL_N=$sth_c->rowCount();
		
		$sql_c = "SELECT * FROM ATTENDANCE_RECORDS WHERE A_ID = ? AND C_ID = ? AND ATTEND = 1";
		$sth_c = $db->prepare($sql_c);
		$sth_c->execute(array($row->AID,$course_id));
		$ATTEND_N=$sth_c->rowCount();
		
		$output_student[$i]['NAME']=$row->NAME;
		$output_student[$i]['REALNAME']=$row->REALNAME;
		//$output_student[$i]['ATTEND']=$row_s->ATTEND;
		$output_student[$i]['ATTEND_TIME']=$ATTEND_N;
		$output_student[$i]['TOTAL_TIME']=$TOTAL_N;
		$output_student[$i]['CLASS_DATE']=$class_date;
		$i++;
	}
	if($i!==1) echo json_encode($output_student);
	else{
		$output_student[$i]['NAME']=" ";
		$output_student[$i]['REALNAME']=" ";
		//$output_student[$i]['ATTEND']=" ";
		$output_student[$i]['ATTEND_TIME']=" ";
		$output_student[$i]['TOTAL_TIME']=" ";
		$output_student[$i]['CLASS_DATE']=" ";
		echo json_encode($output_student);
	}
?>