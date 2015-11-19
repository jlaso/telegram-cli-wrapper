<?php

if ($argc < 3) {
    die ("You have to invoke this program with addContact.php phone_number last_name first_name\n");
}

include_once __DIR__ . '/../vendor/autoload.php';

use TelegramCliWrapper\TelegramCliWrapper;
use TelegramCliWrapper\TelegramCliHelper;
use TelegramCliWrapper\Models\Dialog;

$phone = $argv[1];
$peerSurname = $argv[2];
$peerName = $argv[3];

if (!preg_match("/^\+\d+/", $phone)){
    die("Phone number must be: plus sign (+) country_code & number\n");
}

$th = TelegramCliHelper::getInstance();
$t = new TelegramCliWrapper($th->getSocket(), $th->isDebug());

$t->add_contact($phone, $peerName, $peerSurname);

$t->quit();