<?php

include_once __DIR__ . '/../vendor/autoload.php';

use TelegramCliWrapper\TelegramCliWrapper;
use TelegramCliWrapper\TelegramCliHelper;
use TelegramCliWrapper\Models\User;

$th = TelegramCliHelper::getInstance();
$t = new TelegramCliWrapper($th->getSocket(), $th->isDebug());

echo "These are my contacts" . PHP_EOL .
    User::getTitles() . PHP_EOL;

$contacts = $t->getContactList();
foreach ($contacts as $contact) {
    echo $contact . PHP_EOL;
}

$t->quit();