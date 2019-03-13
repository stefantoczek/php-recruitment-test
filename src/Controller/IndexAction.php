<?php

namespace Snowdog\DevTest\Controller;

use Snowdog\DevTest\Model\PageManager;
use Snowdog\DevTest\Model\User;
use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\WebsiteManager;

class IndexAction
{

    /**
     * @var WebsiteManager
     */
    private $websiteManager;

    /**
     * @var User
     */
    private $user;

    /**
     * @var PageManager
     */
    private $pageManager;

    /**
     * @var array
     */
    private $userPageInfo;

    public function __construct(UserManager $userManager, WebsiteManager $websiteManager, PageManager $pageManager)
    {
        $this->websiteManager = $websiteManager;
        if (isset($_SESSION['login'])) {
            $this->user = $userManager->getByLogin($_SESSION['login']);
            $this->userPageInfo = $pageManager->getUserPageInfo($this->user);
        }
    }

    protected function getWebsites()
    {
        if ($this->user) {
            return $this->websiteManager->getAllByUser($this->user);
        }

        return [];
    }

    protected function getUserPageInfo($key)
    {
        if (array_key_exists($key, $this->userPageInfo)) {
            return $this->userPageInfo[$key];
        }

        return '';
    }

    public function execute()
    {
        require __DIR__ . '/../view/index.phtml';
    }
}