<?php

namespace A3\Model;

class GameState {
    private static $numberToBeGuessedSessionIndex = __CLASS__ . "::numberToBeGuessedSessionIndex";
    private static $correctGuessSessionIndex = __CLASS__ . "::correctGuessSessionIndex";
    private static $amountOfTriesSessionIndex = __CLASS__ . "::amountOfTriesSessionIndex";
    private static $gameWonSessionIndex = __CLASS__ . "::gameWonSessionIndex";

    private $randomNumber;

    private $numberToBeGuessedSession;
    private $amountOfTriesSession;
    private $correctGuessSession;
    private $gameWonSession;

    public function __construct(\A3\Model\RandomNumber $randomNumber) {
        $this->randomNumber = $randomNumber;

        $this->numberToBeGuessedSession = new \SessionStorage(self::$numberToBeGuessedSessionIndex);
        $this->amountOfTriesSession = new \SessionStorage(self::$amountOfTriesSessionIndex);
        $this->correctGuessSession = new \SessionStorage(self::$correctGuessSessionIndex);
        $this->gameWonSession = new \SessionStorage(self::$gameWonSessionIndex);

        if (!$this->numberToBeGuessedSession->hasValue()) {
            $numberToBeGuessed = strval($randomNumber->getValueToGuess());
            $this->numberToBeGuessedSession->store($numberToBeGuessed);
        }
    }

    public function getMinNumberToBeGuessed(): int {
        return $this->randomNumber->getMinValue();
    }

    public function getMaxNumberToBeGuessed(): int {
        return $this->randomNumber->getMaxValue();
    }

    public function getNumberToBeGuessed(): int {
        return $this->numberToBeGuessedSession->getValue();
    }

    public function isGameWon(): bool {
        if ($this->gameWonSession->hasValue()) {
            return $this->gameWonSession->getValue();
        }

        return false;
    }

    public function increaseAmountOfTriesByOne() {
        if ($this->amountOfTriesSession->hasValue()) {
            $currentAmountOfTries = $this->amountOfTriesSession->getValue();
            $newAmountOfTries = $currentAmountOfTries + 1;
            $this->amountOfTriesSession->store($newAmountOfTries);
        } else {
            $this->amountOfTriesSession->store(1);
        }
    }

    public function getCorrectAnswer() {
        return $this->correctGuessSession->getValue();
    }

    public function getAmountOfTries() {
        return $this->amountOfTriesSession->getValue();
    }

    public function gameWasWon() {
        $this->gameWonSession->store(true);
    }

    public function saveCorrectGuess(int $toBeSaved) {
        $this->correctGuessSession->store($toBeSaved);
    }

    public function reset() {
        $this->numberToBeGuessedSession->removeValue();
        $this->amountOfTriesSession->removeValue();
        $this->correctGuessSession->removeValue();
        $this->gameWonSession->removeValue();
    }
}
