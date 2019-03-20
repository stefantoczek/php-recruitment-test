<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 20.03.19
 * Time: 11:06
 */

namespace Snowdog\DevTest\Component;

use Snowdog\DevTest\Model\PageManager;
use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\WebsiteManager;
use Stefantoczek\SitemapParser\Interfaces\WebsiteDatabaseInterface;

/**
 * Class DatabaseWebsiteSetter
 *
 * @package Snowdog\DevTest\Component
 */
class DatabaseWebsiteSetter implements WebsiteDatabaseInterface
{
    /** @var \Snowdog\DevTest\Model\User|null $user */
    private $user;
    /**
     * @var \Snowdog\DevTest\Model\WebsiteManager
     */
    private $websiteManager;
    /**
     * @var \Snowdog\DevTest\Model\PageManager
     */
    private $pageManager;

    /**
     * DatabaseWebsiteSetter constructor.
     *
     * @param \Snowdog\DevTest\Model\UserManager    $userManager
     * @param \Snowdog\DevTest\Model\WebsiteManager $websiteManager
     * @param \Snowdog\DevTest\Model\PageManager    $pageManager
     */
    public function __construct(
        UserManager $userManager,
        WebsiteManager $websiteManager,
        PageManager $pageManager
    ) {
        $this->user = $userManager->getLoggedUser();
        $this->websiteManager = $websiteManager;
        $this->pageManager = $pageManager;
    }

    /**
     * @param string $hostname
     *
     * @return \Snowdog\DevTest\Model\Website
     */
    public function insertWebpage($hostname)
    {
        $websiteId = $this->websiteManager->create($this->user, $hostname, $hostname);

        return $this->websiteManager->getById($websiteId);
    }

    /**
     * @param $parent
     * @param $page
     */
    public function insertPage($parent, $page)
    {
        $this->pageManager->create($parent, $page);
    }
}