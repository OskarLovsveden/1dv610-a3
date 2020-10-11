<?php

namespace View;

class Layout {

  public function render(bool $isLoggedIn, \View\Login $loginView, \View\Register $registerView, \View\DateTime $dateTimeView) {
    $renderHTML = '
    <!DOCTYPE html>
    <html>
    <head>
    <meta charset="utf-8">
    <title>Login Example</title>
    </head>
    <body>
    <h1>Assignment 2</h1>'
      . $this->renderIsLoggedIn($isLoggedIn) .
      '<div class="container">';

    if (isset($_GET["register"])) {
      $renderHTML .= $this->renderLinkForRegister();
      $renderHTML .= $registerView->response();
    } else {
      if (!$isLoggedIn) {
        $renderHTML .= $this->renderLinkForLogin();
      }
      $renderHTML .= $loginView->response($isLoggedIn);
    }

    $renderHTML .=
      $dateTimeView->show() .
      '</div>
    </body>
    </html>';

    echo $renderHTML;
  }

  private function renderLinkForLogin() {
    return '<p><a href="?register">Register a new user</a></p>';
  }

  private function renderLinkForRegister() {
    return '<p><a href="/">Back to login</a></p>';
  }

  private function renderIsLoggedIn(bool $isLoggedIn) {
    if ($isLoggedIn) {
      return '<h2>Logged in</h2>';
    } else {
      return '<h2>Not logged in</h2>';
    }
  }
}