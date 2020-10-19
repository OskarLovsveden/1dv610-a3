<?php

namespace A3\Model;

class HighScore {
    private $items = array();

    public function add(\A3\Model\HighScoreItem $toBeAdded) {
        $this->items[] = $toBeAdded;
    }

    public function get(): array {
        return $this->items;
    }

    public function sort() {
        usort($this->items, array($this, 'compareScores'));
    }

    private function compareScores(\A3\Model\HighScoreItem $a, \A3\Model\HighScoreItem $b) {
        return [$b->getDifficulty(), $a->getScore()] <=> [$a->getDifficulty(), $b->getScore()];
    }
}
