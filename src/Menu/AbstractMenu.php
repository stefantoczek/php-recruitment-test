<?php

namespace Snowdog\DevTest\Menu;

/**
 * Class AbstractMenu
 *
 * @package Snowdog\DevTest\Menu
 */
abstract class AbstractMenu
{

    abstract public function isActive();

    abstract public function getHref();

    abstract public function getLabel();

    /**
     * @return bool
     */
    public function isVisible()
    {
        return true;
    }

    public function __invoke()
    {
        require __DIR__ . '/../view/menu_item.phtml';
    }
}