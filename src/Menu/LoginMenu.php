<?php

namespace Snowdog\DevTest\Menu;

/**
 * Class LoginMenu
 *
 * @package Snowdog\DevTest\Menu
 */
class LoginMenu extends AbstractMenu
{
    /**
     * @return bool
     */
    public function isActive()
    {
        return $_SERVER['REQUEST_URI'] === '/login';
    }

    /**
     * @return string
     */
    public function getHref()
    {
        if (isset($_SESSION['login'])) {
            return '/logout';
        }

        return '/login';
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        if (isset($_SESSION['login'])) {
            return 'Logout';
        }

        return 'Login';
    }

}