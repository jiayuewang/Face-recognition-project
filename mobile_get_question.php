<?php
	require_once("connect.php");
	$db->exec('SET CHARACTER SET utf8');
	$db->query("SET NAMES utf8");
?>

<?php
	$QID = $_POST['qid'];
	
	$sql = "SELECT * FROM QUESTION WHERE QID = ?";
	$sth = $db->prepare($sql);
	$sth->execute(array($QID));
	
	$i=1;
	$output_question = array();
	while($row = $sth->fetchObject()){
		$output_question[$i]['question'] = $row->QUESTION;
		$output_question[$i]['content'] = $row->CONTENT;
		
		$i++;
	}
	
	if($i!==1) echo json_encode($output_question);
	else{
		$output_question[$i]['question']=" ";
		$output_question[$i]['content']=" ";
		echo json_encode($output_question);
	}
?>