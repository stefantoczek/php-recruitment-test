<?php

namespace Snowdog\DevTest\Model;

/**
 * Class Varnish
 *
 * @package Snowdog\DevTest\Model
 */
class Varnish
{

    public $varnish_id;
    public $ip_address;
    public $user_id;

    public function __construct()
    {
        $this->user_id = (int)$this->user_id;
        $this->varnish_id = (int)$this->varnish_id;
        $this->ip_address = long2ip(sprintf('%d', $this->ip_address));
    }

    /**
     * @return int
     */
    public function getVarnishId()
    {
        return $this->varnish_id;
    }

    /**
     * @return mixed
     */
    public function getIpAddress()
    {
        return $this->ip_address;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

}