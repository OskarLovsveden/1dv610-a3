<?php

session_start();

require_once("model/RandomNumber.php");
require_once("view/Game.php");

$randValueSessionIndex = "randNumVal";

$rn = new \Model\RandomNumber(1, 100);

if (!isset($_SESSION[$randValueSessionIndex])) {
    $_SESSION[$randValueSessionIndex] = $rn->getValueToGuess();
}

$game = new \View\Game($rn);
$gameHTML = $game->getHTML();
echo $gameHTML;

if ($game->userWantsToGuess()) {
    $guess = $game->getUserGuess();
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
