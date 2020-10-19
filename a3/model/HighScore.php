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
        usort($this->items, array($this, 'comparePeople'));
    }

    private function comparePeople(\A3\Model\HighScoreItem $h1, \A3\Model\HighScoreItem $h2) {
        return strcmp($h1->getDifficulty(), $h2->getDifficulty());
    }
}
