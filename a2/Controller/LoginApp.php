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
    private $loginView;
    private $registerView;
    private $dateTimeView;
    private $layoutView;

    private $loginController;
    private $registerController;

    private $flashMessage;
    private $userLoggedIn;

    public function __construct(\Authenticator $authenticator) {

        $this->loginView = new \View\Login($authenticator);
        $this->registerView = new \View\Register($authenticator);
        $this->dateTimeView = new \View\DateTime();
        $this->layoutView = new \View\Layout();
        $this->flashMessage = new \FlashMessage();

        $this->loginController = new \Controller\Login($authenticator, $this->loginView, $this->flashMessage);
        $this->registerController = new \Controller\Register($this->registerView, $authenticator, $this->flashMessage);
    }

    public function run() {
        $this->userLoggedIn = $this->loginController->isUserLoggedIn();

        if ($this->userLoggedIn) {
            $this->loginController->doLogout();
        } else {
            if ($this->layoutView->navigatedToRegisterPage()) {
                $this->registerController->doRegister();
            } else {
                $this->loginController->doLogin();
            }
        }

        $this->layoutView->render($this->userLoggedIn, $this->loginView, $this->registerView, $this->dateTimeView);
    }
}
