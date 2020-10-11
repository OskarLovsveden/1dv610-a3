<?php

namespace Controller;

require_once('model/DAL/UserDAL.php');

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
                    $this->cookieDAL->setUserCookies($username);
                    $this->cookieDAL->saveUserCookie();
                    $this->sessionDAL->setInputFeedbackMessage("Welcome and you will be remembered");
                } else {
                    $this->sessionDAL->setInputFeedbackMessage("Welcome");
                }

                $this->sessionDAL->setUserSession($username);
                $this->sessionDAL->setUserBrowser();
                $this->loginView->reloadPage();
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

            if ($this->cookieDAL->isUserCookieActive()) {
                $this->cookieDAL->unsetUserCookies();
            }

            $this->sessionDAL->setInputFeedbackMessage("Bye bye!");
            $this->loginView->reloadPage();
        }
    }
}
