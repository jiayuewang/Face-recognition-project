<?php
	session_save_path("./session");
	session_start();
?>
<?php
	require_once("connect.php");
	$NAME=$_POST["name"]; 
	$PLACE=$_POST["place"]; 
	$linkit_id=$_GET['lid'];
	//$ACCOUNT=$_POST["ACCOUNT"];
	//$APIKEY = "AIzaSyCx31vQPS8SPtFBxPbfwKlHHKfvNDTtohk";
	$APIKEY = "AIzaSyCIdd_P990NSC55edBHrKdRLZjwuvb_ZM4";
	$db->exec('SET CHARACTER SET utf8');
	$db->query("SET NAMES utf8");
?>
<?
	if($linkit_id!=NULL){
		$sql = "UPDATE LINKIT SET NAME = ?, PLACE = ? WHERE LID = ?";
		$sth=$db->prepare($sql);
		if($sth->execute(array($NAME, $PLACE, $linkit_id))===TRUE){
			echo '<script> confirm("Edit succeed!"); </script>';
			echo '<meta http-equiv="refresh" content="0;url=web_admin_linkit.php" />';
		}
		else{
			echo '<script> confirm("Edit to DB failed!"); </script>';
			echo '<meta http-equiv="refresh" content="0;url=web_edit_linkit.php?lid="'.$linkit_id.'""/>'; 
		} 
		
	}
?>