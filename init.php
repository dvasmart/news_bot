<?php

include 'vendor/autoload.php';
include 'menu.php';
include 'settings.php';
include 'lib.php';

use Telegram\Bot\Api;

$telegram = new Api($token);

$oldUpdateId = file_get_contents('oldUpdateId.txt');

$response = $telegram->getUpdates();
$arrayKeyLast = array_key_last($response);
$newUpdateId = $response[$arrayKeyLast]['update_id'];

if ($newUpdateId > $oldUpdateId) {
    $telegram->getUpdates(['offset' => $newUpdateId]);

    file_put_contents('oldUpdateId.txt', $newUpdateId);

    $text = $response[$arrayKeyLast]['message']['text'];
    $chatId = $response[$arrayKeyLast]['message']['chat']['id'];
    textLog($connectDB, $chatId, $text);
}

$text = $response[$arrayKeyLast]['message']['text'];
$chatId = $response[$arrayKeyLast]['message']['chat']['id'];
$userName = $response[$arrayKeyLast]['message']['chat']['username'];
$firstName = $response[$arrayKeyLast]['message']['chat']['first_name'];
$lastName = $response[$arrayKeyLast]['message']['chat']['last_name'];

$getUser = getUser($connectDB, $chatId);

$oldId = $getUser['chat_id'];

if ($text == '/start') {
    $reply = 'Menu: ';
    $reply_markup = $telegram->replyKeyboardMarkup([
        'keyboard' => $menu,
        'resize_keyboard' => true,
        'one_time_keyboard' => false
    ]);
    $telegram->sendMessage([
        'chat_id' => $chatId,
        'text' => $reply,
        'reply_markup' => $reply_markup
    ]);
} elseif ($text == 'Привет') {
    $reply = 'Привет ' . $firstName . " " . $lastName . '!';
    $img = "img.jpg";
    $telegram->sendPhoto([
        'chat_id' => $chatId,
        'photo' => $img,
        'caption' => $reply
    ]);
} elseif ($text == 'Кнопка 2') {
    $reply = 'Вы нажали: \'' . $text . '\' и открыли подменю !';
    $reply_markup = $telegram->replyKeyboardMarkup([
        'keyboard' => $menu2,
        'resize_keyboard' => true,
        'one_time_keyboard' => false
    ]);
    $telegram->sendMessage([
        'chat_id' => $chatId,
        'text' => $reply,
        'reply_markup' => $reply_markup
    ]);
} elseif ($text == 'Новини') {
    $xml = simplexml_load_file('https://news.google.com/rss/topics/CAAqHAgKIhZDQklTQ2pvSWJHOWpZV3hmZGpJb0FBUAE/sections/CAQiUENCSVNOam9JYkc5allXeGZkakpDRUd4dlkyRnNYM1l5WDNObFkzUnBiMjV5Q3hJSkwyMHZNRE15ZUY5cmVnc0tDUzl0THpBek1uaGZheWdBKjEIACotCAoiJ0NCSVNGem9JYkc5allXeGZkako2Q3dvSkwyMHZNRE15ZUY5cktBQVABUAE?hl=uk&gl=UA&ceid=UA%3Auk');
    $i = 0;
    $reply = $xml->channel->title . ":\n\n";

    foreach ($xml->channel->item as $item) {
        $i++;
        if ($i > 3) {
            break;
        }
        $reply .= " " . $item->title . " \nОпубліковано: " .
            $item->pubDate . "\n <a href= '" . $item->link . "' >Детально за посиланням</a>\n\n";
    }
    $telegram->sendMessage([
        'chat_id' => $chatId,
        'text' => $reply,
        'parse_mode' => 'HTML',
        'disable_web_page_preview' => true
    ]);
} elseif ($text == 'Inline Keyboard') {
    $reply = "Inline Keyboard";
    $inline[] = [
        'text' => 'Go to Google Search!',
        'url' => 'https://www.google.com/',
    ];
    $inline = array_chunk($inline, 2);
    $replyMarkup = [
        'inline_keyboard' => $inline,
    ];
    $inlineKeyboard = json_encode($replyMarkup);
    $telegram->sendMessage([
        'chat_id' => $chatId,
        'text' => $reply,
        'reply_markup' => $inlineKeyboard,
    ]);
}

add_user($connectDB, $userName, $firstName, $chatId, $oldId);





