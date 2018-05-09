<?php 
	session_save_path("./session");
	session_start(); 
?>

<?php
	$ACCOUNT=$_POST["ACCOUNT"]; //mobile
	$PASSWORD=$_POST["PASSWORD"]; //mobile
	$PHONE=$_POST["PHONE"]; //mobile
	
	$USERNAME = $_POST["web_account"]; //web
	$PW = $_POST["web_password"]; //web
	$ADMIN = $_POST["admin_account"]; //admin
	$ADMIN_PW = $_POST["admin_password"]; //admin
	require_once("connect.php");
	$db->exec('SET CHARACTER SET utf8');
	$db->query("SET NAMES utf8");
?>
<?php
	//echo "MySQL database connected<br>";
	if($ACCOUNT!=NULL){
		/*check account*/
		check_account($ACCOUNT,$PASSWORD,$PHONE,$db);
	}
	else if($USERNAME!=NULL){
		/*website check account*/
		web_check_account($USERNAME, $PW,$db);
	}
	else if($ADMIN!=NULL){
		/*login admin account*/
		admin_check_account($ADMIN, $ADMIN_PW, $db);
	}
	else{
		echo '<script> confirm("Login Failed!"); </script>';
		echo '<meta http-equiv="refresh" content="0;url=web_login.php"/>';
	}
?>
<?
	function admin_check_account($A,$P,$db){
		$sql = "SELECT * FROM ACCOUNT WHERE NAME = ? AND PASSWORD = ? AND AUTHORITY = 'administrator'";
		$sth = $db->prepare($sql);
		$sth->execute(array($A,$P));
		$row = $sth->fetchObject();
		if($row == NULL){
			echo '<script> confirm("Login Failed!"); </script>';
			echo '<meta http-equiv="refresh" content="0;url=web_admin_login.php"/>';    
		}
		else{
			$_SESSION['account']=$A;
			$_SESSION['id']=$row->AID;
			$_SESSION['authority']=$row->AUTHORITY;
			if($row->AUTHORITY=="administrator"){
				echo '<meta http-equiv="refresh" content="0;url=administrator.php"/>';
			}
			else{
				echo '<script> confirm("Login Failed! You are not administrator!"); </script>';
				echo '<meta http-equiv="refresh" content="0;url=web_login.php"/>';    
			}
		}
	}

?>
<?php
	function web_check_account($U,$P,$db){
		$sql = "SELECT * FROM ACCOUNT WHERE NAME = ? AND PASSWORD = ?";
		$sth = $db->prepare($sql);
		$sth->execute(array($U,$P));
		$row = $sth->fetchObject();
		
		if($row == NULL){
			echo '<script> confirm("Login Failed!"); </script>';
			echo '<meta http-equiv="refresh" content="0;url=web_login.php"/>';    
		}
		else{
			$_SESSION['account']=$U;
			$_SESSION['id']=$row->AID;
			$_SESSION['authority']=$row->AUTHORITY;
			if($row->AUTHORITY=="teacher"){
				echo '<meta http-equiv="refresh" content="0;url=teacher.php"/>';
			}
			else if($row->AUTHORITY=="student"){
				echo '<meta http-equiv="refresh" content="0;url=student.php"/>';
			}
			else{
				echo '<script> confirm("Login Failed! You are not teacher or student!"); </script>';
				echo '<meta http-equiv="refresh" content="0;url=web_login.php"/>';    
			}
		}
	}
?>
<?php
	function check_account($A,$P,$OP,$db){
		/*check if ACCOUNT exist*/
		//$APIKEY = "AIzaSyCx31vQPS8SPtFBxPbfwKlHHKfvNDTtohk";
		$APIKEY = "AIzaSyCIdd_P990NSC55edBHrKdRLZjwuvb_ZM4";
		$success = 0;
		$sql = "SELECT * FROM ACCOUNT WHERE NAME = ? AND PASSWORD = ?";
		$sth = $db->prepare($sql);
		$sth->execute(array($A,$P));
		$row = $sth->fetchObject();
		//$result = mysqli_query($C, $sql);
		//$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		if($row == NULL){
			$success = 0;
			//echo "This ACCOUNT is not exist.<br>";
		}
		else{
			/*connect account to owner(phone)*/
			/*check if the ophone exists*/
			$sql = "SELECT * FROM PHONE WHERE PHONE = ?";
			$sth = $db->prepare($sql);
			$sth->execute(array($OP));
			$row = $sth->fetchObject();	
			//$result = mysqli_query($C, $sql);
			//$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
			if($row == NULL){
				$sql = "INSERT INTO PHONE (PID, PHONE)
						VALUES (NULL, ?)";
				$sth = $db->prepare($sql);
				if($sth->execute(array($OP)) === TRUE);
				//	echo "success insert owners<br>";
				//else echo "failed<br>";
			}
			/*insert into ACCOUNT_TO_PHONE*/
			$sql = "SELECT AID FROM ACCOUNT WHERE NAME = ?";
			$sth = $db->prepare($sql);
			$sth->execute(array($A));
			$row = $sth->fetchObject();
			//$result = mysqli_query($C, $sql);
			//$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
			$AID = $row->AID;
			
			$sql = "SELECT PID FROM PHONE WHERE PHONE = ?";
			$sth = $db->prepare($sql);
			$sth->execute(array($OP));
			$row = $sth->fetchObject();
			//$result = mysqli_query($C, $sql);
			//$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
			$PID = $row->PID;
			$sql = "SELECT * FROM ACCOUNT_TO_PHONE WHERE A_ID=? AND P_ID=?";
			$sth = $db->prepare($sql);
			$sth->execute(array($AID,$PID));
			$row = $sth->fetchObject();
			if($row == NULL){
				$sql = "INSERT INTO ACCOUNT_TO_PHONE (A_T_P_ID, A_ID, P_ID)
						VALUES (NULL, ?, ?)";
				$sth = $db->prepare($sql);
				if($sth->execute(array($AID,$PID)) === TRUE){
					$success = 1;
					//echo "success insert account_to_owners<br>";
				}
				//else echo "failed<br>";
			}
			else $success = 1;
		}
		$AUTHORITY=" ";
		$sql = "SELECT AUTHORITY FROM ACCOUNT WHERE NAME = ?";
		$sth = $db->prepare($sql);
		$sth->execute(array($A));
		$row = $sth->fetchObject();
		//$result = mysqli_query($C, $sql);
		//$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		if($row !== NULL) $AUTHORITY = $row->AUTHORITY;
		
		$i=1;
		$output=array();
		$output[$i]["success"]=$success;
		$output[$i]["AUTHORITY"]=$AUTHORITY;
		echo json_encode($output);
		
		
		/*$aRegID = array();
		array_push($aRegID, $OP);
		
		//Set POST variables
		$url = 'http://android.googleapis.com/gcm/send';
		$fields = array('registration_ids' => $aRegID,
						'data'			   => array( 'success' => $success,
													 'AUTHORITY' => $AUTHORITY )
						);
		$headers = array('Content-Type: application/json',
						 'Authorization: key='.$APIKEY
						 );
		//Open connection
		$ch = curl_init();
		//Set the url, number of POST vars, POST data
		curl_setopt( $ch, CURLOPT_URL, $url);
		curl_setopt( $ch, CURLOPT_POST, true);
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($fields));
		
		//Send post, and wait for the response, and store in $result
		$result = curl_exec($ch);
		
		$aGCMresult = json_decode($result,true);
		$aUnregID = $aGCMresult['results'];
		$unregcnt = count($aUnregID);
		for($i=0;$i<$unregcnt;$i++){
			$aErr = $aUnregID[$i];
			if($aErr['error']=='NotRegistered'){
				$sql1 = "DELETE FROM OWNERS WHERE PHONE = '".$aRegID[$i]."'";
				$result1 = mysqli_query($C, $sql1);
			}
		}
		//Close connection
		curl_close($ch);
		//GCM end
		unset($aRegID);*/
		
		//if($success == 1)	echo "LOGIN!!!<br>";
	}
?>