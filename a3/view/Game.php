<?php

namespace View;

class Game {
    private static $userGuess = "userGuess";
    private static $postGuess = "postGuess";

    private $randomNumber;
    private $title;
    private $message = "";

    public function __construct(\Model\RandomNumber $randomNumber) {
        $this->randomNumber = $randomNumber;
        $this->title = "Guess a number between " . $this->randomNumber->getMinValue() . "-" . $this->randomNumber->getMaxValue();
    }

    public function userWantsToGuess(): bool {
        return isset($_POST[self::$postGuess]);
    }

    public function getUserGuess(): string {
        if (!$_POST[self::$userGuess]) {
            throw new \Exception("Please input a guess");
        }
        return $_POST[self::$userGuess];
    }

    public function getHTML(): string {
        return '
        <form method="post"> 
        <fieldset>
        <legend>' . $this->title . '</legend>
        <p>' . $this->message . '</p>
        
        <label for="' . self::$userGuess . '">Write your guess here :</label>
        <input type="text" id="' . self::$userGuess . '" name="' . self::$userGuess . '"/>
        
        <input type="submit" name="' . self::$postGuess . '" value="Guess" />
        </fieldset>
        </form>
        ';
    }
}
