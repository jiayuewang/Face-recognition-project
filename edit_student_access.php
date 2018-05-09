<?php
	session_save_path("./session");
	session_start();
?>
<?php
	require_once("connect.php");
	$NAME=$_POST["name"]; 
	$PASSWORD=$NAME; 
	$REALNAME=$_POST["realname"]; //for insert
	$ID=$_POST["id"];
	$student_id=$_GET['aid'];
	//$ACCOUNT=$_POST["ACCOUNT"];
	$AUTHORITY="student";
	$CHANGE_IMAGE = $_POST["change_image"];
	//$APIKEY = "AIzaSyCx31vQPS8SPtFBxPbfwKlHHKfvNDTtohk";
	$APIKEY = "AIzaSyCIdd_P990NSC55edBHrKdRLZjwuvb_ZM4";
	$db->exec('SET CHARACTER SET utf8');
	$db->query("SET NAMES utf8");
?>
<?
	if($student_id!=NULL){
		$AUTHORITY = "student";
		
		if($CHANGE_IMAGE=="on"){
			/*get image data*/
			$filename=$_FILES['image']['name'];
			$tmpname=$_FILES['image']['tmp_name'];
			$filetype=$_FILES['image']['type'];
			$filesize=$_FILES['image']['size'];    
					
			$uploaddir = './student_photo/';
			$uploadfile = $uploaddir.basename($filename);
			$jpeg_image = 'image/jpeg';
			if($filetype!=$jpeg_image){
				echo '<script> confirm("Please choose a jpeg image!"); </script>';
				echo '<meta http-equiv="refresh" content="0;url=web_edit_student.php?aid="'.$student_id.'""/>'; 
			}
			else{
				if($tmpname!=NULL){
					if(move_uploaded_file($tmpname, $uploadfile)){ //iconv("utf-8","big5",$uploadfile))){
						$sql = "UPDATE ACCOUNT SET NAME = ?, REALNAME = ?, IMAGE = ?, ID = ? WHERE AID = ?";
						$sth=$db->prepare($sql);
						if($sth->execute(array($NAME, $REALNAME, $filename, $ID, $student_id))===TRUE){
							//$URL ="http://api.skybiometry.com/fc/tags/get.json?api_key=e3ea2b7a800243e7a31e300e814707f9&api_secret=b6b0e9982224429bbd46101ad359be7e&uid=".$NAME."@StudentFace";
							//$data = file_get_contents($URL);
							//$decode_data = json_decode($data);
							//$TID = $decode_data->photos[0]->tags[0]->tid;
							//$URL ="http://api.skybiometry.com/fc/tags/remove.json?api_key=e3ea2b7a800243e7a31e300e814707f9&api_secret=b6b0e9982224429bbd46101ad359be7e&tids=".$TID;
							//$data = file_get_contents($URL);
								
							$URL = "http://api.skybiometry.com/fc/faces/detect.json?api_key=e3ea2b7a800243e7a31e300e814707f9&api_secret=b6b0e9982224429bbd46101ad359be7e&urls=http://people.cs.nctu.edu.tw/~tihlin/view_student_image.php?image=".$filename."&attributes=all";
							$data = file_get_contents($URL);
							$decode_data = json_decode($data);
							if($decode_data->status=="success"&&$decode_data->photos[0]->tags[0]->recognizable==true){
								
								$URL ="http://api.skybiometry.com/fc/tags/save.json?api_key=e3ea2b7a800243e7a31e300e814707f9&api_secret=b6b0e9982224429bbd46101ad359be7e&uid=".$NAME."@StudentFace&tids=".$decode_data->photos[0]->tags[0]->tid;
								$data = file_get_contents($URL);
								$URL ="http://api.skybiometry.com/fc/faces/train.json?api_key=e3ea2b7a800243e7a31e300e814707f9&api_secret=b6b0e9982224429bbd46101ad359be7e&uids=".$NAME."@StudentFace";
								$data = file_get_contents($URL);
								$decode_data = json_decode($data);
								if($decode_data->status=="success"){
									echo '<script> confirm("Edit succeed!"); </script>';
									echo '<meta http-equiv="refresh" content="0;url=web_admin_student.php" />';
								}
								else{
									echo '<script> confirm("Failed to train the face! Please change a picture later"); </script>';
									echo '<meta http-equiv="refresh" content="0;url=web_edit_student.php?aid='.$student_id.'"/>'; 	
								}
							}
							else{
								echo '<script> confirm("Failed to find the face in picture! Please change a picture later"); </script>';
								echo '<meta http-equiv="refresh" content="0;url=web_edit_student.php?aid='.$student_id.'"/>'; 
									}
							//echo '<script> confirm("Edit succeed!"); </script>';
							//echo '<meta http-equiv="refresh" content="0;url=web_admin_student.php" />';
						}
						else{
							echo '<script> confirm("Edit to DB failed!"); </script>';
							echo '<meta http-equiv="refresh" content="0;url=web_edit_student.php?aid='.$student_id.'"/>'; 
						} 
						
					}
					else{
						echo '<script> confirm("Add failed!"); </script>';
						echo '<meta http-equiv="refresh" content="0;url=web_edit_student.php?aid='.$student_id.'"/>'; 
					} 
				}
				else{
					echo '<script> confirm("Please choose an image!"); </script>';
					echo '<meta http-equiv="refresh" content="0;url=web_edit_student.php?aid='.$student_id.'"/>'; 
				}
			}
				
		}
		else{
			$sql = "UPDATE ACCOUNT SET NAME = ?, REALNAME = ?, ID = ? WHERE AID = ?";
			$sth=$db->prepare($sql);
			if($sth->execute(array($NAME, $REALNAME, $ID, $student_id))===TRUE){
				echo '<script> confirm("Edit succeed!"); </script>';
				echo '<meta http-equiv="refresh" content="0;url=web_admin_student.php" />';
			}
			else{
				echo '<script> confirm("Edit to DB failed!"); </script>';
				echo '<meta http-equiv="refresh" content="0;url=web_edit_student.php?aid='.$student_id.'"/>'; 
			} 
		}
	}
?>