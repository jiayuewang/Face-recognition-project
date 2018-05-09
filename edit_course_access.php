<?php
	session_save_path("./session");
	session_start();
?>
<?php
	require_once("connect.php");
	$NAME=$_POST["name"]; 
	$ID=$_POST["id"]; 
	$TIME=$_POST["time"]; 
	$TEACHER_LIST=$_POST["teacher"]." ";
	$course_id=$_GET['cid'];
	$classroom = $_POST['room'];
	//$ACCOUNT=$_POST["ACCOUNT"];
	//$APIKEY = "AIzaSyCx31vQPS8SPtFBxPbfwKlHHKfvNDTtohk";
	$APIKEY = "AIzaSyCIdd_P990NSC55edBHrKdRLZjwuvb_ZM4";
	$db->exec('SET CHARACTER SET utf8');
	$db->query("SET NAMES utf8");
?>
<?php
	if($course_id!=NULL){
		//create time_check
		$time_check="";
		$tmp = explode(" ",$TIME);
		$i=0;
		while($tmp[$i]!=NULL){
			$ttmp = str_split($tmp[$i]);
			$j=1;
			while($ttmp[$j]!=NULL){
				$time_check = $time_check.$ttmp[0].$ttmp[$j]." ";
				$j=$j+1;
			}
			$i=$i+1;
		}
		
		
		$sql = "DELETE FROM COURSE_TO_TEACHER WHERE C_ID = ?";
		$sth = $db->prepare($sql);
		$sth->execute(array($course_id));
		
		$sql = "DELETE FROM LINKIT_TO_COURSE WHERE C_ID = ?";
		$sth = $db->prepare($sql);
		$sth->execute(array($course_id));
		
		$sql = "UPDATE COURSE SET NAME = ?, ID = ?, TIME = ?, TIME_CHECK = ? WHERE CID = ?";
		$sth=$db->prepare($sql);
		if($sth->execute(array($NAME, $ID, $TIME, $time_check, $course_id))===TRUE){
			$sql = "SELECT * FROM LINKIT WHERE PLACE = ?";
			$sth = $db->prepare($sql);
			$sth->execute(array($classroom));
			while($row = $sth->fetchObject()){
				$LID = $row->LID;
				//echo $LID."<br>";
				$sql_1 = "INSERT INTO LINKIT_TO_COURSE (L_T_C_ID, L_ID, C_ID)
						VALUES (NULL, ?, ?)";
				$sth_1 = $db->prepare($sql_1);
				$sth_1->execute(array($LID, $course_id));
			}
			
			$TEACHER=explode(" ",$TEACHER_LIST);
			$one_time=0;
			foreach($TEACHER as $index => $value){
				//echo "teacher $index is: $value<br>";
				if($value!=""){
					$sql = "SELECT * FROM ACCOUNT WHERE NAME = ?";
					$sth = $db->prepare($sql);
					$sth->execute(array($value));
					$row = $sth->fetchObject();
					$AID = $row->AID;
					$sql = "INSERT INTO COURSE_TO_TEACHER (C_T_T_ID, C_ID, A_ID)
							VALUES (NULL, ?, ?)";
					$sth = $db->prepare($sql);
					if ($sth->execute(array($course_id,$AID))===TRUE){
						//echo '<script> confirm("Edit succeed!"); </script>';
						//echo '<meta http-equiv="refresh" content="0;url=web_admin_course.php" />';
					}
					else{
						echo '<script> confirm("Add to DB failed!"); </script>';
						echo '<meta http-equiv="refresh" content="0;url=web_edit_course.php?cid="'.$course_id.'""/>'; 
						
					}
				}
				else if($one_time==0){
					$one_time=1;
					echo '<script> confirm("Edit succeed!"); </script>';
					echo '<meta http-equiv="refresh" content="0;url=web_admin_course.php" />';
				}
			}
			
			
		}
		else{
			echo '<script> confirm("Edit to DB failed!"); </script>';
			echo '<meta http-equiv="refresh" content="0;url=web_edit_course.php?cid="'.$course_id.'""/>'; 
		} 
			
	}
	else{
		echo '<script> confirm("Edit failed!"); </script>';
		echo '<meta http-equiv="refresh" content="0;url=web_edit_course.php?cid="'.$course_id.'""/>'; 
	} 
?>