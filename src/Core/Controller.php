<?php

namespace App\Core;

class Controller
{
    protected $app;

    public function __construct()
    {
        $this->app = App::getInstance();
    }

    protected function view($name, $data = [])
    {
        extract($data);
        
        // Add common data available to all views
        $isAuthenticated = $this->app->isAuthenticated();
        $user = $this->app->getUser();
        
        // Start output buffering
        ob_start();
        
        // Include the view file
        require_once __DIR__ . "/../../views/{$name}.php";
        
        // Get the contents and clean the buffer
        $content = ob_get_clean();
        
        // Include the layout with the content
        require_once __DIR__ . "/../../views/layouts/main.php";
    }

    protected function redirect($path)
    {
        header("Location: {$path}");
        exit();
    }

    protected function json($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
}
