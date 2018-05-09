<?php
	session_save_path("./session");
	session_start();
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
	require_once("connect.php");
	$db->exec('SET CHARACTER SET utf8');
	$db->query("SET NAMES utf8");
?>
<?php
	/*insert pet*/
	$account = $_SESSION['account'];
	$id = $_SESSION['id'];
	$authority = $_SESSION['authority'];
	if($id==null || $account==null)
	{
		echo '<script>alert("You cannot add course!")</script>';
		echo '<meta http-equiv="refresh" content="0;url=administrator.php" />';
	}
	else{
		$name = $_POST['name'];
		$id = $_POST['id'];
		$time = $_POST['time'];
		$teacher = $_POST['teacher'];
		$classroom = $_POST['room'];
		//create time_check
		$time_check="";
		$tmp = explode(" ",$time);
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
		//echo $time_check;
		if($name!=NULL && $id!=NULL && $time!=NULL && $teacher!=NULL && $classroom!=NULL){
			$sql = "SELECT * FROM ACCOUNT WHERE NAME = ?";
			$sth = $db->prepare($sql);
			$sth->execute(array($teacher));
			$row = $sth->fetchObject();
			$AID = $row->AID;
	        $AUTHO = $row->AUTHORITY;
			if($AUTHO=="teacher"){
			
				
				$sql = "SELECT NAME FROM COURSE WHERE ID = ?";
				$sth = $db->prepare($sql);
				$sth->execute(array($id));
				$row = $sth->fetchObject();
				//$result = mysqli_query($con, $sql);
				//$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
				if($row->NAME == NULL){
					$sql = "INSERT INTO COURSE (CID, NAME, ID, TIME, TIME_CHECK) 
							VALUES (NULL, ?,?,?,?) ON DUPLICATE KEY UPDATE CID=CID";
							
					$sth = $db->prepare($sql);
					$sth->execute(array($name,$id,$time,$time_check));
					//mysqli_query($con, $sql);
					//if (mysqli_query($con, $sql)===TRUE)
					//	echo "success<br>";
					//else echo "failed<br>";
				}
				/*insert into COURSE_TO_TEACHER*/
				$sql = "SELECT CID FROM COURSE WHERE ID = ?";
				$sth = $db->prepare($sql);
				$sth->execute(array($id));
				$row = $sth->fetchObject();
				//$result = mysqli_query($con, $sql);
				//$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
				$CID = $row->CID;
				
				$sql = "SELECT * FROM LINKIT WHERE PLACE = ?";
				$sth = $db->prepare($sql);
				$sth->execute(array($classroom));
				while($row = $sth->fetchObject()){
					$LID = $row->LID;
					
					$sql_1 = "INSERT INTO LINKIT_TO_COURSE (L_T_C_ID, L_ID, C_ID)
							VALUES (NULL, ?, ?)";
					$sth_1 = $db->prepare($sql_1);
					$sth_1->execute(array($LID, $CID));
				}
				
				$sql = "INSERT INTO COURSE_TO_TEACHER (C_T_T_ID, C_ID, A_ID)
						VALUES (NULL, ?, ?)";
				$sth = $db->prepare($sql);
				
				
				if ($sth->execute(array($CID,$AID))=== TRUE){
					echo '<script> confirm("Add succeed!"); </script>';
					echo '<meta http-equiv="refresh" content="0;url=web_admin_course.php" />';
				}
				else{
					echo '<script> confirm("Add failed!"); </script>';
					echo '<meta http-equiv="refresh" content="0;url=web_add_course.php"/>'; 
				} 
			}
			else{
				echo '<script> confirm("Teacher Account Wrong!"); </script>';
				echo '<meta http-equiv="refresh" content="0;url=web_add_course.php"/>'; 
			}
			
		}
		else{
			echo '<script> confirm("You have to fill in everything!"); </script>';
			echo '<meta http-equiv="refresh" content="0;url=web_add_course.php"/>'; 
		}
	}
?>