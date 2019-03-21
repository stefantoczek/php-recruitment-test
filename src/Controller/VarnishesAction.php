<?php

namespace Snowdog\DevTest\Controller;

use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\Varnish;
use Snowdog\DevTest\Model\VarnishManager;
use Snowdog\DevTest\Model\WebsiteManager;

/**
 * Class VarnishesAction
 *
 * @package Snowdog\DevTest\Controller
 */
class VarnishesAction
{
    /**
     * @var VarnishManager
     */
    private $varnishManager;

    /** @var \Snowdog\DevTest\Model\User $user */
    private $user;
    /**
     * @var WebsiteManager
     */
    private $websiteManager;

    /**
     * VarnishesAction constructor.
     *
     * @param \Snowdog\DevTest\Model\UserManager    $userManager
     * @param \Snowdog\DevTest\Model\VarnishManager $varnishManager
     * @param \Snowdog\DevTest\Model\WebsiteManager $websiteManager
     */
    public function __construct(
        UserManager $userManager,
        VarnishManager $varnishManager,
        WebsiteManager $websiteManager
    ) {
        $this->varnishManager = $varnishManager;
        if (isset($_SESSION['login'])) {
            $this->user = $userManager->getByLogin($_SESSION['login']);
        }
        $this->websiteManager = $websiteManager;
    }

    /**
     * @return array
     */
    public function getVarnishes()
    {
        if ($this->user) {
            return $this->varnishManager->getAllByUser($this->user);
        }

        return [];
    }

    /**
     * @return array
     */
    public function getWebsites()
    {
        if ($this->user) {
            return $this->websiteManager->getAllByUser($this->user);
        }

        return [];
    }

    /**
     * @param \Snowdog\DevTest\Model\Varnish $varnish
     *
     * @return array
     */
    public function getAssignedWebsiteIds(Varnish $varnish)
    {
        $websites = $this->varnishManager->getWebsites($varnish);
        $ids = [];
        foreach ($websites as $website) {
            $ids[] = $website->getWebsiteId();
        }

        return $ids;
    }

    public function execute()
    {
        include __DIR__ . '/../view/varnish.phtml';
    }

}