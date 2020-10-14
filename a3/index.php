<?php

session_start();

require_once("model/RandomNumber.php");

$minNumber = 1;
$maxNumber = 100;
$randValueSessionIndex = "randNumVal";
$userGuess = "userGuess";
$postGuess = "postGuess";
$legendTitle = "Guess a number between " . $minNumber . "-" . $maxNumber . "";
$message = "";

$rn = new \Model\RandomNumber($minNumber, $maxNumber);

if (!isset($_SESSION[$randValueSessionIndex])) {
    $_SESSION[$randValueSessionIndex] = $rn->getValue();
}

echo '
    <form method="post"> 
    <fieldset>
    <legend>' . $legendTitle . '</legend>
    <p>' . $message . '</p>
    
    <label for="' . $userGuess . '">Username :</label>
    <input type="text" id="' . $userGuess . '" name="' . $userGuess . '"/>
    
    <input type="submit" name="' . $postGuess . '" value="Login" />
    </fieldset>
    </form>
    ';

if (isset($_POST[$postGuess])) {
    $guess = $_POST[$userGuess];
    $randNumVal = $_SESSION[$randValueSessionIndex];

    echo "You guessed: " . $guess . "<br/>";
    echo "Number is: " . $randNumVal . "<br/>";

    if (is_numeric($guess)) {
        if (intval($guess) < $randNumVal) {
            echo "Number is higher";
        }
        if (intval($guess) > $randNumVal) {
            echo "Number is lower";
        }
        if (intval($guess) == $randNumVal) {
            echo "Correct guess!";
        }
    } else {
        echo "Only input a number";
    }
}
