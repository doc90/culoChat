<?php
include 'funz.php';
$db=connetti();
$query="select count(*) as count from messages";//cambiare la query
$result=seleziona($db, $query);
if (get_num_rows($result)>0) {
	$riga=scorri_record($result);
	$json = array('count' => $riga['count']); //cambiare il descr
	echo json_encode($json);
	exit;
}	
?>