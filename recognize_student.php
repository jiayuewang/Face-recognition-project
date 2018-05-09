<?php
	require_once("connect.php");
	$db->exec('SET CHARACTER SET utf8');
	$db->query("SET NAMES utf8");
	//$PICTURE=$_GET["url"];
	$ID=$_POST["ID"];
	$LID=$_POST["LID"];
?>
<?php //TIME!
	$M = "06:50:00";
	$N = "07:50:00";
	$A = "08:50:00";
	$B = "09:50:00";
	$C = "11:00:00";
	$D = "12:00:00";
	$X = "13:10:00";
	$E = "14:10:00";
	$F = "15:10:00";
	$G = "16:20:00";
	$H = "17:20:00";
	$Y = "18:20:00";
	$I = "19:20:00";
	$J = "20:20:00";
	$K = "21:20:00";
	$L = "22:20:00";
	$time_array = array($M,$N,$A,$B,$C,$D,$X,$E,$F,$G,$H,$Y,$I,$J,$K,$L);
	$time_name_array = array("M","N","A","B","C","D","X","E","F","G","H","Y","I","J","K","L");
	//var_dump($time_array);
?>
<?php
	//Check Time First
	//06:00<=M<=06:50, 07:00<=N<=07:50, 08:00<=A<=08:50
	//09:00<=B<=09:50, 10:10<=C<=11:00, 11:10<=D<=12:00
	//12:20<=X<=13:10, 13:20<=E<=14:10, 14:20<=F<=15:10
	//15:30<=G<=16:20, 16:30<=H<=17:20, 17:30<=Y<=18:20
	//18:30<=I<=19:20, 19:30<=J<=20:20, 20:30<=K<=21:20, 21:30<=L<=22:20
	date_default_timezone_set('Asia/Taipei');
	$call_time = date('H:i:s'); // date('Y-m-d H:i:s');
	$day = date('w');
	//echo $call_time."<br>".$day."<br>";
	$class_time;
	//create class time
	$index = 15;
	while($index>=0){
		if($call_time <= $time_array[$index]){
			$class_time = "%".$day.$time_name_array[$index]."%";
		}
		$index = $index-1;
	}
	//echo $class_time."<br>";
	
	if($ID!=NULL&&$LID!=NULL){
		$sql = "SELECT * FROM ACCOUNT WHERE ID = ?";
		$sth=$db->prepare($sql);
		$sth->execute(array($ID));
		if($row = $sth->fetchObject()){
			$filename=$_FILES['image']['name'];
			$tmpname=$_FILES['image']['tmp_name'];
			$filetype=$_FILES['image']['type'];
			$filesize=$_FILES['image']['size'];    
			
			$uploaddir = './temp_photo/';
			$uploadfile = $uploaddir.basename($filename);
			$jpeg_image = 'image/jpeg';
			
			if($filetype!=$jpeg_image&&$tmpname!=NULL){
				echo '<script> confirm("Please choose a jpeg image!"); </script>';
			}
			else{
				if(move_uploaded_file($tmpname, $uploadfile)){ //iconv("utf-8","big5",$uploadfile))){
					$PICTURE="http://people.cs.nctu.edu.tw/~tihlin/view_temp_image.php?image=".$filename."";
					
					
					//echo "http://api.skybiometry.com/fc/faces/recognize.json?api_key=e3ea2b7a800243e7a31e300e814707f9&api_secret=b6b0e9982224429bbd46101ad359be7e&urls=".$PICTURE."&uids=".$row->NAME."@StudentFace<br><br>";
					$URL = "http://api.skybiometry.com/fc/faces/recognize.json?api_key=e3ea2b7a800243e7a31e300e814707f9&api_secret=b6b0e9982224429bbd46101ad359be7e&urls=".$PICTURE."&uids=".$row->NAME."@StudentFace";
					$data = file_get_contents($URL);
					$decode_data = json_decode($data);
					//echo $data;
					echo "SAME PERSON CONFIDENCE : ".$decode_data->photos[0]->tags[0]->uids[0]->confidence;
					if($decode_data->photos[0]->tags[0]->uids[0]->confidence >= 50){
						//roll call success
						//get AID
						$sql = "SELECT * FROM ACCOUNT WHERE ID = ?";
						$sth = $db->prepare($sql);
						$sth->execute(array($ID));
						$row = $sth->fetchObject();
						$AID = $row->AID;
						//get CID from COURSE
						//$sql = "SELECT * FROM COURSE WHERE TIME_CHECK LIKE ?";
						//$sth = $db->prepare($sql);
						//$sth->execute(array($class_time));
						//echo "<br>CID:<br>";
						//while ($row = $sth->fetchObject())
						//	echo $row->CID."<br>";
						//
						//$sql = "SELECT * FROM COURSE WHERE CID = ANY(SELECT C_ID FROM LINKIT_TO_COURSE WHERE L_ID = 
						//		(SELECT LID FROM LINKIT WHERE NAME = ?))";
						//$sth = $db->prepare($sql);
						//$sth->execute(array($LID));
						//echo "<br>C_ID:<br>";
						//while ($row = $sth->fetchObject())
						//	echo $row->CID."<br>";
						$sql = "SELECT * FROM COURSE WHERE TIME_CHECK LIKE ?
								AND CID = ANY(SELECT C_ID FROM LINKIT_TO_COURSE WHERE L_ID = 
								(SELECT LID FROM LINKIT WHERE NAME = ?))";
						$sth = $db->prepare($sql);
						$sth->execute(array($class_time, $LID));
						$row = $sth->fetchObject();
						if($row!=NULL){
							$CID = $row->CID;
							//echo "CID : ".$CID."<br>";
							//make student attend
							//check if attendance_records exists
							$date = date('Y-m-d');
							$sql = "SELECT * FROM ATTENDANCE_RECORDS WHERE C_ID = ? AND DATE = ?";
							$sth = $db->prepare($sql);
							$sth->execute(array($CID,$date));
							$row2 = $sth->fetchObject();
							if($row2==NULL){ //need to create table
								$sql = "SELECT * FROM COURSE_TO_STUDENT WHERE C_ID = ?";
								$sth = $db->prepare($sql);
								$sth->execute(array($CID));
								while($row3 = $sth->fetchObject()){
									$sql = "INSERT INTO ATTENDANCE_RECORDS (A_R_ID, C_ID, A_ID, ATTEND, DATE, TIME)
											VALUES (NULL, ?, ?, 0, ?, NULL)";
									$sth1 = $db->prepare($sql);
									$sth1->execute(array($row3->C_ID,$row3->A_ID,$date));
								}
							}
							$sql = "SELECT * FROM ATTENDANCE_RECORDS WHERE ATTEND = 1 AND A_ID = ? AND C_ID = ? AND DATE = ?";
							$sth = $db->prepare($sql);
							$sth->execute(array($AID,$CID,$date));
							$row_check = $sth->fetchObject();
							if($row_check->TIME==NULL){
								$attend_time = date('H:i:s');
								$sql = "UPDATE ATTENDANCE_RECORDS SET ATTEND = 1, TIME = ? WHERE A_ID = ? AND C_ID = ? AND DATE = ?";
								$sth = $db->prepare($sql);
								if ($sth->execute(array($attend_time,$AID,$CID,$date))===TRUE){
									echo '<script> confirm("Success!"); </script>';
									echo '<meta http-equiv="refresh" content="0;url=web_recognize_student.php"/>'; 
								}
								else{
									echo '<script> confirm("Failed!"); </script>';
									echo '<meta http-equiv="refresh" content="0;url=web_recognize_student.php"/>'; 
								}
							}
							else{
								echo '<script> confirm("Already Attend!"); </script>';
								echo '<meta http-equiv="refresh" content="0;url=web_recognize_student.php"/>'; 
							}
						}
						else{
							echo '<script> confirm("Class not exist!"); </script>';
							echo '<meta http-equiv="refresh" content="0;url=web_recognize_student.php"/>'; 
						}
					}
					else{
						$sql = "SELECT * FROM ACCOUNT WHERE ID = ?";
						$sth=$db->prepare($sql);
						$sth->execute(array($ID));
						$row = $sth->fetchObject();
						echo '<script> confirm("You are not '.$row->NAME.'!"); </script>';
						echo '<meta http-equiv="refresh" content="0;url=web_recognize_student.php"/>'; 
					}
				}
				else{
					echo '<script> confirm("Failed!"); </script>';
					echo '<meta http-equiv="refresh" content="0;url=web_recognize_student.php"/>'; 
				} 
			}
			////echo $PICTURE."<br>";
			////echo $ID."<br>";
			////echo "http://api.skybiometry.com/fc/faces/recognize.json?api_key=e3ea2b7a800243e7a31e300e814707f9&api_secret=b6b0e9982224429bbd46101ad359be7e&urls=".$PICTURE."&uids=".$row->NAME."@StudentFace<br><br>";
			//$URL = "http://api.skybiometry.com/fc/faces/recognize.json?api_key=e3ea2b7a800243e7a31e300e814707f9&api_secret=b6b0e9982224429bbd46101ad359be7e&urls=".$PICTURE."&uids=".$row->NAME."@StudentFace";
			//$data = file_get_contents($URL);
			//$decode_data = json_decode($data);
			////echo $data;
			//echo "SAME PERSON CONFIDENCE : ".$decode_data->photos[0]->tags[0]->uids[0]->confidence;
		}
	}
?>