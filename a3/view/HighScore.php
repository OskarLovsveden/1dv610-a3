<?php

namespace A3\View;

class HighScore {
    private $highScore;

    public function __construct(\A3\Model\HighScore $highScore) {
        $this->highScore = $highScore;
    }

    public function getHTML(): string {
        $this->highScore->sort();
        $hsi = $this->highScore->get();

        $ret = "<ul>";
        foreach ($hsi as $item) {
            $ret .= "<li>";
            $ret .= "Name: " . $item->getPlayer() .  "<br/>";
            $ret .= "Difficulty: " . $item->getDifficulty() .  "<br/>";
            $ret .= "Score: " . $item->getScore() .  "<br/>";
            $ret .= "~";
            $ret .= "</li>";
        }
        $ret .= "</ul>";

        return $ret;
    }
}
