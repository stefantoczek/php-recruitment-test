<?php

namespace Snowdog\DevTest\Component;

use FastRoute\RouteCollector;

/**
 * Class RouteRepository
 *
 * @package Snowdog\DevTest\Component
 */
class RouteRepository
{
    private static $instance;
    private $routes = [];
    const HTTP_METHOD = 'http_method';
    const ROUTE = 'route';
    const CLASS_NAME = 'class_name';
    const METHOD_NAME = 'method_name';


    /**
     * @return RouteRepository
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param $httpMethod
     * @param $route
     * @param $className
     * @param $methodName
     */
    public static function registerRoute($httpMethod, $route, $className, $methodName)
    {
        $instance = self::getInstance();
        $instance->addRoute($httpMethod, $route, $className, $methodName);
    }

    /**
     * @param \FastRoute\RouteCollector $r
     */
    public function __invoke(RouteCollector $r)
    {
        foreach ($this->routes as $route) {
            $r->addRoute(
                $route[self::HTTP_METHOD],
                $route[self::ROUTE],
                [
                    $route[self::CLASS_NAME],
                    $route[self::METHOD_NAME]
                ]
            );
        }
    }

    /**
     * @param $httpMethod
     * @param $route
     * @param $className
     * @param $methodName
     */
    private function addRoute($httpMethod, $route, $className, $methodName)
    {
        $this->routes[] = [
            self::HTTP_METHOD => $httpMethod,
            self::ROUTE => $route,
            self::CLASS_NAME => $className,
            self::METHOD_NAME => $methodName
        ];
    }
}