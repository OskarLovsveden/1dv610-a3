<?php

// Authenticator
require_once('../common/authenticator/Authenticator.php');

// App
require_once('Controller/LoginApp.php');

$authenticator = new Authenticator();

$app = new \Controller\LoginApp($authenticator);
$app->run();

var_dump($_REQUEST);
