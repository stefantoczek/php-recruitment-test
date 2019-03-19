<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 18.03.19
 * Time: 15:03
 */

namespace Snowdog\DevTest\Component;

use Old_Legacy_CacheWarmer_Resolver_Interface;
use Snowdog\DevTest\Model\VarnishManager;
use Snowdog\DevTest\Model\WebsiteManager;

class NewCacheWarmerResolverMethod implements Old_Legacy_CacheWarmer_Resolver_Interface
{
    private $ipCache = [];
    /** @var \Snowdog\DevTest\Model\Varnish */
    private $varnishes;

    private function resolveIpAddresses($hostname)
    {
        unset($this->ipCache[$hostname]);

        /** @var \Snowdog\DevTest\Model\Varnish $varnish */
        foreach ($this->varnishes as $varnish) {
            $this->ipCache[$hostname]  [] = $varnish->getIpAddress();
        }
    }

    /**
     * @param $hostname
     *
     * @return array
     */
    public function getIp($hostname)
    {
        if (!array_key_exists($hostname, $this->ipCache)) {
            $this->resolveIpAddresses($hostname);
        }

        return count($this->ipCache) > 0 ? $this->ipCache[$hostname] : [];
    }

    /**
     * @param array \Snowdog\DevTest\Model\Varnish $varnishes
     */
    public function setVarnishes($varnishes)
    {
        $this->varnishes = $varnishes;
    }
}