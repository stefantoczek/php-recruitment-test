<?php

namespace Snowdog\DevTest\Controller;

/**
 * Class CreatePageAction
 *
 * @package Snowdog\DevTest\Controller
 */
class Create403Action
{
    public function execute()
    {
        require __DIR__ . '/../view/403.phtml';
    }
}