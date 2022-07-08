<?php

require_once "../database.php";

?>

<h2>Заполняющий</h2>

<?php
$db = GetDatabase();

// Обновление значений показателей
if(isset($_POST["filler-update"])) {		
	$data = array();
	$flag = false;
	foreach($_POST as $key => $value) {
		if($key === "ids-start") { $flag = true; continue; }
		if($key === "ids-end") { break; }
		if($flag) {
			$data[$key] = $value;
		}
	}

	$stmt = $db->prepare("UPDATE Indicators_values 
							SET value = :value 
							WHERE id = :id");
	$stmt->bindParam(":value", $new_value);
	$stmt->bindParam(":id", $id);

	$new_value = null;
	$id = null;
	foreach($data as $key => $value) {
		$id = $key;
		$new_value = $value;
		$stmt->execute();
	}
}

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

// Вывод показателей
for($i = 0; $i < sizeof($data); $i += 2): ?>
	<form method="POST" action="./filler.php">
		<input name="ids-start" value="section-start" hidden />
		<table>
			<tr><td colspan=3><?php echo $data[$i]["checker"]; ?></td></tr>
			<tr>
				<td><?php $data[$i]["title"]; ?></td>
				<td><input value=<?php echo $data[$i]["value"]; ?> disabled /></td>
				<td><input name=<?php echo $data[$i]["id"]; ?> value=<?php echo $data[$i]["value"]; ?> /></td>
			</tr>
			<tr>
				<td><?php $data[$i+1]["title"]; ?></td>
				<td><input value=<?php echo $data[$i+1]["value"]; ?> disabled /></td>
				<td><input name=<?php echo $data[$i+1]["id"]; ?> value=<?php echo $data[$i+1]["value"]; ?> /></td>
			</tr>
			<tr>
				<td colspan=3>
					<input name="ids-end" value="section-end" hidden />
					<input type="submit" name="filler-update" value="Сохранить" />
				</td>
			</tr>
		</table>
	</form>
<?php endfor; ?>