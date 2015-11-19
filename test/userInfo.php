<?php

if (!isset($argv[1])) {
    die("You have to call this program with the peer you want to see his info\n");
}

include_once __DIR__ . '/../vendor/autoload.php';

use TelegramCliWrapper\TelegramCliWrapper;
use TelegramCliWrapper\TelegramCliHelper;
use TelegramCliWrapper\Models\User;

$th = TelegramCliHelper::getInstance();
$t = new TelegramCliWrapper($th->getSocket(), $th->isDebug());

$peer = trim($argv[1]);

echo "The info for '$peer' is " . PHP_EOL .
    User::getTitles() . PHP_EOL .
    $t->getUserInfo($peer) . PHP_EOL;

$t->quit();