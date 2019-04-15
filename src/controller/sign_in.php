<?php
	
	$password = (isset($_POST['password'])) ? $_POST['password'] : "";

	if($password !== "hKZf23a"){
		echo json_encode(["isOk" => false, "message" => "La contraseña es incorrecta."]);
		exit();
	}

	echo json_encode(["isOk" => true, "message" => "La contraseña es correcta."]);

?>