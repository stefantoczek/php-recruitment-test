<?php

namespace Snowdog\DevTest\Model;

use Snowdog\DevTest\Core\Database;

/**
 * Class VarnishManager
 *
 * @package Snowdog\DevTest\Model
 */
class VarnishManager
{

    /**
     * @var Database|\PDO
     */
    private $database;

    /**
     * VarnishManager constructor.
     *
     * @param \Snowdog\DevTest\Core\Database $database
     */
    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function getById($id)
    {
        $query = $this->database->prepare('SELECT * FROM varnishes WHERE varnish_id = :varnish_id');
        $query->setFetchMode(\PDO::FETCH_CLASS, Varnish::class);
        $query->bindParam(':varnish_id', $id, \PDO::PARAM_INT);
        $query->execute();

        return $query->fetch(\PDO::FETCH_CLASS);
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
        $query = $this->database->prepare('SELECT * FROM varnishes WHERE user_id = :user');
        $query->bindParam(':user', $userId, \PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll(\PDO::FETCH_CLASS, Varnish::class);
    }

    /**
     * @param \Snowdog\DevTest\Model\Varnish $varnish
     *
     * @return array
     */
    public function getWebsites(Varnish $varnish)
    {
        $varnishId = $varnish->getVarnishId();

        $query = $this->database->prepare('SELECT w.* FROM varnish_website vw LEFT JOIN websites w on w.website_id = vw.website_id where vw.varnish_id = :varnish_id');
        $query->bindParam(':varnish_id', $varnishId, \PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll(\PDO::FETCH_CLASS, Website::class);
    }

    /**
     * @param \Snowdog\DevTest\Model\Website $website
     *
     * @return array
     */
    public function getByWebsite(Website $website)
    {
        $websiteId = $website->getWebsiteId();

        $query = $this->database->prepare('SELECT v.* FROM varnish_website vw LEFT JOIN varnishes v on v.varnish_id = vw.varnish_id where vw.website_id = :website_id');
        $query->bindParam(':website_id', $websiteId, \PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll(\PDO::FETCH_CLASS, Varnish::class);
    }

    /**
     * @param \Snowdog\DevTest\Model\User $user
     * @param                             $ip
     *
     * @return string
     */
    public function create(User $user, $ip)
    {
        $isValid = $this->validateIPaddress($ip);
        if (!$isValid) {
            return 0;
        }
        $userId = $user->getUserId();
        $statement = $this->database->prepare('INSERT INTO varnishes (ip_address, user_id) VALUES (:ip_address, :user_id)');
        $statement->bindParam(':ip_address', $ip, \PDO::PARAM_STR);
        $statement->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $statement->execute();

        return $this->database->lastInsertId();
    }

    /**
     * @param $ip
     *
     * @return bool
     */
    public function validateIPaddress($ip)
    {
        $regexpPattern = '/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/';

        if (preg_match($regexpPattern, $ip, $output_array) !== 1) {
            $_SESSION['flash'] = 'Error occured while validating IP address!';

            return false;
        }

//        $query = $this->database->prepare('SELECT COUNT(*) FROM varnishes where ip_address = INET_ATON(:ip)');
//        $query->bindParam(':ip', $ip, \PDO::PARAM_STR);
//        $query->execute();

        return true;
    }

    /**
     * @param $varnish
     * @param $website
     *
     * @return string
     */
    public function link($varnish, $website)
    {
        $statement = $this->database->prepare('INSERT INTO varnish_website (varnish_id, website_id) VALUES (:varnish_id, :website_id)');
        $statement->bindParam(':varnish_id', $varnish, \PDO::PARAM_INT);
        $statement->bindParam(':website_id', $website, \PDO::PARAM_INT);
        $statement->execute();

        return $this->database->lastInsertId();
    }

    /**
     * @param $varnish
     * @param $website
     */
    public function unlink($varnish, $website)
    {
        $statement = $this->database->prepare('DELETE FROM varnish_website where varnish_id = :varnish_id AND website_id = :website_id');
        $statement->bindParam(':varnish_id', $varnish, \PDO::PARAM_INT);
        $statement->bindParam(':website_id', $website, \PDO::PARAM_INT);
        $statement->execute();
    }

}