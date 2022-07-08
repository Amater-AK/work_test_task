<?php

function GetDatabase(): PDO {
    static $db = null;

    if(!isset($db)) {
        require_once __DIR__ ."/config.php";

        try {
            $dsn = "mysql:dbname=" .$db_settings["db_name"] .";host=" .$db_settings["db_host"] .";charset=" .$db_settings["db_charset"];
            $db = new PDO($dsn, $db_settings["db_login"], $db_settings["db_password"]);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch(PDOException $e) {
            exit("Ошибка подключения к базе данных: " .$e->getMessage());
        }
    }

    return $db;
}

function DB_GetValues(string $query = "", array $keys = array(), array $vals = array()): array {
    $res = array();

    $stmt = GetDatabase()->prepare($query);
    
    for($k = 0; $k < sizeof($keys); $k++) {
        $stmt->bindValue(":" .$keys[$k], $vals[$k]);
    }

    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();

    return $res;
}

function DB_SetValues(string $query = "", array $keys = array(), array $vals = array()): void {
    $stmt = GetDatabase()->prepare($query);

    for($v = 0; $v < sizeof($vals); $v += sizeof($keys)) {
        for($k = 0; $k < sizeof($keys); $k++) {
            $stmt->bindValue(":" .$keys[$k], $vals[$k + $v]);
        }

        $stmt->execute();
    }
}