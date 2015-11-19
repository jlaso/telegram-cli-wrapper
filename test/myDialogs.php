<?php

include_once __DIR__ . '/../vendor/autoload.php';

use TelegramCliWrapper\TelegramCliWrapper;
use TelegramCliWrapper\TelegramCliHelper;
use TelegramCliWrapper\Models\User;

$th = TelegramCliHelper::getInstance();
$t = new TelegramCliWrapper($th->getSocket(), $th->isDebug());

echo "These are my current dialogs" . PHP_EOL .
    User::getTitles() . PHP_EOL;

$currentDialogs = $t->getDialogList();
foreach ($currentDialogs as $currentDialog) {
    echo $currentDialog . PHP_EOL;
}

$t->quit();