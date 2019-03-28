<?php

namespace Snowdog\DevTest\Core;

use Invoker\InvokerInterface;
use Snowdog\DevTest\Component\Migrations;

/**
 * Class Migration
 *
 * @package Snowdog\DevTest\Core
 */
class Migration
{
    const COMPONENT = 'component';
    const VERSION = 'version';

    /**
     * @var InvokerInterface
     */
    private $invoker;
    /**
     * @var Database|\PDO
     */
    private $database;

    /**
     * Migration constructor.
     *
     * @param \Invoker\InvokerInterface      $invoker
     * @param \Snowdog\DevTest\Core\Database $database
     */
    public function __construct(InvokerInterface $invoker, Database $database)
    {
        $this->invoker = $invoker;
        $this->database = $database;
    }

    /**
     * @return array
     * @throws \Invoker\Exception\InvocationException
     * @throws \Invoker\Exception\NotCallableException
     * @throws \Invoker\Exception\NotEnoughParametersException
     */
    public function execute()
    {
        $migrations = Migrations::getInstance()->getComponentMigrations();
        ksort($migrations);

        $currentVersions = $this->getCurrentVersions();
        ksort($currentVersions);

        $executed = [];

        foreach ($migrations as $component => $version) {
            $currentVersion = isset($currentVersions[$component]) ? $currentVersions[$component] : 0;
            for ($i = $currentVersion + 1; $i <= $version; ++$i) {
                $this->migrate($component, $i);
                $executed[] = [
                    self::COMPONENT => $component,
                    self::VERSION => $i
                ];
            }
        }

        return $executed;
    }

    /**
     * @return array
     */
    private function getCurrentVersions()
    {
        $this->testComponentsTable();

        /** @var \PDOStatement $sql */
        $sql = $this->database->query('SELECT component, version FROM components');
        $data = $sql->fetchAll();

        $result = [];
        foreach ($data as $row) {
            $result[$row['component']] = $row['version'];
        }

        return $result;
    }

    /**
     * @param $component
     * @param $i
     *
     * @throws \Invoker\Exception\InvocationException
     * @throws \Invoker\Exception\NotCallableException
     * @throws \Invoker\Exception\NotEnoughParametersException
     */
    private function migrate($component, $i)
    {
        $className = $component . '\\Migration\\Version' . $i;
        $this->invoker->call($className);

        if ($this->database->errorCode() > 0) {
            throw new \PDOException(implode(' ', $this->database->errorInfo()));
        }

        /** @var \PDOStatement $sql */
        $sql = $this->database->prepare('INSERT INTO components (component, version) VALUES (:component, :version) ON DUPLICATE KEY UPDATE version = :version');
        $sql->bindParam(':component', $component, \PDO::PARAM_STR);
        $sql->bindParam(':version', $i, \PDO::PARAM_INT);
        $sql->execute();
    }

    private function testComponentsTable()
    {
        /** @var \PDOStatement $tableQuery */
        $tableQuery = $this->database->query("SHOW TABLES LIKE 'components'");
        if (!$tableQuery->rowCount()) {
            $createQuery = <<<SQL
CREATE TABLE `components` (
    `component` VARCHAR(255) NOT NULL UNIQUE,
    `version` SMALLINT(6) NOT NULL,
    PRIMARY KEY (`component`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8; 
SQL;
            $this->database->exec($createQuery);
        }
    }

}