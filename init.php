<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\Product;
use App\Core\App;

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Initialize the application
$app = new App();

// Create sample products
$products = [
    [
        'id' => '1',
        'title' => 'Modern Minimalist Sofa',
        'description' => 'A beautiful, comfortable sofa perfect for modern homes. Features clean lines and premium fabric.',
        'price' => 899.99,
        'image_url' => 'https://images.pexels.com/photos/1571460/pexels-photo-1571460.jpeg',
        'category' => 'furniture',
        'status' => 'active'
    ],
    [
        'id' => '2',
        'title' => 'Elegant Table Lamp',
        'description' => 'Contemporary table lamp with adjustable brightness. Perfect for bedside or office use.',
        'price' => 129.99,
        'image_url' => 'https://images.pexels.com/photos/1112598/pexels-photo-1112598.jpeg',
        'category' => 'lighting',
        'status' => 'active'
    ],
    [
        'id' => '3',
        'title' => 'Decorative Wall Mirror',
        'description' => 'Round wall mirror with golden frame. Adds elegance to any room.',
        'price' => 199.99,
        'image_url' => 'https://images.pexels.com/photos/1099816/pexels-photo-1099816.jpeg',
        'category' => 'decor',
        'status' => 'active'
    ],
    [
        'id' => '4',
        'title' => 'Plush Area Rug',
        'description' => 'Soft, comfortable area rug with modern geometric pattern. Available in multiple sizes.',
        'price' => 299.99,
        'image_url' => 'https://images.pexels.com/photos/1571471/pexels-photo-1571471.jpeg',
        'category' => 'rugs',
        'status' => 'active'
    ],
    [
        'id' => '5',
        'title' => 'Ceramic Vase Set',
        'description' => 'Set of 3 ceramic vases in varying sizes. Perfect for fresh or dried flowers.',
        'price' => 79.99,
        'image_url' => 'https://images.pexels.com/photos/1139785/pexels-photo-1139785.jpeg',
        'category' => 'decor',
        'status' => 'active'
    ],
    [
        'id' => '6',
        'title' => 'Modern Coffee Table',
        'description' => 'Sleek coffee table with storage shelf. Made from solid wood and metal.',
        'price' => 449.99,
        'image_url' => 'https://images.pexels.com/photos/1571458/pexels-photo-1571458.jpeg',
        'category' => 'furniture',
        'status' => 'active'
    ]
];

try {
    // Initialize Product model
    $productModel = new Product();

    // Create data directory if it doesn't exist
    if (!is_dir(__DIR__ . '/data')) {
        mkdir(__DIR__ . '/data', 0777, true);
    }

    // Save sample products
    foreach ($products as $product) {
        try {
            $productModel->create($product);
            echo "Created product: {$product['title']}\n";
        } catch (\Exception $e) {
            echo "Error creating product {$product['title']}: {$e->getMessage()}\n";
        }
    }

    // Initialize Meilisearch index
    $meilisearch = $app->getMeilisearch();
    $index = $meilisearch->index('home');

    // Configure index settings
    $index->updateSettings([
        'searchableAttributes' => [
            'title',
            'description',
            'category'
        ],
        'filterableAttributes' => [
            'category',
            'price',
            'status'
        ],
        'sortableAttributes' => [
            'price',
            'created_at'
        ]
    ]);

    // Add products to Meilisearch
    $index->addDocuments($products);

    echo "\nInitialization completed successfully!\n";
    echo "You can now start the development server using: php -S localhost:8000 -t public\n";

} catch (\Exception $e) {
    echo "Error during initialization: " . $e->getMessage() . "\n";
}
