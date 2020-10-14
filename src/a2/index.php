<?php
session_start();

// Authenticator
require_once('../common/authenticator/Authenticator.php');
require_once('../common/authenticator/model/Username.php');
require_once('../common/authenticator/model/Password.php');
require_once('../common/authenticator/model/Credentials.php');
require_once('../common/authenticator/model/RememberMeCookie.php');

// App
require_once('controller/Application.php');

$authenticator = new Authenticator();

$app = new \Controller\Application($authenticator);
$app->run();