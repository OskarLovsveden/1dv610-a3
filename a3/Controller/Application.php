<?php

namespace A3\Controller;

// Common
require_once('../common/flash-message/FlashMessage.php');
require_once('../common/session-storage/SessionStorage.php');
require_once('GameSettings.php');

// Controller
require_once("controller/Game.php");

// Model
require_once("model/DAL/HighScoreDAL.php");
require_once("model/HighScore.php");
require_once("model/HighScoreItem.php");
require_once("model/RandomNumber.php");
require_once("model/GameState.php");

// View
require_once("view/Layout.php");
require_once("view/Game.php");
require_once("view/HighScore.php");

class Application {
    private $flashMessage;
    private $randomNumber;
    private $gameState;

    private $highScoreDAL;

    private $gameView;
    private $highScoreView;
    private $layoutView;

    private $gameController;

    public function __construct(\Authenticator $authenticator) {
        $this->flashMessage = new \FlashMessage();
        $this->randomNumber = new \A3\Model\RandomNumber(1, 100);
        $this->gameState = new \A3\Model\GameState($this->randomNumber);

        $this->highScoreDAL = new \A3\Model\DAL\HighScoreDAL(new \GameSettings());
        $highScore = $this->highScoreDAL->get();
        $this->highScoreView = new \A3\View\HighScore($highScore);

        $this->gameView = new \A3\View\Game($this->flashMessage, $this->gameState);
        $this->layoutView = new \A3\View\Layout($this->gameView, $this->highScoreView);

        $this->gameController = new \A3\Controller\Game($authenticator, $this->flashMessage, $this->gameState, $this->gameView);
    }

    public function run() {
        $gameWon = $this->gameState->isGameWon();

        if ($gameWon) {
            $this->gameController->doSaveHighScore();
            $this->gameController->doResetGame();
        } else {
            $this->gameController->doPlay();
        }

        $this->layoutView->renderHTML($gameWon);
    }
}
