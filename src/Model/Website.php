<?php

namespace Snowdog\DevTest\Model;

/**
 * Class Website
 *
 * @package Snowdog\DevTest\Model
 */
class Website
{

    public $website_id;
    public $name;
    public $hostname;
    public $user_id;

    public function __construct()
    {
        $this->user_id = (int)$this->user_id;
        $this->website_id = (int)$this->website_id;
    }

    /**
     * @return int
     */
    public function getWebsiteId()
    {
        return $this->website_id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getHostname()
    {
        return $this->hostname;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }
}