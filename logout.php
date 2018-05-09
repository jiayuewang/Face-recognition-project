<?php
	require_once("connect.php");
	$ACCOUNT=$_POST["ACCOUNT"];
	$PHONE=$_POST["PHONE"];
	$db->exec('SET CHARACTER SET utf8');
	$db->query("SET NAMES utf8");
?>
<?php
	
	//echo "MySQL database connected<br>";
	if($ACCOUNT!=NULL){
		/*insert to account*/
		delete_account_to_phone($ACCOUNT,$PHONE,$db);
	}
?>
<?php
	function delete_account_to_phone($A,$P,$db){
		/*check if ACCOUNT exist*/
		//$APIKEY = "AIzaSyCx31vQPS8SPtFBxPbfwKlHHKfvNDTtohk";
		$APIKEY = "AIzaSyCIdd_P990NSC55edBHrKdRLZjwuvb_ZM4";
		$byebye = NULL;
		$sql = "SELECT * FROM ACCOUNT WHERE NAME = ?";
		$sth = $db->prepare($sql);
		$sth->execute(array($A));
		$row = $sth->fetchObject();
		//$result = mysqli_query($C, $sql);
		//$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		if($row == NULL){
			$byebye = 0;
			//echo "This ACCOUNT is not exist.<br>";
		}
		else{
			//$result = mysqli_query($C, $sql);
			//$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
			$AID = $row->AID;
			
			$sql = "SELECT PID FROM PHONE WHERE PHONE = ?";
			$sth = $db->prepare($sql);
			$sth->execute(array($P));
			$row = $sth->fetchObject();
			//$result = mysqli_query($C, $sql);
			//$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
			$PID = $row->PID;
			
			$sql = "DELETE FROM ACCOUNT_TO_PHONE WHERE (A_ID = ? AND P_ID = ?)";
			$sth = $db->prepare($sql);
			
			if($sth->execute(array($AID,$PID)) === TRUE){
				$byebye = 1;
				//echo "success delete account_to_owners<br>";
			}
			//else echo "failed<br>";
		}
		/*
		$aRegID = array();
		array_push($aRegID, $OP);
		
		//Set POST variables
		$url = 'http://android.googleapis.com/gcm/send';
		$fields = array('registration_ids' => $aRegID,
						'data'			   => array( 'byebye' => $byebye)
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
		unset($aRegID);
		*/
		//if($byebye == 1)	echo "LOGOUT!!!<br>";
	}
?>