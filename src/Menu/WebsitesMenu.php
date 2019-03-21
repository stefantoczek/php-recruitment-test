<?php

namespace Snowdog\DevTest\Menu;

/**
 * Class WebsitesMenu
 *
 * @package Snowdog\DevTest\Menu
 */
class WebsitesMenu extends AbstractMenu
{
    use LoggedUserMenuTrait;

    /**
     * @return bool
     */
    public function isActive()
    {
        return $_SERVER['REQUEST_URI'] === '/';
    }

    /**
     * @return string
     */
    public function getHref()
    {
        return '/';
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return 'Websites';
    }
}