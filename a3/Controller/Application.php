<?php

namespace A3\Controller;

require_once('../common/flash-message/FlashMessage.php');
require_once('../common/session-storage/SessionStorage.php');

// Controller
require_once("controller/Game.php");

// Model
require_once("model/RandomNumber.php");

// View
require_once("view/Layout.php");
require_once("view/Game.php");

class Application {
    private $flashMessage;
    private $randomNumber;

    private $gameView;
    private $layoutView;

    private $gameController;

    public function __construct() {
        $this->flashMessage = new \FlashMessage();
        $this->randomNumber = new \A3\Model\RandomNumber(1, 100);

        $this->gameView = new \A3\View\Game($this->flashMessage, $this->randomNumber);
        $this->layoutView = new \A3\View\Layout($this->gameView);

        $this->gameController = new \A3\Controller\Game($this->flashMessage, $this->gameView, $this->randomNumber);
    }

    public function run() {
        $this->gameController->doGuess();
        $this->layoutView->renderHTML();
    }
}
