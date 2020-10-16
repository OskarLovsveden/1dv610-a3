<?php
session_start();

// Authenticator
require_once('../common/authenticator/Authenticator.php');

// App
require_once('Controller/GameApp.php');

$authenticator = new Authenticator();

$app = new \Controller\GameApp($authenticator);
$app->run();
