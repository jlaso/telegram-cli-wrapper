<?php

if (!isset($argv[1])) {
    die("You have to call this program with the peer you want to see current messages\n");
}

include_once __DIR__ . '/../vendor/autoload.php';

use TelegramCliWrapper\TelegramCliWrapper;
use TelegramCliWrapper\TelegramCliHelper;
use TelegramCliWrapper\Models\Dialog;

$th = TelegramCliHelper::getInstance();
$t = new TelegramCliWrapper($th->getSocket(), $th->isDebug());

$peer = trim($argv[1]);
$n = isset($argv[2]) ? intval($argv[2]) : 5;

print "The last $n messages with '$peer' are " . PHP_EOL .
    Dialog::getTitles() . PHP_EOL;

$history = $t->getHistory($peer, $n);
foreach ($history as $historyItem) {
    print $historyItem . PHP_EOL;
}

$t->quit();