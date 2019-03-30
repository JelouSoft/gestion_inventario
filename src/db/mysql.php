<?php
	$mysqli = new mysqli('localhost', 'root', '', 'inventory');
	mysqli_set_charset($mysqli, "utf8");
	if(mysqli_connect_errno()){ printf("MySQL Connection Error"); exit(); }
?>