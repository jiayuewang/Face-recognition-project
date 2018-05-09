<?php
	session_save_path("./session");
	session_start();
?>
<?php
	require_once("connect.php");
	$NAME=$_POST["name"]; 
	$PLACE=$_POST["place"]; 
	//$ACCOUNT=$_POST["ACCOUNT"];
	//$APIKEY = "AIzaSyCx31vQPS8SPtFBxPbfwKlHHKfvNDTtohk";
	$APIKEY = "AIzaSyCIdd_P990NSC55edBHrKdRLZjwuvb_ZM4";
	$db->exec('SET CHARACTER SET utf8');
	$db->query("SET NAMES utf8");
?>
<?
	if($NAME!=NULL){
		
		/*insert into account*/
		$sql = "SELECT * FROM LINKIT WHERE NAME = ?";
		$sth = $db->prepare($sql);
		$sth->execute(array($NAME));
		$row = $sth->fetchObject();
		//$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		if($row != NULL){
			echo '<script> confirm("Linkit has already been used."); </script>';
			echo '<meta http-equiv="refresh" content="0;url=web_add_linkit.php"/>';
		}
		else{  
			
			$sql = "INSERT INTO LINKIT(LID, NAME, PLACE) 
			VALUES (NULL, ?, ?)";
			$sth=$db->prepare($sql);
			if($sth->execute(array($NAME, $PLACE))===TRUE){
				echo '<script> confirm("Add succeed!"); </script>';
				echo '<meta http-equiv="refresh" content="0;url=web_add_linkit.php" />';
			}
			else{
				echo '<script> confirm("Add to DB failed!"); </script>';
				echo '<meta http-equiv="refresh" content="0;url=web_add_linkit.php"/>'; 
			} 
			//echo '<script> confirm("Please choose an image!"); </script>';
			//echo '<meta http-equiv="refresh" content="0;url=web_add_student.php"/>'; 
			
			
			
		}
		
	}
?>