<?php

	require("../db/mysql.php");
	require("../model/inventory_control.php");
	
	$inventory_control_class = new InventoryControl($mysqli);
	
	$product_id = (isset($_GET['product_id'])) ? $_GET['product_id'] : 0;

	$data = $inventory_control_class->get_by_product_id($product_id);

	echo json_encode($data);
?>