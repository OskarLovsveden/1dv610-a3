<?php

namespace Controller;

require_once('model/DAL/UserDAL.php');
require_once('model/RememberMeCookie.php');

class Login {
    private $loginView;
    private $sessionDAL;
    private $cookieDAL;
    private $userDAL;

    public function __construct(\View\Login $loginView, \Model\DAL\CookieDAL $cookieDAL, \Model\DAL\SessionDAL $sessionDAL, \Model\DAL\UserDAL $userDAL) {
        $this->loginView = $loginView;
        $this->sessionDAL = $sessionDAL;
        $this->cookieDAL = $cookieDAL;
        $this->userDAL = $userDAL;
    }

    public function doLogin() {

        if ($this->loginView->userWantsToLogin()) {
            try {
                $this->loginView->validateLoginForm();
                $credentials = $this->loginView->getLoginCredentials();
                $username = $credentials->getUsername();
                $this->userDAL->loginUser($credentials);

                if ($credentials->getKeepUserLoggedIn()) {
                    $rememberMeCookie = new \Model\RememberMeCookie($username);
                    $cookieName = $rememberMeCookie->getCookieName();
                    $cookiePassword = $rememberMeCookie->getCookiePassword();

                    var_dump("cookieName från remme", $cookieName);
                    var_dump("cookiePass från remme", $cookiePassword);

                    $this->loginView->setUserCookies($cookieName, $cookiePassword);
                    $this->cookieDAL->saveUserCookie($cookieName, $cookiePassword);

                    $this->sessionDAL->setInputFeedbackMessage("Welcome and you will be remembered");
                } else {
                    $this->sessionDAL->setInputFeedbackMessage("Welcome");
                }

                $this->sessionDAL->setUserSession($username);
                $this->sessionDAL->setUserBrowser();
                // $this->loginView->reloadPage();
            } catch (\Exception $e) {
                $this->sessionDAL->setInputFeedbackMessage($e->getMessage());
                $this->loginView->reloadPage();
            }
        }
    }

    public function doLogout() {
        if ($this->loginView->userWantsToLogout()) {
            if ($this->sessionDAL->isUserSessionActive()) {
                $this->sessionDAL->unsetUserSession();
            }

            if ($this->loginView->isUserCookieNameSet()) {
                $cookieName = $this->loginView->getUserCookieName();
                $cookiePassword = $this->loginView->getUserCookiePassword();

                if ($this->cookieDAL->validCookie($cookieName, $cookiePassword)) {
                    // if ($this->cookieDAL->isUserCookieActive($cookieName, $cookiePassword)) {
                    $this->loginView->unsetUserCookies();
                }
            }

            $this->sessionDAL->setInputFeedbackMessage("Bye bye!");
            $this->loginView->reloadPage();
        }
    }
}