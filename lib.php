<?php

/**
 * @param $connectDB
 * @param $userName
 * @param $name
 * @param $chatId
 * @param $oldId
 * @return bool
 */
function add_user($connectDB, $userName, $name, $chatId, $oldId)
{
    $userName = trim($userName);
    $name = trim($name);
    $chatId = trim($chatId);

    if ($chatId == $oldId) {
        return false;
    }
    $t = "INSERT INTO users (user_name, chat_id, name) VALUES ('%s', '%s', '%s')";
    $query = sprintf($t, mysqli_real_escape_string($connectDB, $userName),
        mysqli_real_escape_string($connectDB, $chatId),
        mysqli_real_escape_string($connectDB, $name));
    $result = mysqli_query($connectDB, $query);
    if (!$result) {
        die(mysqli_error($connectDB));
    }
    return true;
}

/**
 * @param $connectDB
 * @param $chatId
 * @return string[]|null
 */
function getUser($connectDB, $chatId)
{
    $query = sprintf("SELECT * FROM users WHERE chat_id=%d", (int)$chatId);
    $result = mysqli_query($connectDB, $query);
    if (!$result) {
        die(mysqli_error($connectDB));
    }
    return mysqli_fetch_assoc($result);
}

/**
 * @param $connectDB
 * @param $chatId
 * @param $text
 * @return bool
 */
function textLog($connectDB, $chatId, $text)
{
    if ($chatId == '') {
        return false;
    }
    $t = "INSERT INTO text_log (chat_id, text) VALUES ('%s', '%s')";

    $query = sprintf($t, mysqli_real_escape_string($connectDB, $chatId),
        mysqli_real_escape_string($connectDB, $text));

    $result = mysqli_query($connectDB, $query);

    if (!$result) {
        die(mysqli_error($connectDB));
    }
    return true;
}

/**
 * @param $connectDB
 * @return array
 */
function users_all($connectDB)
{
    $query = "SELECT * FROM users";
    $result = mysqli_query($connectDB, $query);
    if (!$result) {
        die(mysqli_error($connectDB));
    }

    $n = mysqli_num_rows($result);
    $users_all = [];
    for ($i = 0; $i < $n; $i++) {

        $row = mysqli_fetch_assoc($result);
        $users_all[] = $row;
    }

    return $users_all;
}


