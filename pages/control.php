<?php

require_once "../database.php";

?>

<h2>Контроллёр</h2>

<?php
$db = GetDatabase();

// Получение показателей
$stmt = $db->prepare("SELECT IV.id, U.full_name AS checker, I.title, IV.value 
						FROM Indicators_values AS IV, Indicators AS I, Users AS U 
						WHERE IV.indicator_id = I.id 
							AND IV.checker_id = U.id 
						ORDER BY IV.checker_id, IV.id");
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();

$values = array();
$titles = array();
foreach($data as $value) {
	$values[$value["checker"]][] = $value["value"];
	$titles[$value["title"]] = $value["title"];
}

print_r($data);