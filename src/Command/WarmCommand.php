<?php

namespace Snowdog\DevTest\Command;

use Old_Legacy_CacheWarmer_Actor;
use Snowdog\DevTest\Component\NewCacheWarmer;
use Snowdog\DevTest\Component\NewCacheWarmerResolverMethod;
use Snowdog\DevTest\Model\PageManager;
use Snowdog\DevTest\Model\VarnishManager;
use Snowdog\DevTest\Model\WebsiteManager;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class WarmCommand
 *
 * @package Snowdog\DevTest\Command
 */
class WarmCommand
{
    /**
     * @var WebsiteManager
     */
    private $websiteManager;
    /**
     * @var PageManager
     */
    private $pageManager;
    /**
     * @var \Snowdog\DevTest\Model\VarnishManager
     */
    private $varnishManager;

    /**
     * WarmCommand constructor.
     *
     * @param \Snowdog\DevTest\Model\WebsiteManager $websiteManager
     * @param \Snowdog\DevTest\Model\PageManager    $pageManager
     * @param \Snowdog\DevTest\Model\VarnishManager $varnishManager
     */
    public function __construct(
        WebsiteManager $websiteManager,
        PageManager $pageManager,
        VarnishManager $varnishManager
    ) {
        $this->websiteManager = $websiteManager;
        $this->pageManager = $pageManager;
        $this->varnishManager = $varnishManager;
    }

    /**
     * @param                                                   $id
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    public function __invoke($id, OutputInterface $output)
    {
        $website = $this->websiteManager->getById($id);
        if ($website) {
            $varnishes = $this->varnishManager->getByWebsite($website);
            $pages = $this->pageManager->getAllByWebsite($website);
            $resolver = new NewCacheWarmerResolverMethod();
            $resolver->setVarnishes($varnishes);
            $actor = new Old_Legacy_CacheWarmer_Actor();
            $actor->setActor(static function ($hostname, $ip, $url) use ($output) {
                $output->writeln('Visited <info>http://' . $hostname . '/' . $url . '</info> via IP: <comment>' . $ip . '</comment>');
            });
            $warmer = new NewCacheWarmer();
            $warmer->setResolver($resolver);
            $warmer->setHostname($website->getHostname());
            $warmer->setActor($actor);

            foreach ($pages as $page) {
                if ($warmer->warm($page->getUrl())) {
                    $this->pageManager->registerVisit($page);
                }
            }
        } else {
            $output->writeln('<error>Website with ID ' . $id . ' does not exists!</error>');
        }
    }
}