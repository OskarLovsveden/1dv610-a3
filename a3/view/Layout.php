<?php

namespace View;

class Layout {

    private $gameView;

    public function __construct(\View\Game $gameView) {
        $this->gameView = $gameView;
    }

    public function renderHTML() {
        $renderHTML = '
        <!DOCTYPE html>
        <html>
        <head>
        <meta charset="utf-8">
        <title>Login Example</title>
        </head>
        <body>
        <h1>Assignment 2</h1>
        <div class="container">
        ' . $this->gameView->getHTML() . '
        </div>
        </body>
        </html>';

        echo $renderHTML;
    }
}
