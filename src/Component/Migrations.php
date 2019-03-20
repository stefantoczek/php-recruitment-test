<?php

namespace Snowdog\DevTest\Component;

/**
 * Class Migrations
 *
 * @package Snowdog\DevTest\Component
 */
class Migrations
{
    /** @var Migrations */
    private static $instance;
    
    private $components = [];

    /**
     * @return \Snowdog\DevTest\Component\Migrations
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param $component
     * @param $version
     */
    public static function registerComponentMigration($component, $version)
    {
        $instance = self::getInstance();
        $instance->addComponentMigration($component, $version);
    }

    /**
     * @param $component
     * @param $version
     */
    private function addComponentMigration($component, $version)
    {
        $this->components[$component] = $version;
    }

    /**
     * @return array
     */
    public function getComponentMigrations()
    {
        return $this->components;
    }
}