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

// Require Model(s)
require_once('model/Username.php');
require_once('model/Password.php');
require_once('model/User.php');

// Require DAL(s)
require_once('model/DAL/Database.php');
require_once('model/DAL/CookieDAL.php');
require_once('model/DAL/SessionDAL.php');
require_once('model/DAL/UserDAL.php');

// Create DAL
$database = new \Model\DAL\Database();
$sessionDAL = new \Model\DAL\SessionDAL();
$cookieDAL = new \Model\DAL\CookieDAL($database, $sessionDAL);
$userDAL = new \Model\DAL\UserDAL($database);

// Create view objects
$loginView = new \View\Login($cookieDAL, $sessionDAL);
$registerView = new \View\Register($sessionDAL);
$dateTimeView = new \View\DateTime();
$layoutView = new \View\Layout();

$loginController = new \Controller\Login($loginView, $cookieDAL, $sessionDAL, $userDAL);
$registerController = new \Controller\Register($registerView, $userDAL, $sessionDAL);

$cookieDAL->isUserCookieValid();

$sessionExists = $sessionDAL->isUserSessionActive();
$cookieExists = $cookieDAL->isUserCookieActive();
$validBrowser = $cookieDAL->userBrowserValid();

$userLoggedIn = $sessionExists || $cookieExists && $validBrowser;

if ($userLoggedIn) {
    $loginController->doLogout();
} else {
    if (isset($_GET["register"])) {
        $registerController->doRegister();
    } else {
        $loginController->doLogin();
    }
}

$layoutView->render($userLoggedIn, $loginView, $registerView, $dateTimeView);

// TEMP
if (isset($_SERVER, $_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] == 'localhost') {
    // development
}
