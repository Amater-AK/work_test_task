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

// Вывод показателей
?>

<form method="POST" action=".\control.php">		
	<table>
		<tr>
			<td></td>
			<?php foreach($titles as $title): ?>
				<td><?php echo $title; ?></td>
			<?php endforeach; ?>
		</tr>
		<?php foreach($values as $checker => $v): ?>
			<tr>
				<td><?php echo $checker; ?></td> 
				<?php foreach($v as $value): ?>
						<td><input value=<?php echo $value; ?> disabled /></td>
				<?php endforeach; ?>
			</tr>
		<?php endforeach; ?>
	</table>
</form>