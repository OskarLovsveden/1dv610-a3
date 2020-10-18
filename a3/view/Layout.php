<?php

namespace A3\View;

class Layout {

    private $gameView;

    public function __construct(\A3\View\Game $gameView) {
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

        $html .= $this->gameView->getHTML($gameWon);

        $html .= '
        </div>
        </body>
        </html>';

        echo $html;
    }
}
