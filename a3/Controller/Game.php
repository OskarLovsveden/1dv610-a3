<?php

namespace A3\Controller;

class Game {
    private static $guessSessionIndex = __CLASS__ . "::guessSessionIndex";

    private $guessSession;
    private $flashMessage;
    private $gameView;
    private $randomNumber;

    public function __construct(\FlashMessage $flashMessage, \A3\View\Game $gameView, \A3\Model\RandomNumber $randomNumber) {
        $this->guessSession = new \SessionStorage(self::$guessSessionIndex);
        $this->flashMessage = $flashMessage;
        $this->gameView = $gameView;
        $this->randomNumber = $randomNumber;
    }

    public function doGuess() {
        if (!$this->guessSession->hasValue()) {
            $numberToBeGuessed = strval($this->randomNumber->getValueToGuess());
            $this->guessSession->store($numberToBeGuessed);
        }

        if ($this->gameView->userWantsToGuess()) {
            try {
                $this->gameView->validateGuessForm();

                $guess = $this->gameView->getGuess();
                $numberToBeGuessed = $this->guessSession->getValue();

                if (is_numeric($guess)) {
                    if (intval($guess) < $numberToBeGuessed) {
                        $this->flashMessage->set("Number is higher");
                    }
                    if (intval($guess) > $numberToBeGuessed) {
                        $this->flashMessage->set("Number is lower");
                    }
                    if (intval($guess) == $numberToBeGuessed) {
                        $this->flashMessage->set("You guessed it!");
                        $this->guessSession->removeValue();
                    }
                } else {
                    $this->flashMessage->set("Only input a number");
                }
            } catch (\Exception $e) {
                $this->flashMessage->set($e->getMessage());
            }
        }
    }
}
