<?php

	require("../db/mysql.php");
	require("../model/product.php");
	require("../model/stock.php");

	$product_class = new Product($mysqli);
	$stock_class = new Stock($mysqli);
	
	$product_id = (isset($_POST['product_id'])) ? $_POST['product_id'] : 0;

	if($product_id === 0){
		echo json_encode(["isOk" => false, "message" => "Proporciona el identificador del producto."]);
		exit();
	}

	$product_class->delete($product_id);
	$stock_class->delete_by_product_id($product_id);

	echo json_encode(["isOk" => true, "message" => "El producto se eliminó correctamente."]);
?>