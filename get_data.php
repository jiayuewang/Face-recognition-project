<?php
	$db_host = "dbhome.cs.nctu.edu.tw";
	$db_name = "tihlin_cs";
	$db_user = "tihlin_cs";
	$db_password = "55643a";
	$dsn = "mysql:host=dbhome.cs.nctu.edu.tw;dbname=tihlin_cs";
	header("Content-Type:text/html; charset=utf-8");
	$ACCOUNT=$_GET["ACCOUNT"];
	$OPHONE=$_GET["OPHONE"];
	//$APIKEY = "AIzaSyCx31vQPS8SPtFBxPbfwKlHHKfvNDTtohk";
	$APIKEY = "AIzaSyCIdd_P990NSC55edBHrKdRLZjwuvb_ZM4";
	$db = new PDO($dsn, $db_user, $db_password);
	$db->exec('SET CHARACTER SET utf8');
	$db->query("SET NAMES utf8");
?>
<?php
	$con = mysqli_connect($db_host, $db_user, $db_password, $db_name);
	if(mysqli_connect_errno($con)){
		echo "Fail to connect to MySQL: ".mysqli_connect_error();
	}
	else{
		mysqli_query($con,"SET NAMES 'UTF8'");
		//echo "MySQL database connected<br>";
		if($ACCOUNT!=NULL){ //second time
			send_data($ACCOUNT,$OPHONE,$con,$db);
		}
		mysqli_close($con); //close connection in the end
	}
?>
<?php
	function send_data($A,$OP,$C,$db){
		
		//$APIKEY = "AIzaSyCx31vQPS8SPtFBxPbfwKlHHKfvNDTtohk";
		//$APIKEY = "AIzaSyCIdd_P990NSC55edBHrKdRLZjwuvb_ZM4";
		$sql = "SELECT PNAME, RFID_ID FROM PETS WHERE  PID = 
			ANY( SELECT P_ID FROM ACCOUNT_TO_PETS WHERE A_ID = 
				( SELECT AID FROM ACCOUNT WHERE ANAME = ?))";
		$sth = $db->prepare($sql);
		$sth->execute(array($A));
		
		//$result = mysqli_query($C, $sql);
		$output_pet=array();
		$i = 1;
		$output_linkit=array();
		$j = 1;
	
		while($row = $sth->fetchObject()){
			//echo "PNAME: ".$row->PNAME."  RFID_ID: ".$row->RFID_ID."<br>";
			$output_pet[$i]['PNAME']=$row->PNAME;
			$output_pet[$i]['RFID_ID']=$row->RFID_ID;
			$i++;
			
		}
		/*check ACCOUNT is user or controller */
		$sql = "SELECT AUTHORITY FROM ACCOUNT WHERE ANAME = ?";
		$sth = $db->prepare($sql);
		$sth->execute(array($A));
		$row = $sth->fetchObject();
		//$result = mysqli_query($C, $sql);
		//$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		if($row->AUTHORITY=='controller'){
		
			$sql = "SELECT LNAME, PLACE FROM LINKIT WHERE  LID = 
			ANY( SELECT L_ID FROM ACCOUNT_TO_LINKIT WHERE A_ID = 
				( SELECT AID FROM ACCOUNT WHERE ANAME = ?))";
			$sth = $db->prepare($sql);
			$sth->execute(array($A));
			
			//$result = mysqli_query($C, $sql);
		
			while($row = $sth->fetchObject()){
				//echo "LNAME: ".$row->LNAME."  PLACE: ".$row->PLACE."<br>";
				$output_linkit[$j]['LNAME']=$row->LNAME;
				$output_linkit[$j]['PLACE']=$row->PLACE;
				$j++;
				
			}
			
		}
		
		/*send data*/
		if(i!==1) echo json_encode($output_pet);
		else{
			$output_pet[$i]['PNAME']=" ";
			$output_pet[$i]['RFID_ID']=" ";
			echo json_encode($output_pet);
		}
		if(j!==1) echo json_encode($output_linkit);
		else{
			$output_linkit[$j]['LNAME']=" ";
			$output_linkit[$j]['PLACE']=" ";
			echo json_encode($output_linkit);
		}
		
	
	}
?>