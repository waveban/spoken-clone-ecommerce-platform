<?php

namespace App\Core;

abstract class Model
{
    protected $db;
    protected $meilisearch;
    protected static $collection;
    protected static $searchIndex;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->meilisearch = App::getInstance()->getMeilisearch();
    }

    public function save(array $data)
    {
        // Save to flat-file database
        $savedData = $this->db->store(static::$collection, $data);

        // If this model is searchable, index it in Meilisearch
        if (static::$searchIndex) {
            try {
                $index = $this->meilisearch->index(static::$searchIndex);
                $index->addDocuments([$savedData]);
            } catch (\Exception $e) {
                error_log("Meilisearch indexing error: " . $e->getMessage());
                // Continue even if Meilisearch fails - we still have the data in our flat-file
            }
        }

        return $savedData;
    }

    public function find($id)
    {
        return $this->db->find(static::$collection, $id);
    }

    public function findBy($field, $value)
    {
        return $this->db->findBy(static::$collection, $field, $value);
    }

    public function delete($id)
    {
        $deleted = $this->db->delete(static::$collection, $id);

        if ($deleted && static::$searchIndex) {
            try {
                $index = $this->meilisearch->index(static::$searchIndex);
                $index->deleteDocument($id);
            } catch (\Exception $e) {
                error_log("Meilisearch deletion error: " . $e->getMessage());
            }
        }

        return $deleted;
    }

    public function search($query, $options = [])
    {
        if (!static::$searchIndex) {
            throw new \Exception("This model is not searchable");
        }

        try {
            $index = $this->meilisearch->index(static::$searchIndex);
            return $index->search($query, $options);
        } catch (\Exception $e) {
            error_log("Meilisearch search error: " . $e->getMessage());
            // Fallback to basic flat-file search if Meilisearch fails
            return $this->fallbackSearch($query);
        }
    }

    protected function fallbackSearch($query)
    {
        $allData = $this->db->load(static::$collection);
        $query = strtolower($query);

        return array_filter($allData, function($item) use ($query) {
            // Basic search through all string values
            foreach ($item as $value) {
                if (is_string($value) && str_contains(strtolower($value), $query)) {
                    return true;
                }
            }
            return false;
        });
    }

    public function all()
    {
        return $this->db->load(static::$collection);
    }

    public function query(callable $callback)
    {
        return $this->db->query(static::$collection, $callback);
    }

    public function backup()
    {
        return $this->db->backup(static::$collection);
    }

    public function restore($timestamp)
    {
        $restored = $this->db->restore(static::$collection, $timestamp);

        if ($restored && static::$searchIndex) {
            try {
                // Re-index all data in Meilisearch
                $allData = $this->db->load(static::$collection);
                $index = $this->meilisearch->index(static::$searchIndex);
                $index->deleteAllDocuments();
                $index->addDocuments(array_values($allData));
            } catch (\Exception $e) {
                error_log("Meilisearch reindexing error: " . $e->getMessage());
            }
        }

        return $restored;
    }

    public function reindex()
    {
        if (!static::$searchIndex) {
            throw new \Exception("This model is not searchable");
        }

        try {
            $allData = $this->db->load(static::$collection);
            $index = $this->meilisearch->index(static::$searchIndex);
            $index->deleteAllDocuments();
            $index->addDocuments(array_values($allData));
            return true;
        } catch (\Exception $e) {
            error_log("Meilisearch reindexing error: " . $e->getMessage());
            return false;
        }
    }
}
