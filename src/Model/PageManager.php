<?php

namespace Snowdog\DevTest\Model;

use Snowdog\DevTest\Core\Database;

class PageManager
{

    /**
     * @var Database|\PDO
     */
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getAllByWebsite(Website $website)
    {
        $websiteId = $website->getWebsiteId();
        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT * FROM pages WHERE website_id = :website');
        $query->bindParam(':website', $websiteId, \PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll(\PDO::FETCH_CLASS, Page::class);
    }

    public function create(Website $website, $url)
    {
        $websiteId = $website->getWebsiteId();
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare('INSERT INTO pages (url, website_id) VALUES (:url, :website)');
        $statement->bindParam(':url', $url, \PDO::PARAM_STR);
        $statement->bindParam(':website', $websiteId, \PDO::PARAM_INT);
        $statement->execute();

        return $this->database->lastInsertId();
    }

    public function registerVisit(Page $page)
    {
        $pageId = $page->getPageId();
        $currentStamp = time();
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare('UPDATE pages SET last_visited = :visit_stamp WHERE page_id = :page_id');
        $statement->bindParam(':visit_stamp', $currentStamp, \PDO::PARAM_INT);
        $statement->bindParam(':page_id', $pageId, \PDO::PARAM_INT);
        $statement->execute();
    }
}