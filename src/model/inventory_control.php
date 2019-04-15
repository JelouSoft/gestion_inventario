<?PHP

class InventoryControl{
	
	private static $mysqli;
	private static $active_status;
		
	function __construct($mysqli) {
		self::$mysqli = $mysqli;
		self::$active_status = 1;
	}

	function create($product_id, $current_stock, $quantity, $action, $annotation) {

		$inventory_control_id = 0;

		$query = "INSERT INTO `inventory_control` (`inventory_control_id`, `product_id`, `current_stock`, `quantity`, `action`, `annotation`, `status`, `created_at`, `updated_at`) VALUES (NULL, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);";

		if($stmt = self::$mysqli->prepare($query)){
			$stmt->bind_param("issisi", $product_id, $current_stock, $quantity, $action, $annotation, self::$active_status);
			$stmt->execute();
			$inventory_control_id = self::$mysqli->insert_id;
			$stmt->close();
		}

		return $inventory_control_id;

	}

	function get_by_product_id($product_id) {
		$data = [];
		$query = "SELECT
		    `inventory_control_id`,
		    `product_id`,
		    `current_stock`,
		    `quantity`,
		    `action`,
		    `annotation`,
		    `status`,
		    `created_at`,
		    `updated_at`
		FROM
		    `inventory_control`
		WHERE
		    `product_id` = ? AND `status` = ?;";
		if($stmt = self::$mysqli->prepare($query)){
			$stmt->bind_param("ii", $product_id, self::$active_status);
			$stmt->execute();
			$stmt->bind_result($inventory_control_id, $product_id, $current_stock, $quantity, $action, $annotation, $status, $created_at, $updated_at);
			while ($stmt->fetch()) {
				$data[] = [
					"inventory_control_id" => $inventory_control_id,
					"product_id" => $product_id,
					"current_stock" => $current_stock,
					"quantity" => $quantity,
					"action" => $action,
					"annotation" => $annotation,
					"status" => $status,
					"created_at" => $created_at,
					"updated_at" => $updated_at
				];
			}
			$stmt->close();
		}
		return $data;
	}

	function get() {
		$data = [];
		$query = "SELECT
		    `inventory_control`.`inventory_control_id`,
		    `product`.`name`,
		    `inventory_control`.`current_stock`,
		    `inventory_control`.`quantity`,
		    `inventory_control`.`action`,
		    `inventory_control`.`annotation`,
		    `inventory_control`.`status`,
		    `inventory_control`.`created_at`,
		    `inventory_control`.`updated_at`
		FROM
		    `inventory_control`
		INNER JOIN
				`product`
			ON
				`product`.`product_id` = `inventory_control`.`product_id`
		WHERE
		 	 	`inventory_control`.`status` = ?;";
		if($stmt = self::$mysqli->prepare($query)){
			$stmt->bind_param("i", self::$active_status);
			$stmt->execute();
			$stmt->bind_result($inventory_control_id, $product_name, $current_stock, $quantity, $action, $annotation, $status, $created_at, $updated_at);
			while ($stmt->fetch()) {
				$data[] = [
					"inventory_control_id" => $inventory_control_id,
					"product_name" => $product_name,
					"current_stock" => $current_stock,
					"quantity" => $quantity,
					"action" => $action,
					"annotation" => $annotation,
					"status" => $status,
					"created_at" => $created_at,
					"updated_at" => $updated_at
				];
			}
			$stmt->close();
		}
		return $data;
	}
	
}

?>