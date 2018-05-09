<?php
	require_once("connect.php");
	$db->exec('SET CHARACTER SET utf8');
	$db->query("SET NAMES utf8");
?>
<?php

	$output_course=array();
	$i=1;
	$account = $_POST['account'];
	
	$sql = "SELECT * FROM ACCOUNT WHERE NAME = ?";
	$sth = $db->prepare($sql);
	$sth->execute(array($account));
	$row = $sth->fetchObject();
	
	$id = $row->AID;
	if($row->AUTHORITY=='student'){
		$sql = "SELECT * FROM COURSE WHERE CID = 
		   ANY(SELECT C_ID FROM COURSE_TO_STUDENT WHERE A_ID = ?)";
		$sth = $db->prepare($sql);
		$sth->execute(array($id));
		
		while($row = $sth->fetchObject()){
			
			$sql_c = "SELECT * FROM LINKIT WHERE LID =
					ANY(SELECT L_ID FROM LINKIT_TO_COURSE WHERE C_ID = ?)";
			$sth_c = $db->prepare($sql_c);
			$sth_c->execute(array($row->CID));
			$row_c=$sth_c->fetchObject();
			
			$output_course[$i]['ID']=$row->ID;
			$output_course[$i]['NAME']=$row->NAME;
			$output_course[$i]['TIME']=$row->TIME;
			$output_course[$i]['PLACE']=$row_c->PLACE;
			$i++;
			//echo '<tr><td>'.$row->ID.'</td><td>'.$row->NAME.'</td><td>'.$row->TIME.'</td><td>'.$row_c->PLACE.'</td>';
		}
	}
	else if($row->AUTHORITY=='teacher'){
		$sql = "SELECT * FROM COURSE WHERE CID = 
		   ANY(SELECT C_ID FROM COURSE_TO_TEACHER WHERE A_ID = ?)";
		$sth = $db->prepare($sql);
		$sth->execute(array($id));
		
		while($row = $sth->fetchObject()){
			
			$sql_c = "SELECT * FROM LINKIT WHERE LID =
					ANY(SELECT L_ID FROM LINKIT_TO_COURSE WHERE C_ID = ?)";
			$sth_c = $db->prepare($sql_c);
			$sth_c->execute(array($row->CID));
			$row_c=$sth_c->fetchObject();
			
			$output_course[$i]['ID']=$row->ID;
			$output_course[$i]['NAME']=$row->NAME;
			$output_course[$i]['TIME']=$row->TIME;
			$output_course[$i]['PLACE']=$row_c->PLACE;
			$i++;
			//echo '<tr><td>'.$row->ID.'</td><td>'.$row->NAME.'</td><td>'.$row->TIME.'</td><td>'.$row_c->PLACE.'</td>';
		}
	
	}
	if($i!==1) echo json_encode($output_course);
	else{
		
		$output_course[$i]['ID']=" ";
		$output_course[$i]['NAME']=" ";
		$output_course[$i]['TIME']=" ";
		$output_course[$i]['PLACE']=" ";
		echo json_encode($output_course);
	}
	
?>