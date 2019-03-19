<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 18.03.19
 * Time: 14:27
 */

namespace Snowdog\DevTest\Component;

use Old_Legacy_CacheWarmer_Warmer;

class NewCacheWarmer extends Old_Legacy_CacheWarmer_Warmer
{
    /** @var \Snowdog\DevTest\Component\NewCacheWarmerResolverMethod */
    private $resolver;
    private $hostname;
    /** @var \Old_Legacy_CacheWarmer_Actor */
    private $actor;

    /**
     * @return \Snowdog\DevTest\Component\NewCacheWarmerResolverMethod
     */
    public function getResolver()
    {
        return $this->resolver;
    }

    /**
     * @param \Snowdog\DevTest\Component\NewCacheWarmerResolverMethod $resolver
     */
    public function setResolver($resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * @return mixed
     */
    public function getHostname()
    {
        return $this->hostname;
    }

    /**
     * @param mixed $hostname
     */
    public function setHostname($hostname)
    {
        $this->hostname = $hostname;
    }

    /**
     * @return \Old_Legacy_CacheWarmer_Actor
     */
    public function getActor()
    {
        return $this->actor;
    }

    /**
     * @param \Old_Legacy_CacheWarmer_Actor $actor
     */
    public function setActor($actor)
    {
        $this->actor = $actor;
    }

    public function warm($url)
    {
        $ips = $this->resolver->getIp($this->hostname);
        foreach ($ips as $ip) {
            sleep(1); // this emulates visit to http://$hostname/$url via $ip
            $this->actor->act($this->hostname, $ip, $url);
        }
    }
}