<?php

namespace App\Core;

class Router
{
    private $routes = [
        'GET' => [],
        'POST' => []
    ];

    public function get($uri, $controller)
    {
        $this->routes['GET'][$uri] = $controller;
    }

    public function post($uri, $controller)
    {
        $this->routes['POST'][$uri] = $controller;
    }

    public function dispatch()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        if (!isset($this->routes[$method][$uri])) {
            $this->handleNotFound();
            return;
        }

        $handler = $this->routes[$method][$uri];
        $this->handleRoute($handler);
    }

    private function handleRoute($handler)
    {
        [$controller, $method] = explode('@', $handler);
        $controllerClass = "App\\Controllers\\{$controller}";

        if (!class_exists($controllerClass)) {
            throw new \Exception("Controller {$controllerClass} not found");
        }

        $controller = new $controllerClass();
        
        if (!method_exists($controller, $method)) {
            throw new \Exception("Method {$method} not found in controller {$controllerClass}");
        }

        $controller->$method();
    }

    private function handleNotFound()
    {
        header("HTTP/1.0 404 Not Found");
        require_once __DIR__ . '/../../views/404.php';
    }
}
