<?php

require_once "../database.php";

session_start();

// Ограничения
if(!isset($_SESSION["user"])) {
    header("Location: ../index.php");
    exit();
}
if($_SESSION["user"]["page_alias"] !== basename(__FILE__)) {
	header("Location: ./" .$_SESSION["user"]["page_alias"]);
    exit();
}
?>

<div style='position: absolute; top: 0px; right: 0px; padding: 10px; display: flex; min-width: 300px; justify-content: space-between;'>
	<strong><?php echo $_SESSION["user"]["full_name"]; ?></strong><a href='./logout.php'>Выход</a>
</div>
<h2>Проверяющий</h2>

<?php
$db = GetDatabase();

// Обновление значений показателей
if(isset($_POST["checker-update"])) {
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

// Вывод показателей
?>

<form method="POST" action="./checker.php">
	<input name="ids-start" value="section-start" hidden />
	<table>
		<tr>
			<?php foreach($titles as $title) {
				echo "<td>" .$title ."</td>";
			} ?>
		</tr>
		<tr>
			<?php foreach($values as $value) {
				echo "<td><input value=" .$value ." disabled /></td>";
			} ?>
		</tr>
		<tr>
		<?php foreach($values as $id => $value) {
				echo "<td><input name=" .$id ." value=" .$value ." /></td>";
			} ?>
		</tr>
	</table>
	<input name="ids-end" value="section-end" hidden />
	<br />
	<input type="submit" name="checker-update" value="Сохранить" />
</form>