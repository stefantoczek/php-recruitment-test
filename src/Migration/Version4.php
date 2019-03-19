<?php

namespace Snowdog\DevTest\Migration;

use Snowdog\DevTest\Core\Database;
use Snowdog\DevTest\Model\PageManager;
use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\WebsiteManager;

class Version4
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
        $this->createVarnishesTable();
        $this->createVarnishWebsiteTable();
    }

    private function createVarnishWebsiteTable()
    {
        $statement = <<<SQL
CREATE TABLE `varnish_website` (
  `varnish_website_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `website_id` int(11) unsigned NOT NULL,
  `varnish_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`varnish_website_id`),
  UNIQUE KEY `varnish_website_key` (`website_id`, `varnish_id`),
  KEY `website_id` (`website_id`),
  CONSTRAINT `varnish_website_website_fk` FOREIGN KEY (`website_id`) REFERENCES `websites` (`website_id`),
  KEY `varnish_id` (`varnish_id`),
  CONSTRAINT `varnish_website_varnish_fk` FOREIGN KEY (`varnish_id`) REFERENCES `varnishes` (`varnish_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL;
        $this->database->exec($statement);
    }

    private function createVarnishesTable()
    {
        $statement = <<<SQL
CREATE TABLE `varnishes` (
  `varnish_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(255) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`varnish_id`),
  UNIQUE KEY `ip_user` (`ip_address`, `user_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `varnish_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL;
        $this->database->exec($statement);
    }

}