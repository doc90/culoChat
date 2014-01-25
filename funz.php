<?php
$ver='0.0.0';
date_default_timezone_set('UTC');
if (file_exists('include/config.php')) {
	$config = include 'include/config.php';
} else {
	echo "config.php non esiste!";
	exit;
}

///////////////////////
// FUNZIONI DATABASE //
///////////////////////

function connetti() {
	global $config;
	$db = new mysqli($config['host'], $config['userdb'], $config['passdb'], $config['nomedb']);

	if (mysqli_connect_errno()) {
		printf("Connessione fallita: %s\n", mysqli_connect_error());
		exit();
	}
	return $db;
}

function esegui(&$db, $query) {
	$result=$db->query($query);
	return $result;
}

function seleziona (&$db, $query) {
	return $db->query($query);
}

function get_num_rows($result) {
	return $result->num_rows;
}

function scorri_record ($result) {
	return $result->fetch_assoc();
}

?>