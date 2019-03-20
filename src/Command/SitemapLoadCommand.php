<?php

namespace Snowdog\DevTest\Command;

use Snowdog\DevTest\Component\DatabaseWebsiteSetter;
use Stefantoczek\SitemapParser\SitemapParser;
use Snowdog\DevTest\Model\PageManager;
use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\WebsiteManager;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SitemapLoadCommand
 *
 * @package Snowdog\DevTest\Command
 */
class SitemapLoadCommand
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
     * @var \Snowdog\DevTest\Command\SitemapParser
     */
    private $sitemapParser;
    /**
     * @var \Snowdog\DevTest\Model\UserManager
     */
    private $userManager;

    private $databaseWebsiteSetter;

    /**
     * SitemapLoadCommand constructor.
     *
     * @param \Stefantoczek\SitemapParser\SitemapParser        $sitemapParser
     * @param \Snowdog\DevTest\Model\UserManager               $userManager
     * @param \Snowdog\DevTest\Component\DatabaseWebsiteSetter $databaseWebsiteSetter
     */
    public function __construct(
        SitemapParser $sitemapParser,
        UserManager $userManager,
        DatabaseWebsiteSetter $databaseWebsiteSetter
    ) {
        $this->sitemapParser = $sitemapParser;
        $this->userManager = $userManager;
        $this->databaseWebsiteSetter = $databaseWebsiteSetter;
    }

    /**
     * @param                                                   $username
     * @param                                                   $filename
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    public function __invoke($username, $filename, OutputInterface $output)
    {
        $user = $this->userManager->getByLogin($username);
        $this->sitemapParser
            ->setWebsiteDatabaseSetter($this->databaseWebsiteSetter)
            ->loadFromFile($filename)
            ->insertDataToDatabase();
    }
}