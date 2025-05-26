<?php

namespace App\Core;

class Database
{
    private $dataDir;
    private static $instance = null;

    public function __construct()
    {
        $this->dataDir = __DIR__ . '/../../data';
        if (!is_dir($this->dataDir)) {
            mkdir($this->dataDir, 0777, true);
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function store($collection, $data)
    {
        $id = $data['id'] ?? uniqid();
        $data['id'] = $id;
        $data['created_at'] = $data['created_at'] ?? date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        $filePath = $this->getCollectionPath($collection);
        $existingData = $this->load($collection);
        $existingData[$id] = $data;

        file_put_contents($filePath, json_encode($existingData, JSON_PRETTY_PRINT));
        return $data;
    }

    public function load($collection)
    {
        $filePath = $this->getCollectionPath($collection);
        if (!file_exists($filePath)) {
            return [];
        }

        $content = file_get_contents($filePath);
        return json_decode($content, true) ?? [];
    }

    public function find($collection, $id)
    {
        $data = $this->load($collection);
        return $data[$id] ?? null;
    }

    public function findBy($collection, $field, $value)
    {
        $data = $this->load($collection);
        return array_filter($data, function($item) use ($field, $value) {
            return isset($item[$field]) && $item[$field] === $value;
        });
    }

    public function delete($collection, $id)
    {
        $filePath = $this->getCollectionPath($collection);
        $data = $this->load($collection);

        if (isset($data[$id])) {
            unset($data[$id]);
            file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT));
            return true;
        }

        return false;
    }

    public function query($collection, callable $callback)
    {
        $data = $this->load($collection);
        return array_filter($data, $callback);
    }

    private function getCollectionPath($collection)
    {
        return $this->dataDir . '/' . $collection . '.json';
    }

    public function beginTransaction()
    {
        // For flat file, we could implement file locking here
        return true;
    }

    public function commit()
    {
        // Release file locks if implemented
        return true;
    }

    public function rollback()
    {
        // Restore from backup if implemented
        return true;
    }

    public function backup($collection)
    {
        $filePath = $this->getCollectionPath($collection);
        if (file_exists($filePath)) {
            $backupPath = $filePath . '.bak.' . date('Y-m-d-H-i-s');
            return copy($filePath, $backupPath);
        }
        return false;
    }

    public function restore($collection, $timestamp)
    {
        $filePath = $this->getCollectionPath($collection);
        $backupPath = $filePath . '.bak.' . $timestamp;
        
        if (file_exists($backupPath)) {
            return copy($backupPath, $filePath);
        }
        return false;
    }
}
