<?php 
	
	$time = time();
	
	header('Content-Type: application/json');
	header("Content-Disposition: attachment; filename=wcs-$time.json");
	
	echo json_encode($data);
?>