<?php

	require("../db/mysql.php");
	require("../model/inventory_control.php");
	
	$inventory_control_class = new InventoryControl($mysqli);
	
	$data = $inventory_control_class->get();

	echo json_encode($data);
?>