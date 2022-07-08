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