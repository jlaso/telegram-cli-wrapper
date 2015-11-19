<?php

include_once __DIR__ . '/../vendor/autoload.php';

use TelegramCliWrapper\TelegramCliWrapper;
use TelegramCliWrapper\TelegramCliHelper;
use TelegramCliWrapper\Models\User;

$th = TelegramCliHelper::getInstance();
$t = new TelegramCliWrapper($th->getSocket(), $th->isDebug());

echo "this is me" . PHP_EOL .
    User::getTitles() . PHP_EOL .
    $t->whoAmI() . PHP_EOL;

$t->quit();