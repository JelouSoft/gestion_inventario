<?php

	require("../db/mysql.php");
	require("../model/product.php");
	
	$product_class = new Product($mysqli);
	
	$data = $product_class->get_all();

	echo json_encode($data);
?>