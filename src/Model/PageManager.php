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

    /**
     * if $directionFlag is set to true, then returned value is most recently visited page from given user,
     * otherwise returns least recently visited page
     *
     * @param \Snowdog\DevTest\Model\User $user
     * @param                             $directionFlag
     *
     * @return array
     */
    public function getRecentUserPageVisit(User $user, $directionFlag = true)
    {
        $direction = $directionFlag ? 'DESC' : 'ASC';
        $userId = $user->getUserId();

        $query = $this->database->prepare("select p.* from pages p left join websites w on w.website_id = p.website_id where w.user_id = :user_id order by last_visited {$direction} limit 1");
        $query->bindParam(':user_id', $userId, \PDO::PARAM_INT);

        $query->execute();
        $result = $query->fetchAll(\PDO::FETCH_CLASS, Page::class);

        return count($result) > 0 ? $result[0] : null;
    }

    public function getTotalUserPageCount(User $user)
    {
        $userId = $user->getUserId();

        $query = $this->database->prepare('SELECT COUNT(*) FROM pages p LEFT JOIN websites w on p.website_id = w.website_id WHERE w.user_id = :user_id');
        $query->bindParam(':user_id', $userId);
        $query->execute();

        return (int)$query->fetchColumn();
    }

    public function getUserPageInfo(User $user)
    {
        return [
            'page_count' => $this->getTotalUserPageCount($user),
            'least_recently_visited' => $this->getRecentUserPageVisit($user, true),
            'most_recently_visited' => $this->getRecentUserPageVisit($user, false),
        ];
    }

}