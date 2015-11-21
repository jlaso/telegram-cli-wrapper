<?php

include_once __DIR__ . '/../vendor/autoload.php';

use TelegramCliWrapper\TelegramCliWrapper;
use TelegramCliWrapper\TelegramCliHelper;

$th = TelegramCliHelper::getInstance();
$t = new TelegramCliWrapper($th->getSocket(), $th->isDebug());

// this is only a skeleton, if you consider to provide services to your users through telegram, please take a look over
// the file /public/check.php in order to see how to solve easily
// this file has to be used to listen to users and execute the actions ("orders") that we understand