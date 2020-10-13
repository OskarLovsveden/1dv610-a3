<?php
session_start();

// Require Controller(s)
require_once('Controller/Login.php');
require_once('Controller/Register.php');

// Require View(s)
require_once('view/Login.php');
require_once('view/Register.php');
require_once('view/DateTime.php');
require_once('view/Layout.php');

require_once('Authenticator.php');

$authenticator = new Authenticator();

// Create view objects
$loginView = new \View\Login($authenticator);
$registerView = new \View\Register($authenticator);
$dateTimeView = new \View\DateTime();
$layoutView = new \View\Layout();

$loginController = new \Controller\Login($loginView, $authenticator);
$registerController = new \Controller\Register($registerView, $authenticator);

$userLoggedIn = $loginController->isUserLoggedIn();

if ($userLoggedIn) {
    $loginController->doLogout();
} else {
    if ($layoutView->wantsToRegister()) {
        $registerController->doRegister();
    } else {
        $loginController->doLogin();
    }
}

$layoutView->render($userLoggedIn, $loginView, $registerView, $dateTimeView);