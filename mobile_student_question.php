<?php
	require_once("connect.php");
	$db->exec('SET CHARACTER SET utf8');
	$db->query("SET NAMES utf8");
?>

<?php
	$course_id = $_POST['cid'];
	$account = $_POST['account'];
	
	$sql = "SELECT * FROM ACCOUNT WHERE NAME = ?";
	$sth = $db->prepare($sql);
	$sth->execute(array($account));
	$row = $sth->fetchObject();
	$id = $row->AID;
	$authority = $row->AUTHORITY;
	
	$sql = "SELECT * FROM COURSE WHERE ID = ?";
	$sth = $db->prepare($sql);
	$sth->execute(array($course_id));
	$row = $sth->fetchObject();
	$cid = $row->CID;
	
	$sql = "SELECT * FROM QUESTION WHERE C_ID = ? AND A_ID = 
			ANY(SELECT AID FROM ACCOUNT WHERE AUTHORITY = 'student')";
	$sth = $db->prepare($sql);
	$sth->execute(array($cid));
	
	$i=1;
	$output = array();
	while($row = $sth->fetchObject()){
		$output[$i]['question'] = $row->QUESTION;
		$output[$i]['question_id'] = $row->QID;
		
		if($row->A_ID == $id && $authority=="student"){
			$output[$i]['editable'] = 1;
		}
		else {
			$output[$i]['editable'] = 0;
		}
		$i++;
	}
	
	if($i!==1) echo json_encode($output);
	else{
		$output[$i]['question']=" ";
		$output[$i]['question_id']=" ";
		$output[$i]['editable'] = 0;
		echo json_encode($output);
	}
?>