<?php

namespace Snowdog\DevTest\Component;

use FastRoute\RouteCollector;
use Snowdog\DevTest\Controller\Create403Action;
use Snowdog\DevTest\Controller\LoginFormAction;

/**
 * Class RouteRepository
 *
 * @package Snowdog\DevTest\Component
 */
class RouteRepository
{
    use UserLoginStatus;
    private static $instance;
    private $routes = [];
    const HTTP_METHOD = 'http_method';
    const ROUTE = 'route';
    const CLASS_NAME = 'class_name';
    const METHOD_NAME = 'method_name';

    const MIDDLEWARE_LOGGED = 'logged';
    const MIDDLEWARE_GUEST = 'guest';
    const MIDDLEWARE_ANY = 'any';

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
     * @param      $httpMethod
     * @param      $route
     * @param      $className
     * @param      $methodName
     * @param null $middleware
     */
    public static function registerRoute($httpMethod, $route, $className, $methodName, $middleware = null)
    {
        list($className, $methodName) = self::processMiddleware($middleware, $className, $methodName);

        $instance = self::getInstance();
        $instance->addRoute($httpMethod, $route, $className, $methodName);
    }

    /**
     * @param string|null $middleware
     *
     * @param string      $className
     * @param string      $methodName
     *
     * @return array
     */
    private static function processMiddleware($middleware, $className, $methodName)
    {
        switch ($middleware) {
            case self::MIDDLEWARE_LOGGED:
                if (!static::getUserLoginStatus()) {
                    $className = LoginFormAction::class;
                    $methodName = 'execute';
                }
                break;
            case self::MIDDLEWARE_GUEST:
                if (static::getUserLoginStatus()) {
                    $className = Create403Action::class;
                    $methodName = 'execute';
                }
                break;
        }

        return [$className, $methodName];
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