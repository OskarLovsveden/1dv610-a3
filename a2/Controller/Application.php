<?php

namespace A2\Controller;

require_once('../common/flash-message/FlashMessage.php');
require_once('../common/session-storage/SessionStorage.php');

require_once('controller/Login.php');
require_once('controller/Register.php');

require_once('view/Login.php');
require_once('view/Register.php');
require_once('view/DateTime.php');
require_once('view/Layout.php');

class Application {
    private static $usernameInputIndex = __CLASS__ . '::usernameInputIndex';

    private $loginView;
    private $registerView;
    private $dateTimeView;
    private $layoutView;

    private $loginController;
    private $registerController;

    private $authenticator;
    private $flashMessage;

    public function __construct(\Authenticator $authenticator) {
        $this->authenticator = $authenticator;
        $this->flashMessage = new \FlashMessage();

        $usernameInputSession = new \SessionStorage(self::$usernameInputIndex);

        $this->loginView = new \A2\View\Login($this->flashMessage, $usernameInputSession);
        $this->registerView = new \A2\View\Register($this->flashMessage, $usernameInputSession);
        $this->dateTimeView = new \A2\View\DateTime();
        $this->layoutView = new \A2\View\Layout();

        $this->loginController = new \A2\Controller\Login($this->loginView, $authenticator, $this->flashMessage);
        $this->registerController = new \A2\Controller\Register($this->registerView, $authenticator, $this->flashMessage);
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
