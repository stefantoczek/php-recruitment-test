<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 21.03.19
 * Time: 08:45
 */

namespace Snowdog\DevTest\Component;

/**
 * Trait UserLoginStatus
 *
 * @package Snowdog\DevTest\Component
 */
trait UserLoginStatus
{
    /**
     * @return bool
     */
    public static function getUserLoginStatus()
    {
        return isset($_SESSION['login']);
    }
}