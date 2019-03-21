<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 20.03.19
 * Time: 15:04
 */

namespace Snowdog\DevTest\Menu;

/**
 * Trait LoggedUserMenuTrait
 *
 * @package Snowdog\DevTest\Menu
 */
trait LoggedUserMenuTrait
{
    /**
     * @return bool
     */
    public function isVisible()
    {
        return isset($_SESSION['login']);
    }
}