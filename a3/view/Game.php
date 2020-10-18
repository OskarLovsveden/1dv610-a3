<?php

namespace A3\View;

class Game {
    private static $number = __CLASS__ . "number";
    private static $guess = __CLASS__ . "guess";

    private $title;
    private $message;

    private $flashMessage;

    public function __construct(\FlashMessage $flashMessage, \A3\Model\RandomNumber $randomNumber) {
        $this->title = "Guess a number between " . $randomNumber->getMinValue() . "-" . $randomNumber->getMaxValue();

        $this->flashMessage = $flashMessage;
    }

    public function userWantsToGuess(): bool {
        return isset($_POST[self::$number]);
    }

    public function validateGuessForm() {
        if (!$_POST[self::$number]) {
            throw new \Exception("Please enter a guess to play");
        }
    }

    public function getGuess(): string {
        return $_POST[self::$number];
    }

    public function getHTML(): string {
        $this->message = $this->flashMessage->get();

        return '
        <form method="post"> 
        <fieldset>
        <legend>' . $this->title . '</legend>
        <p>' . $this->message . '</p>
        
        <label for="' . self::$number . '">Write your guess here :</label>
        <input type="text" id="' . self::$number . '" name="' . self::$number . '"/>
        
        <input type="submit" name="' . self::$guess . '" value="Guess" />
        </fieldset>
        </form>
        ';
    }
}
