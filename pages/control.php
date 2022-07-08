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
<h2>Контроллёр</h2>

<?php
$db = GetDatabase();

// Создание нового периода
if(isset($_POST["create_period"])) {
	try {
		$db->beginTransaction();

		// Обновление текущего периода
		$stmt = $db->prepare("UPDATE Periods 
								SET period_end = NOW(), isArchived = 1 
								WHERE isArchived = 0 
									AND period_start = (SELECT MAX(period_start) 
														FROM periods)");
		$stmt->execute();

		// Добавление нового периода
		$stmt = $db->prepare("INSERT INTO Periods VALUES()");
		$stmt->execute();

		// Получение id добавленного периода
		$stmt = $db->prepare("SELECT id 
								FROM Periods 
								WHERE isArchived = 0 
									AND period_start = (SELECT MAX(period_start) 
														FROM periods)");
		$stmt->execute();
		$data = $stmt->fetch(PDO::FETCH_ASSOC);
		$new_period_id = $data["id"];
		$stmt->closeCursor();

		// Создание новых значений для каждого показателя и для каждого проверяющего
		// Получение id всех проверяющих
		$stmt = $db->prepare("SELECT U.id 
								FROM Users AS U, Roles AS R 
								WHERE U.role_id = R.id 
									AND R.isChecker = 1 
								ORDER BY U.id");
		$stmt->execute();
		$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$stmt->closeCursor();

		$checkers_id = array();
		foreach($data as $value) {
			$checkers_id[] = $value["id"];
		}

		// Получение id всех показателей
		$stmt = $db->prepare("SELECT id 
								FROM Indicators 
								ORDER BY id");
		$stmt->execute();
		$data = $stmt->fetchAll();
		$stmt->closeCursor();

		$indicators_id = array();
		foreach($data as $value) {
			$indicators_id[] = $value["id"];
		}

		// Добавление новых значений показателей
		$vals = array();
		foreach($checkers_id as $cid) {
			foreach($indicators_id as $iid) {
				$vals[] = "(" .$iid ."," .$cid ."," .$new_period_id .")";
			}
		}

		$stmt = $db->prepare("INSERT INTO Indicators_values (indicator_id, checker_id, period_id) VALUES " .implode(", ", $vals));
		$stmt->execute();

		$db->commit();
	} catch(PDOException $e) {
		$db->rollBack();
		echo $e->getMessage();
	}
}

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
    <p><input type="submit" name="create_period" value="Новый период" /></p>
</form>