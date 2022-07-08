<?php

require_once "./database.php";

session_start();

// Ограничения
if(isset($_SESSION["user"])) {
    header("Location: ./pages/" .$_SESSION["user"]["page_alias"]);
    exit();
}

$db = GetDatabase();

// Аутентификация
if(isset($_POST["login_submit"])) {
    $test_login = $_POST["login"];
    $test_pass = $_POST["password"];

    // Проверяем есть ли пользователь с таким логином и получаем его id и хеш пароля
    $stmt = $db->prepare("SELECT id, password 
                            FROM Users 
                            WHERE login = :login
                            LIMIT 1");
    $stmt->bindValue(":login", $test_login);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    
    // Если пользователь найден
    if($data !== false && $stmt->rowCount() > 0) {
        // Проверяем правильность пароля
        if(password_verify($test_pass, $data["password"])) {
            // Проверяем не устарел ли метод хеширования и при необходимости обновляем
            if(password_needs_rehash($data["password"], PASSWORD_DEFAULT)) {
                $new_pass_hash = password_hash($test_pass, PASSWORD_DEFAULT);

                $stmt = $db->prepare("UPDATE Users 
                                        SET password = :password 
                                        WHERE id = :id 
                                        LIMIT 1");
                $stmt->bindValue(":password", $new_pass_hash);
                $stmt->bindValue(":id", $data["id"]);
                $stmt->execute();
            }
            
            // Получаем данные пользователя
            $stmt = $db->prepare("SELECT U.id, U.full_name, R.id AS role_id, R.title AS role_title, R.page_alias, R.isFiller, R.isChecker, R.isControl  
                                    FROM Users AS U, Roles AS R 
                                    WHERE U.id = :id 
                                        AND U.role_id = R.id");
            $stmt->bindValue(":id", $data["id"]);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            $_SESSION["user"] = $user;
            header("Location: ./pages/" .$user["page_alias"]);

        } else {
            // Пароль не верен
            echo "Введён неправельный логин и/или пароль.";
        }
    } else {
        // Такого пользователя нет
        echo "Введён неправельный логин и/или пароль.";
    }
}

?>

<form method="POST" action="./index.php">
    <p><label for="login">Логин:</label><br />
    <input id="login" name="login" required /></p>

    <p><label for="password">Пароль:</label><br />
    <input type="password" id="password" name="password" required /></p>

    <p><input type="submit" name="login_submit" value="Войти" /></p>
</form>

<p>
    <strong>Пользователи:</strong><br />
    filler1 - pass<br />
    filler2 - pass<br />
    filler3 - pass<br />
    checker1 - pass<br />
    checker2 - pass<br />
    checker3 - pass<br />
    control - pass<br />
</p>