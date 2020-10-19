<?php

namespace A3\Controller;

class Game {
    private $authenticator;
    private $flashMessage;
    private $gameView;
    private $gameState;

    public function __construct(\Authenticator $authenticator, \FlashMessage $flashMessage, \A3\Model\GameState $gameState, \A3\View\Game $gameView) {
        $this->authenticator = $authenticator;
        $this->flashMessage = $flashMessage;
        $this->gameView = $gameView;
        $this->gameState = $gameState;
    }

    public function doPlay() {
        if ($this->gameView->userWantsToGuess()) {
            try {
                $this->gameView->validateGuessForm();

                $guess = $this->gameView->getGuess();
                $numberToBeMatched = $this->gameState->getNumberToBeGuessed();

                if (is_numeric($guess)) {
                    $guessAsInt = intval($guess);
                    $this->gameState->increaseAmountOfTriesByOne();

                    if ($guessAsInt < $numberToBeMatched) {
                        $this->flashMessage->set("Number is higher");
                    }
                    if ($guessAsInt > $numberToBeMatched) {
                        $this->flashMessage->set("Number is lower");
                    }
                    if ($guessAsInt == $numberToBeMatched) {
                        $this->gameState->gameWasWon();
                        $this->gameState->saveCorrectGuess($guessAsInt);
                    }
                } else {
                    $this->flashMessage->set("The guess has to be a number");
                }

                $this->gameView->redirectIndex();
            } catch (\Exception $e) {
                $this->flashMessage->set($e->getMessage());
                $this->gameView->redirectIndex();
            }
        }
    }

    public function doSaveHighScore() {
        if ($this->gameView->userWantsToSaveHighScore()) {
            $player = $this->authenticator->getLoggedInUser();
            $difficulty = $this->gameState->getMaxNumberToBeGuessed();
            $numberOfTries = $this->gameState->getAmountOfTries();

            $highScoreItem = new \A3\Model\HighScoreItem($player, $difficulty, $numberOfTries);

            $this->highScoreDAL->save($highScoreItem);
            $this->gameState->reset();
            $this->gameView->redirectIndex();
        }
    }

    public function doResetGame() {
        if ($this->gameView->userWantsToResetGame()) {
            $this->gameState->reset();
            $this->gameView->redirectIndex();
        }
    }
}
