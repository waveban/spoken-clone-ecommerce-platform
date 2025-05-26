<?php

namespace App\Controllers;

use App\Core\Controller;

class SearchController extends Controller
{
    public function index()
    {
        $query = $_GET['q'] ?? '';
        $page = max(1, intval($_GET['page'] ?? 1));
        $perPage = 12;

        $meilisearch = $this->app->getMeilisearch();
        $index = $meilisearch->index('home');

        $results = $index->search($query, [
            'limit' => $perPage,
            'offset' => ($page - 1) * $perPage,
            'sort' => ['created_at:desc'],
            'attributesToHighlight' => ['title', 'description']
        ]);

        // Calculate pagination data
        $total = $results->getEstimatedTotalHits();
        $totalPages = ceil($total / $perPage);

        return $this->view('search', [
            'title' => $query ? "Search: {$query}" : 'Search Items',
            'query' => $query,
            'items' => $results->getHits(),
            'total' => $total,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'perPage' => $perPage
        ]);
    }

    public function search()
    {
        $query = $_POST['q'] ?? '';
        
        $meilisearch = $this->app->getMeilisearch();
        $index = $meilisearch->index('home');

        $results = $index->search($query, [
            'limit' => 5,
            'attributesToHighlight' => ['title'],
            'attributesToRetrieve' => ['id', 'title', 'price', 'image_url']
        ]);

        // Return JSON for AJAX requests
        return $this->json([
            'success' => true,
            'results' => $results->getHits()
        ]);
    }
}
