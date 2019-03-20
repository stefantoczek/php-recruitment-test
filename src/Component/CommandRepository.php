<?php

namespace Snowdog\DevTest\Component;


use Silly\Application;

/**
 * Class CommandRepository
 *
 * @package Snowdog\DevTest\Component
 */
class CommandRepository
{
    private static $instance;
    private $commands = [];
    const COMMAND = 'command';
    const CLASS_NAME = 'class_name';


    /**
     * @return CommandRepository
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param $command
     * @param $className
     */
    public static function registerCommand($command , $className)
    {
        $instance = self::getInstance();
        $instance->addCommand($command, $className);
    }

    /**
     * @param \Silly\Application $app
     */
    public function applyCommands(Application $app)
    {
        foreach ($this->commands as $command) {
            $app->command($command[self::COMMAND], $command[self::CLASS_NAME]);
        }
    }

    /**
     * @param $command
     * @param $className
     */
    private function addCommand($command, $className)
    {
        $this->commands[] = [
            self::COMMAND => $command,
            self::CLASS_NAME => $className
        ];
    }
}