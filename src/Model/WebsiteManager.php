<?php

namespace Snowdog\DevTest\Model;

use Snowdog\DevTest\Core\Database;

/**
 * Class WebsiteManager
 *
 * @package Snowdog\DevTest\Model
 */
class WebsiteManager
{
    /**
     * @var Database|\PDO
     */
    private $database;

    /**
     * WebsiteManager constructor.
     *
     * @param \Snowdog\DevTest\Core\Database $database
     */
    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * @param $websiteId
     *
     * @return \Snowdog\DevTest\Model\Website
     */
    public function getById($websiteId)
    {
        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT * FROM websites WHERE website_id = :id');
        $query->setFetchMode(\PDO::FETCH_CLASS, Website::class);
        $query->bindParam(':id', $websiteId, \PDO::PARAM_STR);
        $query->execute();
        /** @var Website $website */
        $website = $query->fetch(\PDO::FETCH_CLASS);

        return $website;
    }

    /**
     * @param \Snowdog\DevTest\Model\User $user
     *
     * @return array
     */
    public function getAllByUser(User $user)
    {
        $userId = $user->getUserId();
        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT * FROM websites WHERE user_id = :user');
        $query->bindParam(':user', $userId, \PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll(\PDO::FETCH_CLASS, Website::class);
    }

    /**
     * @param \Snowdog\DevTest\Model\User $user
     * @param                             $name
     * @param                             $hostname
     *
     * @return string
     */
    public function create(User $user, $name, $hostname)
    {
        $userId = $user->getUserId();
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare('INSERT INTO websites (name, hostname, user_id) VALUES (:name, :host, :user)');
        $statement->bindParam(':name', $name, \PDO::PARAM_STR);
        $statement->bindParam(':host', $hostname, \PDO::PARAM_STR);
        $statement->bindParam(':user', $userId, \PDO::PARAM_INT);
        $statement->execute();

        return $this->database->lastInsertId();
    }

    /**
     * @param $hostname
     *
     * @return mixed
     */
    public function getByHostname($hostname)
    {
        $query = $this->database->prepare('SELECT * FROM websites WHERE hostname = :hostname LIMIT 1');
        $query->bindParam(':hostname', $hostname, \PDO::PARAM_STR);
        $query->setFetchMode(\PDO::FETCH_CLASS, Website::class);

        return $query->fetch(\PDO::FETCH_CLASS);
    }

}