<?php

namespace Controller;

require_once("Controller/Game.php");
require_once("model/RandomNumber.php");
require_once("view/Game.php");

class GameApp {
    private $authenticator;

    public function __construct(\Authenticator $authenticator) {
        $this->authenticator = $authenticator;
    }

    public function run() {

        $rn = new \Model\RandomNumber(1, 100);

        $gv = new \View\Game($rn);
        $gameHTML = $gv->getHTML();

        $gc = new \Controller\Game($this->authenticator, $gv, $rn);
        $gc->doGuess();

        echo $gameHTML;
    }
}
