<?php
	session_save_path("./session");
	session_start();
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
	require_once("connect.php");
	$db->exec('SET CHARACTER SET utf8');
	$db->query("SET NAMES utf8");
	//header("Content-Type:text/html; charset=utf-8");
?>
<?php
	$account = $_SESSION['account'];
	$id = $_SESSION['id'];
	$authority = $_SESSION['authority'];
	if($id==null || $account==null)
	{
		echo '<script>alert("You cannot change password!")</script>';
		if($authority == 'teacher')
			echo '<meta http-equiv="refresh" content="0;url=teacher.php" />';
		else if($authority == 'student')
			echo '<meta http-equiv="refresh" content="0;url=student.php" />';
	}
	else{
		$old_password = $_POST['old_password'];
		$new_password = $_POST['new_password'];
		if($old_password!=NULL && $new_password!=NULL){
			$sql = "SELECT * FROM ACCOUNT WHERE NAME = ? AND PASSWORD = ?";
			$sth = $db->prepare($sql);
			$sth->execute(array($account,$old_password));
			$row = $sth->fetchObject();
			if($row==NULL){
				echo '<script> confirm("Wrong old passage!"); </script>';
				echo '<meta http-equiv="refresh" content="0;url=web_change_password.php"/>';   	
			}
			else{
				$sql = "UPDATE ACCOUNT SET PASSWORD = ? WHERE AID = ?";
				$sth = $db->prepare($sql);
				if($sth->execute(array($new_password,$id))===TRUE){
					echo '<script> confirm("Password changed!"); </script>';
					if($authority == 'teacher')
						echo '<meta http-equiv="refresh" content="0;url=teacher.php" />';
					else if($authority == 'student')
						echo '<meta http-equiv="refresh" content="0;url=student.php" />';
					else if($authority == 'administrator')
						echo '<meta http-equiv="refresh" content="0;url=administrator.php" />';
				}
				else{
					echo '<script> confirm("Change failed!"); </script>';
					echo '<meta http-equiv="refresh" content="0;url=web_change_password.php"/>'; 
				} 
			}
		}
		else{
			echo "<script> confirm('You have to fill in everything'); </script>";
			echo '<meta http-equiv="refresh" content="0;web_change_password.php"/>';
		}
	}
	

?>