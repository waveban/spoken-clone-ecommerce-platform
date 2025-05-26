<?php

namespace App\Core;

use MeiliSearch\Client;

class App
{
    private static $instance = null;
    private $container = [];

    public function __construct()
    {
        self::$instance = $this;
        $this->initializeMeilisearch();
    }

    private function initializeMeilisearch()
    {
        $client = new Client($_ENV['MEILISEARCH_HOST'], $_ENV['MEILISEARCH_KEY']);
        $this->container['meilisearch'] = $client;
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function get($key)
    {
        return $this->container[$key] ?? null;
    }

    public function set($key, $value)
    {
        $this->container[$key] = $value;
    }

    public function getMeilisearch()
    {
        return $this->get('meilisearch');
    }

    public function isAuthenticated()
    {
        return isset($_SESSION['user']);
    }

    public function getUser()
    {
        return $_SESSION['user'] ?? null;
    }
}
