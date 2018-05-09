<?php
	session_save_path("./session");
	session_start();
?>
<?php
	require_once("connect.php");
	$NAME=$_POST["name"]; 
	$PASSWORD=$NAME; 
	$REALNAME=$_POST["realname"]; //for insert
	//$ACCOUNT=$_POST["ACCOUNT"];
	$AUTHORITY="teacher";
	//$APIKEY = "AIzaSyCx31vQPS8SPtFBxPbfwKlHHKfvNDTtohk";
	$APIKEY = "AIzaSyCIdd_P990NSC55edBHrKdRLZjwuvb_ZM4";
	$db->exec('SET CHARACTER SET utf8');
	$db->query("SET NAMES utf8");
?>
<?
	if($NAME!=NULL){
		$AUTHORITY = "teacher";
		
		$space = ' ';
		$pos = strpos($NAME, $space);
		$pos2 = strpos($PASSWORD, $space);
		
		if($NAME=="" || $PASSWORD=="" ){
			echo '<script> confirm("Something have not complete yet."); </script>';
			echo '<meta http-equiv="refresh" content="0;url=web_add_teacher.php"/>';
		}
		else if( !($AUTHORITY=="teacher" || $AUTHORITY=="student")){
			echo '<script> confirm("Authority can only be teacher or student."); </script>';
			echo '<meta http-equiv="refresh" content="0;url=web_add_teacher.php"/>';
		}
		else if($pos === false && $pos2 === false){
			/*insert into account*/
			$sql = "SELECT * FROM ACCOUNT WHERE NAME = ?";
			$sth = $db->prepare($sql);
			$sth->execute(array($NAME));
			$row = $sth->fetchObject();
			//$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
			if($row != NULL){
				echo '<script> confirm("Account has already been used."); </script>';
				echo '<meta http-equiv="refresh" content="0;url=web_add_teacher.php"/>';
			}
			else{
				/*get image data*/
				$filename=$_FILES['image']['name'];
				$tmpname=$_FILES['image']['tmp_name'];
				$filetype=$_FILES['image']['type'];
				$filesize=$_FILES['image']['size'];    
				
				$uploaddir = './teacher_photo/';
				$uploadfile = $uploaddir.basename($filename);
				$jpeg_image = 'image/jpeg';
				if($filetype!=$jpeg_image&&$tmpname!=NULL){
					echo '<script> confirm("Please choose a jpeg image!"); </script>';
					echo '<meta http-equiv="refresh" content="0;url=web_add_teacher.php"/>'; 
				}
				else{
					if($tmpname!=NULL){
						if(move_uploaded_file($tmpname, $uploadfile)){ //iconv("utf-8","big5",$uploadfile))){
								$sql = "INSERT INTO ACCOUNT(AID, NAME, PASSWORD, AUTHORITY, IMAGE, REALNAME) 
								VALUES (NULL, ?, ?, ?, ?, ?)";
								$sth=$db->prepare($sql);
								if($sth->execute(array($NAME, $PASSWORD, $AUTHORITY, $filename, $REALNAME))===TRUE){
									echo '<script> confirm("Add succeed!"); </script>';
									echo '<meta http-equiv="refresh" content="0;url=web_add_teacher.php" />';
								}
								else{
									echo '<script> confirm("Add to DB failed!"); </script>';
									echo '<meta http-equiv="refresh" content="0;url=web_add_teacher.php"/>'; 
								} 
							
						}
						else{
							echo '<script> confirm("Add failed!"); </script>';
							echo '<meta http-equiv="refresh" content="0;url=web_add_teacher.php"/>'; 
						} 
					}
					else{
						$sql = "INSERT INTO ACCOUNT(AID, NAME, PASSWORD, AUTHORITY, IMAGE, REALNAME) 
						VALUES (NULL, ?, ?, ?, 'default.jpg', ?)";
						$sth=$db->prepare($sql);
						if($sth->execute(array($NAME, $PASSWORD, $AUTHORITY, $REALNAME))===TRUE){
							echo '<script> confirm("Add succeed!"); </script>';
							echo '<meta http-equiv="refresh" content="0;url=web_add_teacher.php" />';
						}
						else{
							echo '<script> confirm("Add to DB failed!"); </script>';
							echo '<meta http-equiv="refresh" content="0;url=web_add_teacher.php"/>'; 
						} 
						//echo '<script> confirm("Please choose an image!"); </script>';
						//echo '<meta http-equiv="refresh" content="0;url=web_add_teacher.php"/>'; 
					}
				
				}
			}
		}
		else{
			echo '<script> confirm("You cannot have space in your student ID!"); </script>';
			echo '<meta http-equiv="refresh" content="0;url=web_add_teacher.php"/>';
		}
	}
?>