<?php

require_once('model/DAL/Database.php');
require_once('model/DAL/CookieDAL.php');
require_once('model/DAL/SessionDAL.php');
require_once('model/DAL/UserDAL.php');

require_once('model/Username.php');
require_once('model/Password.php');
require_once('model/User.php');
require_once('model/Credentials.php');
require_once('model/RememberMeCookie.php');

class Authenticator {
    private $database;
    private $sessionDAL;
    private $cookieDAL;
    private $userDAL;

    public function __construct() {
        $this->database = new \Model\DAL\Database();
        $this->sessionDAL = new \Model\DAL\SessionDAL();
        $this->cookieDAL = new \Model\DAL\CookieDAL($this->database);
        $this->userDAL = new \Model\DAL\UserDAL($this->database);
    }

    public function setInputUserValue(string $username) {
        return $this->sessionDAL->setInputUserValue($username);
    }

    public function getInputUserValue() {
        return $this->sessionDAL->getInputUserValue();
    }

    public function isInputUserValueSet(): bool {
        return $this->sessionDAL->isInputUserValueSet();
    }

    public function isUserSessionActive(): bool {
        return $this->sessionDAL->isUserSessionActive();
    }

    public function setUserSession(string $username) {
        return $this->sessionDAL->setUserSession($username);
    }

    public function unsetUserSession() {
        return $this->sessionDAL->unsetUserSession();
    }

    public function setInputFeedbackMessage(string $message) {
        return $this->sessionDAL->setInputFeedbackMessage($message);
    }

    public function unsetInputFeedbackMessage() {
        return $this->sessionDAL->unsetInputFeedbackMessage();
    }

    public function getInputFeedbackMessage() {
        return $this->sessionDAL->getInputFeedbackMessage();
    }

    public function setUserBrowser(string $userBrowser) {
        return $this->sessionDAL->setUserBrowser($userBrowser);
    }

    public function userBrowserValid(string $userBrowser): bool {
        return $this->sessionDAL->userBrowserValid($userBrowser);
    }

    public function saveUserCookieAndReturnPassword(string $username, string $userBrowser) {
        $rememberMeCookie = new \Model\RememberMeCookie($username, $userBrowser);
        $this->cookieDAL->saveUserCookie($rememberMeCookie);
        return $rememberMeCookie->getCookiePassword();
    }

    public function getUserCookie(string $username) {
        return $this->cookieDAL->getUserCookie($username);
    }

    public function validCookie(string $cookieName, string $cookiePassword, string $userBrowser): bool {
        return $this->cookieDAL->validCookie($cookieName, $cookiePassword, $userBrowser);
    }

    public function registerUser(string $username, string $password) {
        $name = new \Model\Username($username);
        $pass = new \Model\Password($password);

        $user = new \Model\User($name, $pass);
        return $this->userDAL->registerUser($user);
    }

    public function loginUser(string $username, string $password, bool $keepUserLoggedIn) {
        $name = new \Model\Username($username);
        $pass = new \Model\Password($password);

        $credentials = new \Model\Credentials($name, $pass, $keepUserLoggedIn);
        return $this->userDAL->loginUser($credentials);
    }
}
