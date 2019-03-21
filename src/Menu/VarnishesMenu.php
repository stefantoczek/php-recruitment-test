<?php

namespace Snowdog\DevTest\Menu;

/**
 * Class VarnishesMenu
 *
 * @package Snowdog\DevTest\Menu
 */
class VarnishesMenu extends AbstractMenu
{
    use LoggedUserMenuTrait;

    /**
     * @return bool
     */
    public function isActive()
    {
        return $_SERVER['REQUEST_URI'] === '/varnish';
    }

    /**
     * @return string
     */
    public function getHref()
    {
        return '/varnish';
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return 'Varnishes';
    }
}