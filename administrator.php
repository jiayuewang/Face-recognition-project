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
<html>
	<head>
		<title>Roll Call</title>
		<style type="text/css">
			.font-style {
				font-family: "Trebuchet MS", "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Tahoma, sans-serif;
				color: #BF815D;
				font-size: 20;
				padding: 15;
				font-weight: bold;
			}
			.font-style-title {
				font-family: "Trebuchet MS", "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Tahoma, sans-serif;
				color: #BF815D;
				font-size: 50;
				padding: 5;
				font-weight: bold;
			}
			.font-style-session {
				font-family: "Trebuchet MS", "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Tahoma, sans-serif;
				color: #629096;
				font-size: 20;
				padding: 15;
				font-weight: bold;
			}
			.button { 
				font-size:16px;
				font-family:Trebuchet MS;
				font-weight:normal;
				-moz-border-radius:8px;
				-webkit-border-radius:8px;
				border-radius:8px;
				border:0px solid #ffffff;
				padding:7px 14px;
				text-decoration:none;
				background:-moz-linear-gradient( center top, #F4BF9F 5%, #965A37 100% );
				background:-ms-linear-gradient( top, #F4BF9F 5%, #965A37 100% );
				filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#F4BF9F', endColorstr='#965A37');
				background:-webkit-gradient( linear, left top, left bottom, color-stop(5%, #F4BF9F), color-stop(100%, #DD8E6A) );
				background-color:#F4BF9F;
				color:#f2f3fa;
				display:inline-block;
				text-shadow:1px 1px 0px #d0b0d;
				-webkit-box-shadow: 1px 1px 0px 0px #ffffff;
				-moz-box-shadow: 1px 1px 0px 0px #ffffff;
				box-shadow: 1px 1px 0px 0px #ffffff;
			}.button:hover {
				background:-moz-linear-gradient( center top, #965A37 5%, #F4BF9F 100% );
				background:-ms-linear-gradient( top, #965A37 5%, #F4BF9F 100% );
				filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#965A37', endColorstr='#F4BF9F');
				background:-webkit-gradient( linear, left top, left bottom, color-stop(5%, #965A37), color-stop(100%, #F4BF9F) );
				background-color:#965A37;
			}.button:active {
				position:relative;
				top:1px;
			}
			.CSSTableGenerator {
				margin:0px;padding:0px;
				width:100%;
				box-shadow: 10px 10px 5px #888888;
				border:1px solid #ffffff;
				
				-moz-border-radius-bottomleft:10px;
				-webkit-border-bottom-left-radius:10px;
				border-bottom-left-radius:10px;
				
				-moz-border-radius-bottomright:10px;
				-webkit-border-bottom-right-radius:10px;
				border-bottom-right-radius:10px;
				
				-moz-border-radius-topright:10px;
				-webkit-border-top-right-radius:10px;
				border-top-right-radius:10px;
				
				-moz-border-radius-topleft:10px;
				-webkit-border-top-left-radius:10px;
				border-top-left-radius:10px;
			}.CSSTableGenerator table{
				border-collapse: collapse;
					border-spacing: 0;
				width:100%;
				height:100%;
				margin:0px;padding:0px;
			}.CSSTableGenerator tr:last-child td:last-child {
				-moz-border-radius-bottomright:10px;
				-webkit-border-bottom-right-radius:10px;
				border-bottom-right-radius:10px;
			}
			.CSSTableGenerator table tr:first-child td:first-child {
				-moz-border-radius-topleft:10px;
				-webkit-border-top-left-radius:10px;
				border-top-left-radius:10px;
			}
			.CSSTableGenerator table tr:first-child td:last-child {
				-moz-border-radius-topright:10px;
				-webkit-border-top-right-radius:10px;
				border-top-right-radius:10px;
			}.CSSTableGenerator tr:last-child td:first-child{
				-moz-border-radius-bottomleft:10px;
				-webkit-border-bottom-left-radius:10px;
				border-bottom-left-radius:10px;
			}.CSSTableGenerator tr:hover td{
				
			}
			.CSSTableGenerator tr:nth-child(odd){ background-color:#CEF2E0; }
			.CSSTableGenerator tr:nth-child(even)    { background-color:#BEE8EE; }.CSSTableGenerator td{
				vertical-align:middle;
				
				border:1px solid #ffffff;
				border-width:0px 1px 1px 0px;
				text-align:center;
				padding:7px;
				font-size:16px;
				font-family:Trebuchet MS;
				font-weight:normal;
				color:#6C381A;
			}.CSSTableGenerator tr:last-child td{
				border-width:0px 1px 0px 0px;
			}.CSSTableGenerator tr td:last-child{
				border-width:0px 0px 1px 0px;
			}.CSSTableGenerator tr:last-child td:last-child{
				border-width:0px 0px 0px 0px;
			}
			.CSSTableGenerator tr:first-child td{
					background:-o-linear-gradient(bottom, #A3D3BB 5%, #A3D3BB 100%);	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #629096), color-stop(1, #629096) );
				background:-moz-linear-gradient( center top, #A3D3BB 5%, #A3D3BB 100% );
				filter:progid:DXImageTransform.Microsoft.gradient(startColorstr="#A3D3BB", endColorstr="#A3D3BB");	background: -o-linear-gradient(top,#629096,629096);
			
				background-color:#A3D3BB;
				border:0px solid #ffffff;
				text-align:center;
				border-width:0px 0px 1px 1px;
				font-size:18px;
				font-family:Trebuchet MS;
				font-weight:bold;
				color:#ffffff;
			}
			.CSSTableGenerator tr:first-child:hover td{
				background:-o-linear-gradient(bottom, #A3D3BB 5%, #A3D3BB 100%);	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #629096), color-stop(1, #629096) );
				background:-moz-linear-gradient( center top, #A3D3BB 5%, #A3D3BB 100% );
				filter:progid:DXImageTransform.Microsoft.gradient(startColorstr="#A3D3BB", endColorstr="#A3D3BB");	background: -o-linear-gradient(top,#629096,629096);
			
				background-color:#A3D3BB;
			}
			.CSSTableGenerator tr:first-child td:first-child{
				border-width:0px 0px 1px 0px;
			}
			.CSSTableGenerator tr:first-child td:last-child{
				border-width:0px 0px 1px 1px;
			}
		</style>
	</head>
	<body bgcolor="#E8FBFD">
		<ss class="font-style-title">Table(Administrator)</ss>
		<?php
			$account = $_SESSION['account'];
			$id = $_SESSION['id'];
			$authority = $_SESSION['authority'];
			if($id==null || $account==null || $authority!="administrator")
			{
				echo '<script>alert("You cannot read this page.")</script>';
				echo '<meta http-equiv="refresh" content="0;url=web_logout.php" />';
			}
			else{
				echo '<ss class="font-style-session">Hi,&nbsp;'.$account.'.&nbsp</ss>&nbsp;&nbsp;&nbsp;<br>';
				?>
				&nbsp;&nbsp;<a href="web_change_password.php" class="button">Change Password</a>
				<br><br>
				&nbsp;&nbsp;<a href="web_logout.php" class="button">Logout</a>
				<br><br>
				<br><br>
				
				&nbsp;&nbsp;<a href="web_admin_student.php" class="button">Student</a>
				&nbsp;&nbsp;<a href="web_admin_teacher.php" class="button">Teacher</a>
				&nbsp;&nbsp;<a href="web_admin_course.php" class="button">Course</a>
				&nbsp;&nbsp;<a href="web_admin_linkit.php" class="button">Linkit</a>
				
				<br><br>
				<?php
				
				
			}
		?>
	</body>	
</html>
