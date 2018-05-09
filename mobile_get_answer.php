<?php
	require_once("connect.php");
	$db->exec('SET CHARACTER SET utf8');
	$db->query("SET NAMES utf8");
?>

<?php
	$question_id = $_POST['qid'];
	$account = $_POST['account'];
	
	$sql = "SELECT * FROM ACCOUNT WHERE NAME = ?";
	$sth = $db->prepare($sql);
	$sth->execute(array($account));
	$row = $sth->fetchObject();
	$id = $row->AID;
	$authority = $row->AUTHORITY;
	
	$sql = "SELECT * FROM ANSWER WHERE Q_ID = ?";
	$sth = $db->prepare($sql);
	$sth->execute(array($question_id));
	
	$i=1;
	$output_answer = array();
	while($row = $sth->fetchObject()){
		$output_answer[$i]['answer'] = $row->ANSWER;
		$output_answer[$i]['answer_id'] = $row->AID; //answer id
		
		if($row->A_ID == $id){
			$output_answer[$i]['editable'] = 1;
		}
		else {
			$output_answer[$i]['editable'] = 0;
		}
		$i++;
	}
	
	if($i!==1) echo json_encode($output_answer);
	else{
		$output_answer[$i]['answer']=" ";
		$output_answer[$i]['answer_id']=" ";
		$output_answer[$i]['editable'] = 0;
		echo json_encode($output_answer);
	}
?>