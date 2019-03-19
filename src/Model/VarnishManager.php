<?php

namespace Snowdog\DevTest\Model;

use Snowdog\DevTest\Core\Database;

class VarnishManager
{

    /**
     * @var Database|\PDO
     */
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getById($id)
    {
        $query = $this->database->prepare('SELECT * FROM varnishes WHERE varnish_id = :varnish_id');
        $query->setFetchMode(\PDO::FETCH_CLASS, Varnish::class);
        $query->bindParam(':varnish_id', $id, \PDO::PARAM_INT);
        $query->execute();

        return $query->fetch(\PDO::FETCH_CLASS);
    }

    public function getAllByUser(User $user)
    {
        $userId = $user->getUserId();
        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT * FROM varnishes WHERE user_id = :user');
        $query->bindParam(':user', $userId, \PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll(\PDO::FETCH_CLASS, Varnish::class);
    }

    public function getWebsites(Varnish $varnish)
    {
        $varnishId = $varnish->getVarnishId();

        $query = $this->database->prepare('SELECT w.* FROM varnish_website vw LEFT JOIN websites w on w.website_id = vw.website_id where vw.varnish_id = :varnish_id');
        $query->bindParam(':varnish_id', $varnishId, \PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll(\PDO::FETCH_CLASS, Website::class);
    }

    public function getByWebsite(Website $website)
    {
        $websiteId = $website->getWebsiteId();
        
        $query = $this->database->prepare('SELECT v.* FROM varnish_website vw LEFT JOIN varnishes v on v.varnish_id = vw.varnish_id where vw.website_id = :website_id');
        $query->bindParam(':website_id', $websiteId, \PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll(\PDO::FETCH_CLASS, Varnish::class);
    }

    public function create(User $user, $ip)
    {
        $userId = $user->getUserId();
        $statement = $this->database->prepare('INSERT INTO varnishes (ip_address, user_id) VALUES (:ip_address, :user_id)');
        $statement->bindParam(':ip_address', $ip, \PDO::PARAM_STR);
        $statement->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $statement->execute();

        return $this->database->lastInsertId();
    }

    public function link($varnish, $website)
    {
        $statement = $this->database->prepare('INSERT INTO varnish_website (varnish_id, website_id) VALUES (:varnish_id, :website_id)');
        $statement->bindParam(':varnish_id', $varnish, \PDO::PARAM_INT);
        $statement->bindParam(':website_id', $website, \PDO::PARAM_INT);
        $statement->execute();

        return $this->database->lastInsertId();
    }

    public function unlink($varnish, $website)
    {
        $statement = $this->database->prepare('DELETE FROM varnish_website where varnish_id = :varnish_id AND website_id = :website_id');
        $statement->bindParam(':varnish_id', $varnish, \PDO::PARAM_INT);
        $statement->bindParam(':website_id', $website, \PDO::PARAM_INT);
        $statement->execute();
    }

}