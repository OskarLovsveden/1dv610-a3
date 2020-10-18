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

    public function __construct() {
        $this->flashMessage = new \FlashMessage();
    }

    public function run() {

        $rn = new \A3\Model\RandomNumber(1, 100);

        $gv = new \A3\View\Game($this->flashMessage, $rn);
        $lv = new \A3\View\Layout($gv);

        $gc = new \A3\Controller\Game($this->flashMessage, $gv, $rn);
        $gc->doGuess();

        $lv->renderHTML();
    }
}
