<?PHP

class Product{
	
	private static $mysqli;
	private static $active_status;
		
	function __construct($mysqli) {
		self::$mysqli = $mysqli;
		self::$active_status = 1;
	}

	function create($name, $unit, $classification, $location, $feature) {

		$product_id = 0;

		$query = "INSERT INTO `product` (`product_id`, `name`, `unit`, `classification`, `location`, `feature`, `status`, `created_at`, `updated_at`) VALUES (NULL, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);";

		if($stmt = self::$mysqli->prepare($query)){
			$stmt->bind_param("sssssi", $name, $unit, $classification, $location, $feature, self::$active_status);
			$stmt->execute();
			$product_id = self::$mysqli->insert_id;
			$stmt->close();
		}

		return $product_id;

	}

	function get_all() {
		$data = [];

		$query="SELECT
		    `product`.`product_id`,
		    `product`.`name`,
		    `product`.`unit`,
		    `product`.`classification`,
		    `product`.`location`,
		    `product`.`feature`,
		    `stock`.`stock`,
		    `product`.`status`,
		    `product`.`created_at`,
		    `product`.`updated_at`
		FROM
		    `product`
		INNER JOIN `stock` ON `stock`.`product_id` = `product`.`product_id`
		WHERE
		    `product`.`status` = ?
		ORDER BY `product`.`name`";

		if($stmt = self::$mysqli->prepare($query)){
			$stmt->bind_param("i", self::$active_status);
			$stmt->execute();
			$stmt->bind_result($product_id, $name, $unit, $classification, $location, $feature, $stock, $status, $created_at, $updated_at);
			while ($stmt->fetch()) {
				$data[] = [
					"product_id" => $product_id,
					"name" => $name,
					"unit" => $unit,
					"classification" => $classification,
					"location" => $location,
					"feature" => $feature,
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

	function get_by_id($product_id) {
		$data = [];

		$query="SELECT
		    `product`.`product_id`,
		    `product`.`name`,
		    `product`.`unit`,
		    `product`.`classification`,
		    `product`.`location`,
		    `product`.`feature`,
		    `stock`.`stock`,
		    `product`.`status`,
		    `product`.`created_at`,
		    `product`.`updated_at`
		FROM
		    `product`
		INNER JOIN `stock` ON `stock`.`product_id` = `product`.`product_id`
		WHERE
		    `product`.`product_id` = ? AND `product`.`status` = ?";

		if($stmt = self::$mysqli->prepare($query)){
			$stmt->bind_param("ii", $product_id, self::$active_status);
			$stmt->execute();
			$stmt->bind_result($product_id, $name, $unit, $classification, $location, $feature, $stock, $status, $created_at, $updated_at);
			while ($stmt->fetch()) {
				$data = [
					"product_id" => $product_id,
					"name" => $name,
					"unit" => $unit,
					"classification" => $classification,
					"location" => $location,
					"unit" => $unit,
					"feature" => $feature,
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

	function delete($product_id) {
		$status = 0;
		$query = "UPDATE `product` SET `status` = ? WHERE `product_id` = ?;";
		if($stmt = self::$mysqli->prepare($query)){
			$stmt->bind_param("ii", $status, $product_id);
			$stmt->execute();
			$stmt->close();
		}
	}

	function update($name, $unit, $classification, $location, $feature, $product_id) {
		$query = "UPDATE `product` SET `name` = ?, `unit` = ?, `classification` = ?, `location` = ?, `feature` = ? WHERE `product_id` = ?;";
		if($stmt = self::$mysqli->prepare($query)){
			$stmt->bind_param("sssssi", $name, $unit, $classification, $location, $feature, $product_id);
			$stmt->execute();
			$stmt->close();
		}
	}
	
}

?>