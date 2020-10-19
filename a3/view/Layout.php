<?php

namespace A3\View;

class Layout {
    private static $login = "login";
    private static $register = "register";
    private static $logout = "logout";
    private static $highscore = "highscore";

    private $loginView;
    private $registerView;
    private $gameView;
    private $highScoreView;

    public function __construct(\A3\View\Login $loginView, \A3\View\Register $registerView, \A3\View\Game $gameView, \A3\View\HighScore $highScoreView) {
        $this->loginView = $loginView;
        $this->registerView = $registerView;
        $this->gameView = $gameView;
        $this->highScoreView = $highScoreView;
    }

    public function renderHTML(bool $userLoggedIn, bool $gameWon) {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
        <meta charset="utf-8">
        <title>Random Number Game</title>
        </head>
        <nav>
        <a href="/a3/">Start</a> | ';

        if (!$userLoggedIn) {
            $html .= '<a href="/a3/?' . self::$login . '">Login</a> | ';
            $html .= '<a href="/a3/?' . self::$register . '">Register</a> | ';
        }

        $html .= '
        <a href="/a3/?' . self::$highscore . '">Highscore</a>
        </nav>
        <body>
        <h1>Random Number Guessing Game</h1>
        <div class="container">';

        if (isset($_GET[self::$register])) {
            $html .= $this->registerView->response();
        } else if (isset($_GET[self::$login])) {
            $html .= $this->loginView->response();
        } else {
            if (isset($_GET[self::$highscore])) {
                $html .= $this->highScoreView->getHTML();
            } else {
                $html .= $this->gameView->getHTML($userLoggedIn, $gameWon);
            }
        }

        if ($userLoggedIn) {
            $html .= $this->loginView->generateLogoutButtonHTML();
        }

        $html .= '
        </div>
        </body>
        </html>';

        echo $html;
    }
}
