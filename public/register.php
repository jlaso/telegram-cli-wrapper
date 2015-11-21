<?php

session_start();

include_once __DIR__ . '/../vendor/autoload.php';

use TelegramCliWrapper\TelegramCliWrapper;
use TelegramCliWrapper\TelegramCliHelper;
use TelegramCliWrapper\Storage\LocalFilesStorage;
use TelegramCliWrapper\Response;
use TelegramCliWrapper\Models\User;

if (!isset($_POST['phone'])) {
    return Response::error('phone parameter missed');
}

$phone = trim($_POST['phone']);
if (!preg_match("/^\d{9,15}$/", $phone)) {
    return Response::error('phone parameter does not seems a phone number');
}

$userStorage = new LocalFilesStorage('user');
$user = $userStorage->getById($phone);
if ($user) {
    return Response::error('phone exists already in this system');
}

$phoneRequested = isset($_SESSION['phone_requested']) ? json_decode($_SESSION['phone_requested'], true) : array();
if (isset($phoneRequested[$phone]) && (intval(date("U")) < $phoneRequested[$phone])) {
    return Response::error('phone already requested, you must to wait 15 minutes');
}
$phoneRequested[$phone] = intval(date("U")) + 15 * 60 * 60;
$_SESSION['phone_requested'] = json_encode($phoneRequested);

$th = TelegramCliHelper::getInstance();
$t = new TelegramCliWrapper($th->getSocket(), $th->isDebug());

$user = User::createUser($phone, $phone, "");
$t->addContact($user);

// send message with code in order to validate phone
$r = uniqid();
$user->code = substr($r, rand(1, strlen($r)-6), 6);

$userStorage->save($user);

$msg = <<<EOD
Welcome to the telegram-cli-wrapper proof of concept
This is the code to access system, please keep it secret.
Code: {$user->code}
*Note: If this message was not solicited by you, please do not take in account.
EOD;


$t->msg($user->phone, $msg);

return Response::ok();
