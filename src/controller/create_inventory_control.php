<?php

	require("../db/mysql.php");
	require("../model/inventory_control.php");
	require("../model/stock.php");

	$inventory_control_class = new InventoryControl($mysqli);
	$stock_class = new Stock($mysqli);
	
	$product_id = (isset($_POST['product_id'])) ? $_POST['product_id'] : 0;
	$action = (isset($_POST['action'])) ? $_POST['action'] : 0;
	$quantity = (isset($_POST['quantity'])) ? $_POST['quantity'] : 0;
	$annotation = (isset($_POST['annotation'])) ? $_POST['annotation'] : "";

	if($product_id === 0){
		echo json_encode(["isOk" => false, "message" => "Proporciona el identificador del producto."]);
		exit();
	}

	if($quantity <= 0 || !is_numeric($quantity)){
		echo json_encode(["isOk" => false, "message" => "Proporciona la cantidad a inventariar."]);
		exit();
	}

	if($annotation === ""){
		echo json_encode(["isOk" => false, "message" => "Proporciona una anotación."]);
		exit();
	}

	$stock_data = $stock_class->get_stock_by_product_id($product_id);

	if(empty($stock_data)){
		echo json_encode(["isOk" => false, "message" => "Error interno de servidor. IC001"]);
		exit();
	}

	$current_stock = $stock_data['stock'];

	$inventory_control_id = $inventory_control_class->create($product_id, $current_stock, $quantity, $action, $annotation);

	if($inventory_control_id === 0){
		echo json_encode(["isOk" => false, "message" => "Error interno de servidor. IC002"]);
		exit();
	}

	$new_stock = ($action == 0) ? $current_stock - $quantity : $current_stock + $quantity;

	$stock_class->update_stock_by_product_id($new_stock, $product_id);

	echo json_encode(["isOk" => true, "message" => "El stock se actualizó correctamente."]);
?>