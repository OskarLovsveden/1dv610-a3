<?php

// Authenticator
require_once('../common/authenticator/Authenticator.php');

// App
require_once('controller/Application.php');

$authenticator = new Authenticator();

$app = new \A3\Controller\Application($authenticator);
$app->run();
