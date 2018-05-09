<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
	require_once("connect.php");
	$db->exec('SET CHARACTER SET utf8');
	$db->query("SET NAMES utf8");
	$USERNAME = $_POST["account"];
	$PW = $_POST["password"];
	$IS_TEACHER = $_POST["is_teacher"];
	$ADMIN = $_POST["admin_account"];
	$ADMIN_PW = $_POST["admin_password"];
	$VERI = $_POST["verification"];
?>
<?php
	if($USERNAME!=NULL){
		$AUTHORITY;
		if($IS_TEACHER == "on") $AUTHORITY="teacher";
		else $AUTHORITY="student";
		
		$space = ' ';
		$pos = strpos($USERNAME, $space);
		$pos2 = strpos($PW, $space);
		
		if($USERNAME=="" || $PW=="" ){
			echo '<script> confirm("Something have not complete yet."); </script>';
			echo '<meta http-equiv="refresh" content="0;url=web_regist.php"/>';
		}
		else if( !($AUTHORITY=="teacher" || $AUTHORITY=="student")){
			echo '<script> confirm("Authority can only be teacher or student."); </script>';
			echo '<meta http-equiv="refresh" content="0;url=web_regist.php"/>';
		}
		else if($pos === false && $pos2 === false){
			/*insert into account*/
			insert_account($USERNAME, $PW, $AUTHORITY, $db);
		}
		else{
			echo '<script> confirm("You cannot have space in your account or password!"); </script>';
			echo '<meta http-equiv="refresh" content="0;url=web_regist.php"/>';
		}
	}
	else if($ADMIN!=NULL){
		if($VERI!='veri')
		{
			echo '<script> confirm("Wrong verification code!."); </script>';
			echo '<meta http-equiv="refresh" content="0;url=web_admin_regist.php"/>';
		}
		else{
			$AUTHORITY = "administrator";
			
			$space = ' ';
			$pos = strpos($ADMIN, $space);
			$pos2 = strpos($ADMIN_PW, $space);
			
			if($ADMIN=="" || $ADMIN_PW=="" ){
				echo '<script> confirm("Something have not complete yet."); </script>';
				echo '<meta http-equiv="refresh" content="0;url=web_admin_regist.php"/>';
			}
			else if($pos === false && $pos2 === false){
				/*insert into account*/
				insert_account($ADMIN, $ADMIN_PW, $AUTHORITY, $db);
			}
			else{
				echo '<script> confirm("You cannot have space in your account or password!"); </script>';
				echo '<meta http-equiv="refresh" content="0;url=web_regist.php"/>';
			}
		}
	}
	else{
		echo '<script> confirm("Regist Failed!"); </script>';
		echo '<meta http-equiv="refresh" content="0;url=web_login.php"/>';
	}
?>
<?php
	function insert_account($U,$P,$A,$db){
	
		$sql = "SELECT * FROM ACCOUNT WHERE NAME = ?";
		$sth = $db->prepare($sql);
		$sth->execute(array($U));
		$row = $sth->fetchObject();
		//$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		if($row != NULL){
			echo '<script> confirm("Account has already been used."); </script>';
			if($A=="administrator")
				echo '<meta http-equiv="refresh" content="0;url=web_admin_regist.php"/>';   
			else
				echo '<meta http-equiv="refresh" content="0;url=web_regist.php"/>';   
		}
		else{
			/*insert ACCOUNT*/
			$sql = "INSERT INTO ACCOUNT (AID, NAME, PASSWORD, AUTHORITY)
					VALUES (NULL, ?, ?, ?) ON DUPLICATE KEY UPDATE AID=AID";
			$sth = $db->prepare($sql);
			
			if ($sth->execute(array($U,$P,$A))=== TRUE){
				echo '<script> confirm("Regist Succeed!"); </script>';
				if($A=="administrator")
					echo '<meta http-equiv="refresh" content="0;url=web_admin_login.php"/>';     
				else
					echo '<meta http-equiv="refresh" content="0;url=web_login.php"/>';     
			}
			else {
				echo '<script> confirm("Regist Failed! (insert into sql failed)"); </script>';
				if($A=="administrator")
					echo '<meta http-equiv="refresh" content="0;url=web_admin_regist.php"/>';     
				else
					echo '<meta http-equiv="refresh" content="0;url=web_regist.php"/>';     
			}
		}
	}
?>