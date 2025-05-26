<?php

namespace App\Models;

use App\Core\Model;

class Product extends Model
{
    protected static $collection = 'products';
    protected static $searchIndex = 'home';

    // Validation rules for product data
    private static $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric|min:0',
        'image_url' => 'required|url',
        'category' => 'required|string',
        'status' => 'string|in:active,inactive,draft'
    ];

    public function validate($data)
    {
        $errors = [];

        foreach (self::$rules as $field => $rules) {
            $ruleArray = explode('|', $rules);
            
            foreach ($ruleArray as $rule) {
                if (strpos($rule, ':') !== false) {
                    [$ruleName, $ruleValue] = explode(':', $rule);
                } else {
                    $ruleName = $rule;
                    $ruleValue = null;
                }

                switch ($ruleName) {
                    case 'required':
                        if (!isset($data[$field]) || empty($data[$field])) {
                            $errors[$field][] = "The {$field} field is required.";
                        }
                        break;

                    case 'string':
                        if (isset($data[$field]) && !is_string($data[$field])) {
                            $errors[$field][] = "The {$field} must be a string.";
                        }
                        break;

                    case 'numeric':
                        if (isset($data[$field]) && !is_numeric($data[$field])) {
                            $errors[$field][] = "The {$field} must be a number.";
                        }
                        break;

                    case 'url':
                        if (isset($data[$field]) && !filter_var($data[$field], FILTER_VALIDATE_URL)) {
                            $errors[$field][] = "The {$field} must be a valid URL.";
                        }
                        break;

                    case 'max':
                        if (isset($data[$field]) && strlen($data[$field]) > (int)$ruleValue) {
                            $errors[$field][] = "The {$field} may not be greater than {$ruleValue} characters.";
                        }
                        break;

                    case 'min':
                        if (isset($data[$field]) && $data[$field] < (float)$ruleValue) {
                            $errors[$field][] = "The {$field} must be at least {$ruleValue}.";
                        }
                        break;

                    case 'in':
                        $allowedValues = explode(',', $ruleValue);
                        if (isset($data[$field]) && !in_array($data[$field], $allowedValues)) {
                            $errors[$field][] = "The {$field} must be one of: " . implode(', ', $allowedValues);
                        }
                        break;
                }
            }
        }

        return $errors;
    }

    public function create($data)
    {
        // Validate the data
        $errors = $this->validate($data);
        if (!empty($errors)) {
            throw new \Exception(json_encode($errors));
        }

        // Set default values
        $data['status'] = $data['status'] ?? 'active';
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        // Save to database and index
        return $this->save($data);
    }

    public function update($id, $data)
    {
        // Get existing product
        $existing = $this->find($id);
        if (!$existing) {
            throw new \Exception("Product not found");
        }

        // Merge existing data with updates
        $updatedData = array_merge($existing, $data);
        
        // Validate the merged data
        $errors = $this->validate($updatedData);
        if (!empty($errors)) {
            throw new \Exception(json_encode($errors));
        }

        // Update timestamp
        $updatedData['updated_at'] = date('Y-m-d H:i:s');

        // Save changes
        return $this->save($updatedData);
    }

    public function getFeatured($limit = 6)
    {
        return $this->query(function($item) {
            return ($item['status'] ?? '') === 'active';
        });
    }

    public function getByCategory($category)
    {
        return $this->findBy('category', $category);
    }

    public function searchProducts($query, $filters = [], $sort = [], $page = 1, $limit = 12)
    {
        $searchOptions = [
            'limit' => $limit,
            'offset' => ($page - 1) * $limit,
        ];

        if (!empty($filters)) {
            $searchOptions['filter'] = $filters;
        }

        if (!empty($sort)) {
            $searchOptions['sort'] = $sort;
        }

        return $this->search($query, $searchOptions);
    }
}
