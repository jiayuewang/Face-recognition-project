<?php
	function getRadomStr($len){
		$str = 'abcdefghijklmnopqrstuvwxyz0123456789';
		$getRadomCode=substr(str_shuffle($str),0,$len);
		$_SESSION['codeid']=$getRadomCode;
		return $getRadomCode;
	}
	$CODE = getRadomStr(10);
	echo '<a href="show_qr.php?code='.$CODE.'" class="button">See QRCODE</a><br><br>';
?>