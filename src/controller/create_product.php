<?php

	require("../db/mysql.php");
	require("../model/product.php");
	require("../model/stock.php");

	$product_class = new Product($mysqli);
	$stock_class = new Stock($mysqli);
	
	$name = (isset($_POST['name'])) ? $_POST['name'] : "";
	$unit = (isset($_POST['unit'])) ? $_POST['unit'] : "";
	$classification = (isset($_POST['classification'])) ? $_POST['classification'] : "";
	$location = (isset($_POST['location'])) ? $_POST['location'] : "";
	$feature = (isset($_POST['feature'])) ? $_POST['feature'] : "";
	$stock = (isset($_POST['stock'])) ? $_POST['stock'] : 0;

	if($name === ""){
		echo json_encode(["isOk" => false, "message" => "Proporciona el nombre del producto."]);
		exit();
	}

	if($unit === ""){
		echo json_encode(["isOk" => false, "message" => "Proporciona el nombre de la unidad."]);
		exit();
	}

	if($stock === 0 || !is_numeric($stock)){
		echo json_encode(["isOk" => false, "message" => "Proporciona las unidades en existencia."]);
		exit();
	}

	$product_id = $product_class->create($name, $unit, $classification, $location, $feature);

	if($product_id === 0){
		echo json_encode(["isOk" => false, "message" => "Error interno de servidor. PR001"]);
		exit();
	}

	$stock_id = $stock_class->create($product_id, $stock);

	if($stock_id === 0){
		echo json_encode(["isOk" => false, "message" => "Error interno de servidor. PR002"]);
		exit();
	}

	echo json_encode(["isOk" => true, "message" => "El producto se almacenó correctamente."]);
?>