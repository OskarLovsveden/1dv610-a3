<?php

namespace A3\View;

class Layout {

    private $gameView;

    public function __construct(\A3\View\Game $gameView, \A3\View\HighScore $highScoreView) {
        $this->gameView = $gameView;
    }

    public function renderHTML(bool $gameWon) {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
        <meta charset="utf-8">
        <title>Random Number Game</title>
        </head>
        <body>
        <h1>Random Number Guessing Game</h1>
        <div class="container">';

        if (isset($_GET["highscore"])) {
            $html .= $this->highScoreView->getHTML();
        } else {
            $html .= $this->gameView->getHTML($gameWon);
        }

        $html .= '
        </div>
        </body>
        </html>';

        echo $html;
    }
}
