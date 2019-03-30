<?php

	require("../db/mysql.php");
	require("../model/product.php");
	require("../model/stock.php");

	$product_class = new Product($mysqli);
	$stock_class = new Stock($mysqli);
	
	$product_id = (isset($_POST['product_id'])) ? $_POST['product_id'] : 0;
	$name = (isset($_POST['name'])) ? $_POST['name'] : "";
	$unit = (isset($_POST['unit'])) ? $_POST['unit'] : "";
	$classification = (isset($_POST['classification'])) ? $_POST['classification'] : "";
	$location = (isset($_POST['location'])) ? $_POST['location'] : "";
	$feature = (isset($_POST['feature'])) ? $_POST['feature'] : "";

	if($product_id === 0 || !is_numeric($product_id)){
		echo json_encode(["isOk" => false, "message" => "Proporciona el identificador del producto."]);
		exit();
	}

	if($name === ""){
		echo json_encode(["isOk" => false, "message" => "Proporciona el nombre del producto."]);
		exit();
	}

	if($unit === ""){
		echo json_encode(["isOk" => false, "message" => "Proporciona el nombre de la unidad."]);
		exit();
	}

	$product_class->update($name, $unit, $classification, $location, $feature, $product_id);

	echo json_encode(["isOk" => true, "message" => "El producto se actualizó correctamente."]);
?>