<?php

namespace Controller;

require_once('../common/flash-message/FlashMessage.php');

require_once('Controller/Login.php');
require_once('Controller/Register.php');

require_once('view/Login.php');
require_once('view/Register.php');
require_once('view/DateTime.php');
require_once('view/Layout.php');

class LoginApp {
    private $authenticator;

    private $loginView;
    private $registerView;
    private $dateTimeView;
    private $layoutView;

    private $loginController;
    private $registerController;

    private $flashMessage;

    public function __construct(\Authenticator $authenticator) {
        $this->authenticator = $authenticator;
        $this->flashMessage = new \FlashMessage();

        $this->loginView = new \View\Login($this->flashMessage);
        $this->registerView = new \View\Register($this->flashMessage);
        $this->dateTimeView = new \View\DateTime();
        $this->layoutView = new \View\Layout();

        $this->loginController = new \Controller\Login($this->loginView, $authenticator, $this->flashMessage);
        $this->registerController = new \Controller\Register($this->registerView, $authenticator, $this->flashMessage);
    }

    public function run() {

        try {
            $isCookieNameSet = $this->loginView->isUserCookieNameSet();
            $isCookiePasswordSet = $this->loginView->isUserCookiePasswordSet();

            // var_dump($isCookieNameSet, $isCookiePasswordSet);
            // exit;

            if ($isCookieNameSet && $isCookiePasswordSet) {
                $cookieUsername = $this->loginView->getUserCookieName();
                $cookiePassword = $this->loginView->getUserCookiePassword();
                $this->authenticator->loginWithCookie($cookieUsername, $cookiePassword);
                $this->flashMessage->set("Welcome back with cookie");
                // $this->loginView->redirectIndex();
            }
        } catch (\Exception $e) {
            $this->flashMessage->set($e->getMessage());
        }

        $userLoggedIn = $this->authenticator->isUserLoggedIn();

        if ($userLoggedIn) {
            $this->loginController->doLogout();
        } else {
            if ($this->layoutView->navigatedToRegisterPage()) {
                $this->registerController->doRegister();
            } else {
                $this->loginController->doLogin();
            }
        }

        $this->layoutView->render($userLoggedIn, $this->loginView, $this->registerView, $this->dateTimeView);
    }
}
