<?php

namespace Controller;

class Login {
    private static $usernameInputIndex = __CLASS__ . '::usernameInputIndex';

    private $loginView;
    private $authenticator;
    private $flashMessage;
    private $usernameInputSession;

    public function __construct(\Authenticator $authenticator, \View\Login $loginView, \FlashMessage $flashMessage) {
        $this->authenticator = $authenticator;
        $this->loginView = $loginView;
        $this->flashMessage = $flashMessage;
        $this->usernameInputSession = new \SessionStorage(self::$usernameInputIndex);
    }

    public function doLogin() {

        // User wants to login
        if ($this->loginView->userWantsToLogin()) {
            try {
                // Form filled in
                $this->loginView->validateLoginForm();

                // Get username 
                $username = $this->loginView->getRequestUserName();
                // Get password
                $password = $this->loginView->getRequestPassword();
                // Check if user wants to be remembered
                $keepUserLoggedIn = $this->loginView->getRequestKeepMeLoggedIn();


                // user wanted to be remembered
                if ($keepUserLoggedIn) {
                    // login user and set session
                    $this->authenticator->loginUser($username, $password);

                    // store user cookie
                    $this->authenticator->keepUserLoggedIn($username);

                    // ask authenticator for cookie password
                    $cookiePassword = $this->authenticator->getCookiePassword();


                    // $userBrowser = $this->loginView->getUserBrowser();
                    // $cookiePassword = $this->authenticator->saveUserCookieAndReturnPassword($username, $userBrowser);


                    // Set view cookie
                    $this->loginView->setUserCookies($username, $cookiePassword);

                    $this->flashMessage->set("Welcome and you will be remembered");
                } else {
                    $this->flashMessage->set("Welcome");
                }

                // $this->authenticator->setUserSession($username);

                // $userBrowser = $this->loginView->getUserBrowser();
                // $this->authenticator->setUserBrowser($userBrowser);

                $this->loginView->redirectIndex();
            } catch (\Exception $e) {
                $this->flashMessage->set($e->getMessage());
                $this->loginView->redirectIndex();
            }
        }
    }

    public function doLogout() {
        if ($this->loginView->userWantsToLogout()) {
            if ($this->authenticator->isUserSessionActive()) {
                $this->authenticator->unsetUserSession();
            }

            if ($this->loginView->isUserCookieNameSet()) {
                $this->loginView->unsetUserCookies();
            }

            $this->flashMessage->set("Bye bye!");
            $this->loginView->redirectIndex();
        }
    }

    public function isUserLoggedIn(): bool {
        if ($this->authenticator->isUserSessionActive()) {
            return true;
        }
        if ($this->checkIfCookieExists()) {
            $this->tryToLoginWithCookie();

            $cookieUsername = $this->loginView->getUserCookieName();
            $this->authenticator->setUserSession($cookieUsername);
            $this->loginView->redirectIndex();
        }
        return false;
    }

    private function checkIfCookieExists() {
        return $this->loginView->isUserCookieNameSet() && $this->loginView->isUserCookiePasswordSet();
    }

    private function tryToLoginWithCookie() {
        $cookieName = $this->loginView->getUserCookieName();
        $cookiePassword = $this->loginView->getUserCookiePassword();

        try {
            $userBrowser = $this->loginView->getUserBrowser();
            $this->authenticator->validCookie($cookieName, $cookiePassword, $userBrowser);
            $this->flashMessage->set("Welcome back with cookie");
        } catch (\Exception $e) {
            $this->flashMessage->set($e->getMessage());
        }
    }
}
