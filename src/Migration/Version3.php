<?php

namespace Snowdog\DevTest\Migration;

use Snowdog\DevTest\Core\Database;
use Snowdog\DevTest\Model\PageManager;
use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\WebsiteManager;

/**
 * Class Version3
 *
 * @package Snowdog\DevTest\Migration
 */
class Version3
{
    /**
     * @var Database|\PDO
     */
    private $database;
    /**
     * @var UserManager
     */
    private $userManager;
    /**
     * @var WebsiteManager
     */
    private $websiteManager;
    /**
     * @var PageManager
     */
    private $pageManager;

    /**
     * Version3 constructor.
     *
     * @param \Snowdog\DevTest\Core\Database        $database
     * @param \Snowdog\DevTest\Model\UserManager    $userManager
     * @param \Snowdog\DevTest\Model\WebsiteManager $websiteManager
     * @param \Snowdog\DevTest\Model\PageManager    $pageManager
     */
    public function __construct(
        Database $database,
        UserManager $userManager,
        WebsiteManager $websiteManager,
        PageManager $pageManager
    ) {
        $this->database = $database;
        $this->userManager = $userManager;
        $this->websiteManager = $websiteManager;
        $this->pageManager = $pageManager;
    }

    public function __invoke()
    {
        $this->updatePageTable();
    }

    private function updatePageTable()
    {
        $updateQuery = <<<SQL
ALTER TABLE `pages`
ADD `last_visited` int(11) unsigned;
SQL;
        $this->database->exec($updateQuery);
    }

}