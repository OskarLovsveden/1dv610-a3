<?php

namespace Controller;

require_once('../common/flash-message/FlashMessage.php');
require_once('../common/session-storage/SessionStorage.php');

// Controller
require_once("Controller/Game.php");

// Model
require_once("model/RandomNumber.php");

// View
require_once("view/Layout.php");
require_once("view/Game.php");

class GameApp {
    private $flashMessage;

    public function __construct() {
        $this->flashMessage = new \FlashMessage();
    }

    public function run() {

        $rn = new \Model\RandomNumber(1, 100);

        $gv = new \View\Game($this->flashMessage, $rn);
        $lv = new \View\Layout($gv);

        $gc = new \Controller\Game($this->flashMessage, $gv, $rn);
        $gc->doGuess();

        $lv->renderHTML();
    }
}
