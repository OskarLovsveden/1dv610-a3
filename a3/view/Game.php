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

    public function userWantsToGuess() {
        return isset($_POST[self::$postGuess]);
    }

    public function getUserGuess() {
        return $_POST[self::$userGuess];
    }

    public function getHTML() {
        return '
        <form method="post"> 
        <fieldset>
        <legend>' . $this->title . '</legend>
        <p>' . $this->message . '</p>
        
        <label for="' . self::$userGuess . '">Username :</label>
        <input type="text" id="' . self::$userGuess . '" name="' . self::$userGuess . '"/>
        
        <input type="submit" name="' . self::$postGuess . '" value="Login" />
        </fieldset>
        </form>
        ';
    }
}