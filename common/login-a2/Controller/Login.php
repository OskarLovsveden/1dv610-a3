<?php

namespace Controller;

require_once('model/DAL/UserDAL.php');
require_once('model/RememberMeCookie.php');

class Login {
    private $loginView;
    private $sessionDAL;
    private $cookieDAL;
    private $userDAL;
    private $userBrowser;

    public function __construct(\View\Login $loginView, \Model\DAL\CookieDAL $cookieDAL, \Model\DAL\SessionDAL $sessionDAL, \Model\DAL\UserDAL $userDAL) {
        $this->loginView = $loginView;
        $this->sessionDAL = $sessionDAL;
        $this->cookieDAL = $cookieDAL;
        $this->userDAL = $userDAL;
        $this->userBrowser = $_SERVER['HTTP_USER_AGENT'];
    }

    public function isUserLoggedIn(): bool {
        if ($this->sessionDAL->isUserSessionActive()) {
            return true;
        }
        if ($this->checkIfCookieExists()) {
            $this->tryToLoginWithCookie();
            $this->sessionDAL->setUserSession($this->loginView->getUserCookieName());
            $this->loginView->reloadPage();
        }
        return false;
    }

    public function checkIfCookieExists() {
        return $this->loginView->isUserCookieNameSet() && $this->loginView->isUserCookiePasswordSet();
    }

    public function tryToLoginWithCookie() {
        $cookieName = $this->loginView->getUserCookieName();
        $cookiePassword = $this->loginView->getUserCookiePassword();

        try {
            $this->cookieDAL->validCookie($cookieName, $cookiePassword, $this->userBrowser);
            $this->sessionDAL->setInputFeedbackMessage("Welcome back with cookie");
        } catch (\Exception $e) {
            $this->sessionDAL->setInputFeedbackMessage($e->getMessage());
        }
    }

    public function doLogin() {

        if ($this->loginView->userWantsToLogin()) {
            try {
                $this->loginView->validateLoginForm();
                $credentials = $this->loginView->getLoginCredentials();
                $username = $credentials->getUsername();

                if ($credentials->getKeepUserLoggedIn()) {
                    $rememberMeCookie = new \Model\RememberMeCookie($username, $this->userBrowser);
                    $cookieName = $rememberMeCookie->getCookieName();
                    $cookiePassword = $rememberMeCookie->getCookiePassword();
                    $cookieBrowser = $rememberMeCookie->getUserBrowser();

                    $this->loginView->setUserCookies($cookieName, $cookiePassword);
                    $this->cookieDAL->saveUserCookie($cookieName, $cookiePassword, $cookieBrowser);

                    $this->sessionDAL->setInputFeedbackMessage("Welcome and you will be remembered");
                } else {
                    $this->sessionDAL->setInputFeedbackMessage("Welcome");
                }

                $this->sessionDAL->setUserSession($username);
                $this->sessionDAL->setUserBrowser($this->userBrowser);

                $this->userDAL->loginUser($credentials);

                $this->loginView->reloadPage();
            } catch (\Exception $e) {
                $this->sessionDAL->setInputFeedbackMessage($e->getMessage());
                $this->loginView->reloadPage();
            }
        }
    }

    public function doLogout() {
        if ($this->loginView->userWantsToLogout()) {
            if ($this->sessionDAL->isUserSessionActive() && $this->sessionDAL->userBrowserValid($this->userBrowser)) {
                $this->sessionDAL->unsetUserSession();
            }

            if ($this->loginView->isUserCookieNameSet()) {
                $cookieName = $this->loginView->getUserCookieName();
                $cookiePassword = $this->loginView->getUserCookiePassword();

                if ($this->cookieDAL->validCookie($cookieName, $cookiePassword, $this->userBrowser)) {
                    $this->loginView->unsetUserCookies();
                }
            }

            $this->sessionDAL->setInputFeedbackMessage("Bye bye!");
            $this->loginView->reloadPage();
        }
    }
}