<?php

namespace Snowdog\DevTest\Migration;

use Snowdog\DevTest\Core\Database;

/**
 * Class Version4
 *
 * @package Snowdog\DevTest\Migration
 */
class Version5
{
    /**
     * @var Database|\PDO
     */
    private $database;

    /**
     * Version4 constructor.
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
        $this->updateVarnishesTable();
    }

    private function updateVarnishesTable()
    {
        $statement = <<<SQL
    ALTER TABLE `varnishes` MODIFY `ip_address` int(11) unsigned NOT NULL ;
SQL;

        $this->database->exec($statement);
    }

}