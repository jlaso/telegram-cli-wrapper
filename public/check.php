<?php

session_start();

include_once __DIR__ . '/../vendor/autoload.php';

use TelegramCliWrapper\TelegramCliWrapper;
use TelegramCliWrapper\TelegramCliHelper;
use TelegramCliWrapper\Storage\LocalFilesStorage;
use TelegramCliWrapper\Response;
use TelegramCliWrapper\Models\User;
use TelegramCliWrapper\Services\Weather\OpenWeatherApi;
use TelegramCliWrapper\Services\Media\MediaSelector;
use TelegramCliWrapper\Services\Joke\IcndbApi;

if (!isset($_SESSION['user'])) {
    return Response::error("illegal request");
}

$userStorage = new LocalFilesStorage('user');
$user = $userStorage->getById($_SESSION['user']);

if (!$user) {
    return Response::error("user does not exist");
}

$th = TelegramCliHelper::getInstance();
$t = new TelegramCliWrapper($th->getSocket(), $th->isDebug());

$messages = $t->getHistory($user->phone, 1);
if (count($messages) < 1) {
    return Response::error('no messages received');
}

$message = $messages[0];
if (intval($message->unread) != 1) {
    return Response::error("no unread messages");
}

$text = strtolower(trim($message->text));
$response = "";
switch ($text) {
    case "help":
        $response = "These are the things you can ask me:\n" .
                    "help => this info\n" .
                    "remove me => remove my phone number from the system\n" .
                    "send me a photo => invite system to send a photo\n" .
                    "tell me a joke => I send to you something funny\n" .
                    "say me the time => I send to you the current time on my timezone\n" .
                    "weather => I send to you the weather where I live\n";
        break;
    case "weather":
        $weather = new OpenWeatherApi();
        $response = $weather->getWeatherInfoAsString();
        break;
    case "send me the time":
        $response = sprintf("The current time here is %s", date("l, F jS Y h:ia"));
        break;
    case "tell me a joke":
        $response = IcndbApi::getAJoke();
        break;
    case "remove me":
        $t->msg($user->phone, "You have been deleted from my contact list");
        $t->del_contact($user->phone);
        $userStorage->remove($user->phone);
        unset($_SESSION['user']);
        header("location: index.php");
        break;
    case "send me a photo":
        $t->send_photo($user->phone, MediaSelector::getRandomPicture());
        break;
    default:
        $response = "I'm so sorry.\nI'm not ready yet to understand you";
        break;
}

$t->msg($user->phone, $response);

return Response::ok();