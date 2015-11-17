<?php

include_once __DIR__ . '/vendor/autoload.php';

use TelegramCliWrapper\TelegramCliWrapper;

$t = new TelegramCliWrapper("unix:///tmp/tg.sck", true);

$peerName = "Joseluis";
$peerSurname = "Laso";
$peer = $peerName . " " . $peerSurname;

//$t->add_contact('+34123456789', $peerName, $peerSurname);
$contacts = $t->contact_list();
var_dump($contacts);
$t->msg($peer, 'Hello');

$msgs = $t->history($peer, 100);
var_dump($msgs);