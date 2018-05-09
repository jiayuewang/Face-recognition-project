<?php
	session_save_path("./session");
	session_start();
?>
<?php
	require_once("connect.php");
	$NAME=$_POST["name"]; 
	$PASSWORD=$NAME; 
	$REALNAME=$_POST["realname"]; //for insert
	$teacher_id=$_GET['aid'];
	//$ACCOUNT=$_POST["ACCOUNT"];
	$AUTHORITY="teacher";
	$CHANGE_IMAGE = $_POST["change_image"];
	//$APIKEY = "AIzaSyCx31vQPS8SPtFBxPbfwKlHHKfvNDTtohk";
	$APIKEY = "AIzaSyCIdd_P990NSC55edBHrKdRLZjwuvb_ZM4";
	$db->exec('SET CHARACTER SET utf8');
	$db->query("SET NAMES utf8");
?>
<?
	if($teacher_id!=NULL){
		$AUTHORITY = "teacher";
		
		if($CHANGE_IMAGE=="on"){
			/*get image data*/
			$filename=$_FILES['image']['name'];
			$tmpname=$_FILES['image']['tmp_name'];
			$filetype=$_FILES['image']['type'];
			$filesize=$_FILES['image']['size'];    
					
			$uploaddir = './teacher_photo/';
			$uploadfile = $uploaddir.basename($filename);
			$jpeg_image = 'image/jpeg';
			if($filetype!=$jpeg_image){
				echo '<script> confirm("Please choose a jpeg image!"); </script>';
				echo '<meta http-equiv="refresh" content="0;url=web_edit_teacher.php?aid="'.$teacher_id.'""/>'; 
			}
			else{
				if($tmpname!=NULL){
					if(move_uploaded_file($tmpname, $uploadfile)){ //iconv("utf-8","big5",$uploadfile))){
						$sql = "UPDATE ACCOUNT SET NAME = ?, REALNAME = ?, IMAGE = ? WHERE AID = ?";
						$sth=$db->prepare($sql);
						if($sth->execute(array($NAME, $REALNAME, $filename, $teacher_id))===TRUE){
							echo '<script> confirm("Edit succeed!"); </script>';
							echo '<meta http-equiv="refresh" content="0;url=web_admin_teacher.php" />';
						}
						else{
							echo '<script> confirm("Edit to DB failed!"); </script>';
							echo '<meta http-equiv="refresh" content="0;url=web_edit_teacher.php?aid="'.$teacher_id.'""/>'; 
						} 
						
					}
					else{
						echo '<script> confirm("Add failed!"); </script>';
						echo '<meta http-equiv="refresh" content="0;url=web_edit_teacher.php?aid="'.$teacher_id.'""/>'; 
					} 
				}
				else{
					echo '<script> confirm("Please choose an image!"); </script>';
					echo '<meta http-equiv="refresh" content="0;url=web_edit_teacher.php?aid="'.$teacher_id.'""/>'; 
				}
			}
				
		}
		else{
			$sql = "UPDATE ACCOUNT SET NAME = ?, REALNAME = ? WHERE AID = ?";
			$sth=$db->prepare($sql);
			if($sth->execute(array($NAME, $REALNAME, $teacher_id))===TRUE){
				echo '<script> confirm("Edit succeed!"); </script>';
				echo '<meta http-equiv="refresh" content="0;url=web_admin_teacher.php" />';
			}
			else{
				echo '<script> confirm("Edit to DB failed!"); </script>';
				echo '<meta http-equiv="refresh" content="0;url=web_edit_teacher.php?aid="'.$teacher_id.'""/>'; 
			} 
		}
	}
?>