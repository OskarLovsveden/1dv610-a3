<?php

namespace A3\View;

class Game {
    private static $number = __CLASS__ . "::number";
    private static $guess = __CLASS__ . "::guess";

    private static $save = __CLASS__ . "::save";
    private static $reset = __CLASS__ . "::reset";

    private $title;
    private $message;

    private $flashMessage;
    private $gameState;

    public function __construct(\FlashMessage $flashMessage, \A3\Model\GameState $gameState) {
        $this->title = "Guess a number between " . $gameState->getMinNumberToBeGuessed() . "-" . $gameState->getMaxNumberToBeGuessed();

        $this->flashMessage = $flashMessage;
        $this->gameState = $gameState;
    }

    public function userWantsToGuess(): bool {
        return isset($_POST[self::$guess]);
    }

    public function validateGuessForm() {
        if (!$_POST[self::$number]) {
            throw new \Exception("Please enter a guess to play");
        }
    }

    public function getGuess(): string {
        return $_POST[self::$number];
    }

    public function userWantsToSaveHighScore() {
        return isset($_POST[self::$save]);
    }

    public function userWantsToResetGame() {
        return isset($_POST[self::$reset]);
    }

    public function redirectIndex() {
        header("Location: /a3");
    }

    public function getHTML(bool $gameWon): string {
        $this->message = $this->flashMessage->get();

        if ($gameWon) {
            return $this->gameWonHTML();
        } else {
            return $this->playGameHTML();
        }
    }

    private function gameWonHTML(): string {
        $correctGuess = $this->gameState->getCorrectAnswer();
        $amountOfTries = $this->gameState->getAmountOfTries();

        $ret = '<h1>Correct number was ' . $correctGuess . '!</h1>';
        $ret .= '<h3>You finished in ' . $amountOfTries . ' number of tries!</h3>';
        $ret .= '<form method="post"><input type="submit" name="' . self::$save . '" value="Save to highscore" /></form>';
        $ret .= '<form method="post"><input type="submit" name="' . self::$reset . '" value="Reset game" /></form>';

        return $ret;
    }

    private function playGameHTML(): string {
        return '
        <form method="post"> 
        <fieldset>
        <legend>' . $this->title . '</legend>
        <p>' . $this->message . '</p>
        
        <label for="' . self::$number . '">Write your guess here :</label>
        <input autofocus type="text" id="' . self::$number . '" name="' . self::$number . '"/>
        
        <input type="submit" name="' . self::$guess . '" value="Guess" />
        </fieldset>
        </form>
        ' . 'Number To be guessed: ' . $this->gameState->getNumberToBeGuessed() . '
        ';
    }
}
