<?php

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        // Get featured items from Meilisearch
        $meilisearch = $this->app->getMeilisearch();
        $index = $meilisearch->index('home');
        
        // Get featured items (limit to 6)
        $featured = $index->search('', [
            'limit' => 6,
            'sort' => ['created_at:desc']
        ]);

        return $this->view('home', [
            'title' => 'Welcome to Spoken Clone',
            'featured' => $featured->getHits()
        ]);
    }
}
