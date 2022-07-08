<?php

require_once "../database.php";

?>

<h2>Заполняющий</h2>

<?php
$db = GetDatabase();

// Получение показателей
$stmt = $db->prepare("SELECT IV.id, I.title, U.full_name AS checker, IV.value 
						FROM Indicators_values AS IV, Indicators AS I, Users AS U 
						WHERE I.filler_id = :filler_id 
							AND IV.indicator_id = I.id
							AND IV.checker_id = U.id 
						ORDER BY IV.checker_id");
$stmt->bindValue(":filler_id", 1);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();

print_r($data);