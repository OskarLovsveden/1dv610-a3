<?php

namespace Controller;

require_once('controller/Login.php');
require_once('controller/Register.php');

require_once('view/Login.php');
require_once('view/Register.php');
require_once('view/DateTime.php');
require_once('view/Layout.php');

class Application {
    private $authenticator;
    private $loginView;
    private $registerView;
    private $dateTimeView;
    private $layoutView;
    private $loginController;
    private $registerController;
    private $userLoggedIn;

    public function __construct(\Authenticator $authenticator) {
        $this->authenticator = $authenticator;
        $this->loginView = new \View\Login($authenticator);
        $this->registerView = new \View\Register($authenticator);
        $this->dateTimeView = new \View\DateTime();
        $this->layoutView = new \View\Layout();
        $this->loginController = new \Controller\Login($this->loginView, $authenticator);
        $this->registerController = new \Controller\Register($this->registerView, $authenticator);
    }

    public function run() {
        $this->userLoggedIn = $this->loginController->isUserLoggedIn();

        if ($this->userLoggedIn) {
            $this->loginController->doLogout();
        } else {
            if ($this->layoutView->wantsToRegister()) {
                $this->registerController->doRegister();
            } else {
                $this->loginController->doLogin();
            }
        }

        $this->layoutView->render($this->userLoggedIn, $this->loginView, $this->registerView, $this->dateTimeView);
    }
}