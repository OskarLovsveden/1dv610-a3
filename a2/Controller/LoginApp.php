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
