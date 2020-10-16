<?php

namespace Controller;

class Game {
    private static $randomNumberSessionIndex = __CLASS__ . "::randNumVal";

    private $authenticator;
    private $gameView;
    private $randomNumber;

    public function __construct(\Authenticator $authenticator, \View\Game $gameView, \Model\RandomNumber $randomNumber) {
        $this->authenticator = $authenticator;
        $this->gameView = $gameView;
        $this->randomNumber = $randomNumber;
    }

    public function doGuess() {
        // if (!isset($_SESSION[self::$randomNumberSessionIndex])) {
        //     $_SESSION[self::$randomNumberSessionIndex] = $this->randomNumber->getValueToGuess();
        // }
        if (!$this->authenticator->isSessionIndexSet(self::$randomNumberSessionIndex)) {
            $numberToBeGuessed = strval($this->randomNumber->getValueToGuess());
            $this->authenticator->setSessionIndex(self::$randomNumberSessionIndex, $numberToBeGuessed);
            // $_SESSION[self::$randomNumberSessionIndex] = $this->randomNumber->getValueToGuess();
        }
        var_dump($_SESSION);
        echo "<br/>";

        if ($this->gameView->userWantsToGuess()) {
            try {
                $guess = $this->gameView->getUserGuess();
                $numberToBeGuessed = $this->authenticator->getSessionIndex(self::$randomNumberSessionIndex);

                echo $guess . "<br/>";
                echo $numberToBeGuessed . "<br/>";

                if (is_numeric($guess)) {
                    if (intval($guess) < $numberToBeGuessed) {
                        throw new \Exception("Number is higher");
                    }
                    if (intval($guess) > $numberToBeGuessed) {
                        throw new \Exception("Number is lower");
                    }
                    if (intval($guess) == $numberToBeGuessed) {
                        echo "CORRECT MY MAN";
                        unset($_SESSION[self::$randomNumberSessionIndex]);
                    }
                } else {
                    throw new \Exception("Only input a number");
                }
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }
    }
}
