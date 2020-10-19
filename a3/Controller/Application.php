<?php

namespace A3\Controller;

// Common
require_once('../common/flash-message/FlashMessage.php');
require_once('../common/session-storage/SessionStorage.php');
require_once('GameSettings.php');

// Controller
require_once("controller/Login.php");
require_once("controller/Register.php");
require_once("controller/Game.php");

// Model
require_once("model/DAL/HighScoreDAL.php");
require_once("model/HighScore.php");
require_once("model/HighScoreItem.php");
require_once("model/RandomNumber.php");
require_once("model/GameState.php");

// View
require_once("view/Login.php");
require_once("view/Register.php");
require_once("view/Layout.php");
require_once("view/Game.php");
require_once("view/HighScore.php");

class Application {
    private static $usernameInputIndex = __NAMESPACE__ . __CLASS__ . '::usernameInputIndex';

    private $authenticator;
    private $flashMessage;
    private $randomNumber;
    private $gameState;

    private $highScoreDAL;

    private $loginView;
    private $registerView;
    private $gameView;
    private $highScoreView;
    private $layoutView;

    private $gameController;

    public function __construct(\Authenticator $authenticator) {
        $this->authenticator = $authenticator;
        $this->flashMessage = new \FlashMessage();
        $this->randomNumber = new \A3\Model\RandomNumber(1, 200);
        $this->gameState = new \A3\Model\GameState($this->randomNumber);

        $this->highScoreDAL = new \A3\Model\DAL\HighScoreDAL(new \GameSettings());
        $highScore = $this->highScoreDAL->get();
        $this->highScoreView = new \A3\View\HighScore($highScore);

        $usernameInputSession = new \SessionStorage(self::$usernameInputIndex);

        $this->loginView = new \A3\View\Login($this->flashMessage, $usernameInputSession);
        $this->registerView = new \A3\View\Register($this->flashMessage, $usernameInputSession);
        $this->gameView = new \A3\View\Game($this->flashMessage, $this->gameState);
        $this->layoutView = new \A3\View\Layout($this->loginView, $this->registerView, $this->gameView, $this->highScoreView);

        $this->loginController = new \A3\Controller\Login($this->loginView, $authenticator, $this->flashMessage);
        $this->registerController = new \A3\Controller\Register($this->registerView, $authenticator, $this->flashMessage);
        $this->gameController = new \A3\Controller\Game($authenticator, $this->flashMessage, $this->gameState, $this->highScoreDAL, $this->gameView);
    }

    public function run() {
        $userLoggedIn = $this->authenticator->isUserLoggedIn();
        $gameWon = $this->gameState->isGameWon();

        if ($gameWon) {
            $this->gameController->doSaveHighScore();
            $this->gameController->doResetGame();
        } else {
            $this->gameController->doPlay();
        }

        if ($userLoggedIn) {
            $this->loginController->doLogout();
        } else {
            $this->registerController->doRegister();
            $this->loginController->doLogin();
        }

        $this->layoutView->renderHTML($userLoggedIn, $gameWon);
    }
}
