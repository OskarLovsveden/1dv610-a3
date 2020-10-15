<?php
session_start();

// Authenticator
require_once('../common/authenticator/Authenticator.php');

// App
require_once('Controller/Application.php');

$authenticator = new Authenticator();

$app = new \Controller\Application($authenticator);
$app->run();
