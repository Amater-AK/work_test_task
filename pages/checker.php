<?php

require_once "../database.php";

?>

<h2>Проверяющий</h2>

<?php
$db = GetDatabase();

// Получение показателей
$stmt = $db->prepare("SELECT IV.id, I.title, IV.value 
						FROM Indicators_values AS IV, Indicators AS I 
						WHERE IV.checker_id = :checker_id 
							AND IV.indicator_id = I.id 
						ORDER BY IV.id");
$stmt->bindValue(":checker_id", 4);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();

$titles = array();
$values = array();
foreach($data as $value) {
	$titles[] = $value["title"];
	$values[$value["id"]] = $value["value"];
}

print_r($data);