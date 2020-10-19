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
            $ret .= $item->getPlayer() . " | " . $item->getDifficulty() . " | " . $item->getScore();
            $ret .= "</li>";
        }
        $ret .= "</ul>";

        return $ret;
    }
}
