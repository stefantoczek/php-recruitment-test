<?php

namespace Snowdog\DevTest\Controller;

/**
 * Class LogoutAction
 *
 * @package Snowdog\DevTest\Controller
 */
class LogoutAction
{

    public function execute() {
        if(isset($_SESSION['login'])) {
            unset($_SESSION['login']);
            $_SESSION['flash'] = 'Logged out successfully';
        }
        header('Location: /login');
    }
}