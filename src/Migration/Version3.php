<?php

namespace Snowdog\DevTest\Migration;

use Snowdog\DevTest\Core\Database;

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
     * Version3 constructor.
     *
     * @param \Snowdog\DevTest\Core\Database $database
     */
    public function __construct(
        Database $database
    ) {
        $this->database = $database;
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