<?PHP

class Stock{
	
	private static $mysqli;
	private static $active_status;
		
	function __construct($mysqli) {
		self::$mysqli = $mysqli;
		self::$active_status = 1;
	}

	function create($product_id, $stock) {

		$stock_id = 0;

		$query = "INSERT INTO `stock` (`stock_id`, `product_id`, `stock`, `status`, `created_at`, `updated_at`) VALUES (NULL, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);";

		if($stmt = self::$mysqli->prepare($query)){
			$stmt->bind_param("isi", $product_id, $stock, self::$active_status);
			$stmt->execute();
			$stock_id = self::$mysqli->insert_id;
			$stmt->close();
		}

		return $stock_id;

	}

	function get_stock_by_product_id($product_id){
		$data = [];
		$query = "SELECT
		    `stock_id`,
		    `product_id`,
		    `stock`,
		    `status`,
		    `created_at`,
		    `updated_at`
		FROM
		    `stock`
		WHERE
		    `product_id` = ? AND `status` = ? LIMIT 1;";
		if($stmt = self::$mysqli->prepare($query)){
			$stmt->bind_param("ii", $product_id, self::$active_status);
			$stmt->execute();
			$stmt->bind_result($stock_id, $product_id, $stock, $status, $created_at, $updated_at);
			while ($stmt->fetch()) {
				$data = [
					"stock_id" => $stock_id,
					"product_id" => $product_id,
					"stock" => $stock,
					"status" => $status,
					"created_at" => $created_at,
					"updated_at" => $updated_at
				];
			}
			$stmt->close();
		}
		return $data;
	}

	function delete_by_product_id($product_id) {
		$status = 0;
		$query = "UPDATE `stock` SET `status` = ? WHERE `product_id` = ?;";
		if($stmt = self::$mysqli->prepare($query)){
			$stmt->bind_param("ii", $status, $product_id);
			$stmt->execute();
			$stmt->close();
		}
	}

	function update_stock_by_product_id($stock, $product_id) {
		$status = 0;
		$query = "UPDATE `stock` SET `stock` = ? WHERE `product_id` = ?;";
		if($stmt = self::$mysqli->prepare($query)){
			$stmt->bind_param("si", $stock, $product_id);
			$stmt->execute();
			$stmt->close();
		}
	}
	
}

?>