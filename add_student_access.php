<?php
	session_save_path("./session");
	session_start();
?>
<?php
	require_once("connect.php");
	$NAME=$_POST["name"]; 
	$PASSWORD=$NAME; 
	$REALNAME=$_POST["realname"]; //for insert
	$ID=$_POST['id'];
	//$ACCOUNT=$_POST["ACCOUNT"];
	$AUTHORITY="student";
	//$APIKEY = "AIzaSyCx31vQPS8SPtFBxPbfwKlHHKfvNDTtohk";
	$APIKEY = "AIzaSyCIdd_P990NSC55edBHrKdRLZjwuvb_ZM4";
	$db->exec('SET CHARACTER SET utf8');
	$db->query("SET NAMES utf8");
?>
<?
	if($NAME!=NULL){
		$AUTHORITY = "student";
		
		$space = ' ';
		$pos = strpos($NAME, $space);
		$pos2 = strpos($PASSWORD, $space);
		
		if($NAME=="" || $PASSWORD=="" ){
			echo '<script> confirm("Something have not complete yet."); </script>';
			echo '<meta http-equiv="refresh" content="0;url=web_add_student.php"/>';
		}
		else if( !($AUTHORITY=="teacher" || $AUTHORITY=="student")){
			echo '<script> confirm("Authority can only be teacher or student."); </script>';
			echo '<meta http-equiv="refresh" content="0;url=web_add_student.php"/>';
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
				echo '<meta http-equiv="refresh" content="0;url=web_add_student.php"/>';
			}
			else{
				/*get image data*/
				$filename=$_FILES['image1']['name'];
				$tmpname=$_FILES['image1']['tmp_name'];
				$filetype=$_FILES['image1']['type'];
				$filesize=$_FILES['image1']['size'];     
				
				$filename2=$_FILES['image2']['name'];
				$tmpname2=$_FILES['image2']['tmp_name'];
				$filetype2=$_FILES['image2']['type'];
				$filesize2=$_FILES['image2']['size'];   
				
				$filename3=$_FILES['image3']['name'];
				$tmpname3=$_FILES['image3']['tmp_name'];
				$filetype3=$_FILES['image3']['type'];
				$filesize3=$_FILES['image3']['size'];   
				
				$filename4=$_FILES['image4']['name'];
				$tmpname4=$_FILES['image4']['tmp_name'];
				$filetype4=$_FILES['image4']['type'];
				$filesize4=$_FILES['image4']['size'];   
				
				$filename5=$_FILES['image5']['name'];
				$tmpname5=$_FILES['image5']['tmp_name'];
				$filetype5=$_FILES['image5']['type'];
				$filesize5=$_FILES['image5']['size'];   
				
				$uploaddir = './student_photo/';
				$uploadfile = $uploaddir.basename($filename);
				$jpeg_image = 'image/jpeg';
				
				$uploaddir = './temp_photo/';
				$uploadfile2 = $uploaddir.basename($filename2);
				$uploadfile3 = $uploaddir.basename($filename3);
				$uploadfile4 = $uploaddir.basename($filename4);
				$uploadfile5 = $uploaddir.basename($filename5);
				
				if($tmpname!=NULL && $tmpname2!=NULL && $tmpname3!=NULL && $tmpname4!=NULL && $tmpname5!=NULL){
				
					if($filetype!=$jpeg_image || $filetype2!=$jpeg_image || $filetype3!=$jpeg_image || $filetype4!=$jpeg_image || $filetype5!=$jpeg_image){
						echo '<script> confirm("Please choose a jpeg image!"); </script>';
						echo '<meta http-equiv="refresh" content="0;url=web_add_student.php"/>'; 
					}
					else{
						
						if(move_uploaded_file($tmpname, $uploadfile) && move_uploaded_file($tmpname2, $uploadfile2) && move_uploaded_file($tmpname3, $uploadfile3) && move_uploaded_file($tmpname4, $uploadfile4) && move_uploaded_file($tmpname5, $uploadfile5)){ //iconv("utf-8","big5",$uploadfile))){
								$sql = "INSERT INTO ACCOUNT(AID, NAME, PASSWORD, AUTHORITY, IMAGE, REALNAME, ID) 
								VALUES (NULL, ?, ?, ?, ?, ?, ?)";
								$sth=$db->prepare($sql);
								if($sth->execute(array($NAME, $PASSWORD, $AUTHORITY, $filename, $REALNAME, $ID))===TRUE){
									//train main face
									$URL = "http://api.skybiometry.com/fc/faces/detect.json?api_key=e3ea2b7a800243e7a31e300e814707f9&api_secret=b6b0e9982224429bbd46101ad359be7e&urls=http://people.cs.nctu.edu.tw/~tihlin/view_student_image.php?image=".$filename."&attributes=all";
									$data = file_get_contents($URL);
									
									//echo $data."<br><br><br>";
									$decode_data = json_decode($data);
									//echo $decode_data->status."<br>";
									//echo $decode_data->photos[0]->tags[0]->tid."<br>";
									//echo $decode_data->photos[0]->tags[0]->recognizable."<br>";
									if($decode_data->status=="success"&&$decode_data->photos[0]->tags[0]->recognizable==true){
										$URL ="http://api.skybiometry.com/fc/tags/save.json?api_key=e3ea2b7a800243e7a31e300e814707f9&api_secret=b6b0e9982224429bbd46101ad359be7e&uid=".$NAME."@StudentFace&tids=".$decode_data->photos[0]->tags[0]->tid;
										$data = file_get_contents($URL);
										$URL ="http://api.skybiometry.com/fc/faces/train.json?api_key=e3ea2b7a800243e7a31e300e814707f9&api_secret=b6b0e9982224429bbd46101ad359be7e&uids=".$NAME."@StudentFace";
										$data = file_get_contents($URL);
										$decode_data = json_decode($data);
										if($decode_data->status=="success"){
											train_face($filename2,$NAME);
											train_face($filename3,$NAME);
											train_face($filename4,$NAME);
											train_face($filename5,$NAME);
											
											echo '<script> confirm("Add succeed!"); </script>';
											echo '<meta http-equiv="refresh" content="0;url=web_admin_student.php" />';
										}
										else{
											echo '<script> confirm("Failed to train the face!"); </script>';
											echo '<meta http-equiv="refresh" content="0;url=web_add_student.php"/>'; 	
										}
									}
									else{
										$sql = "DELETE FROM ACCOUNT WHERE NAME = ?";
										$sth = $db->prepare($sql);
										$sth->execute(array($NAME));
										echo '<script> confirm("Failed to find the face in picture!"); </script>';
										echo '<meta http-equiv="refresh" content="0;url=web_add_student.php"/>'; 
									}
									//echo '<script> confirm("Add succeed!"); </script>';
									//echo '<meta http-equiv="refresh" content="0;url=web_add_student.php" />';
								}
								else{
									echo '<script> confirm("Add to DB failed!"); </script>';
									echo '<meta http-equiv="refresh" content="0;url=web_add_student.php"/>'; 
								} 
							
						}
						else{
							echo '<script> confirm("Add failed!"); </script>';
							echo '<meta http-equiv="refresh" content="0;url=web_add_student.php"/>'; 
						} 
						
					
					}
				}
				else{
					echo '<script> confirm("Please send 5 pictures!"); </script>';
					echo '<meta http-equiv="refresh" content="0;url=web_add_student.php"/>'; 
				}
			}
		}
		else{
			echo '<script> confirm("You cannot have space in your student ID!"); </script>';
			echo '<meta http-equiv="refresh" content="0;url=web_add_student.php"/>';
		}
	}
?>
<?php
	function train_face($filename,$NAME){
		$PICTURE="http://people.cs.nctu.edu.tw/~tihlin/view_temp_image.php?image=".$filename."";
		$URL = "http://api.skybiometry.com/fc/faces/detect.json?api_key=e3ea2b7a800243e7a31e300e814707f9&api_secret=b6b0e9982224429bbd46101ad359be7e&urls=".$PICTURE."&attributes=all";
		$data = file_get_contents($URL);
		$decode_data = json_decode($data);
		if($decode_data->status=="success"&&$decode_data->photos[0]->tags[0]->recognizable==true){
			$URL ="http://api.skybiometry.com/fc/tags/save.json?api_key=e3ea2b7a800243e7a31e300e814707f9&api_secret=b6b0e9982224429bbd46101ad359be7e&uid=".$NAME."@StudentFace&tids=".$decode_data->photos[0]->tags[0]->tid;
			$data = file_get_contents($URL);
			$URL ="http://api.skybiometry.com/fc/faces/train.json?api_key=e3ea2b7a800243e7a31e300e814707f9&api_secret=b6b0e9982224429bbd46101ad359be7e&uids=".$NAME."@StudentFace";
			$data = file_get_contents($URL);
			$decode_data = json_decode($data);
			echo $decode_data->status."<br><br>";
		}
		return;
	}
?>