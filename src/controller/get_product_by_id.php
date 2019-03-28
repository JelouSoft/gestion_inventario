<?php

	require("../db/mysql.php");
	require("../model/product.php");
	
	$product_class = new Product($mysqli);
	
	$product_id = (isset($_GET['product_id'])) ? $_GET['product_id'] : 0;

	$data = $product_class->get_by_id($product_id);

	echo json_encode($data);
?>