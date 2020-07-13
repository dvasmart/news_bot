<?php

$token = "";

const MYSQL_SERVER = 'localhost';

const MYSQL_USER = 'root';

const MYSQL_PASSWORD = '';

const MYSQL_DB = 'bot_3_db';

function connect_db()
{
    $link = mysqli_connect(MYSQL_SERVER, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);

    if (!$link) {
        echo "Ошибка: Невозможно установить соединение с MySQL." . PHP_EOL;
        echo "Код ошибки errno: " . mysqli_connect_errno() . PHP_EOL;
        echo "Текст ошибки error: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }

    return $link;
}

$connectDB = connect_db();