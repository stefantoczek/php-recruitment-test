<?php

namespace Snowdog\DevTest\Menu;

/**
 * Class RegisterMenu
 *
 * @package Snowdog\DevTest\Menu
 */
class RegisterMenu extends AbstractMenu
{

    /**
     * @return bool
     */
    public function isActive()
    {
        return $_SERVER['REQUEST_URI'] == '/register';
    }

    /**
     * @return string
     */
    public function getHref()
    {
        return '/register';
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return 'Register';
    }

    public function __invoke()
    {
        if (!isset($_SESSION['login'])) {
            parent::__invoke();
        }
    }
}