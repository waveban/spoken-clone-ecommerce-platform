# Spoken Clone

A modern e-commerce platform built with PHP, featuring Meilisearch integration for powerful search capabilities, Logto.ai for user authentication, and a flat-file database system for data storage.

## Features

- 🔍 Fast, typo-tolerant search powered by Meilisearch
- 🔐 Secure authentication via Logto.ai
- 💾 Efficient flat-file database system
- 🎨 Modern UI with Tailwind CSS
- 📱 Fully responsive design
- 🚀 Fast and SEO-friendly

## Requirements

- PHP 8.1 or higher
- Meilisearch server running
- Logto.ai account
- Apache/Nginx web server
- Composer

## Installation

1. Clone the repository:
```bash
git clone <repository-url>
cd spoken-clone
```

2. Install dependencies:
```bash
composer install
```

3. Copy the environment file and configure it:
```bash
cp .env.example .env
```

Edit the `.env` file with your configuration:
- Set your Meilisearch host and API key
- Configure your Logto.ai credentials
- Set the base URL for your application

4. Initialize the application:
```bash
php init.php
```

5. Start the development server:
```bash
php -S localhost:8000 -t public
```

## Project Structure

```
├── data/                  # Flat-file database storage
├── public/               # Public directory
│   └── index.php        # Entry point
├── src/                  # Source code
│   ├── Controllers/     # Application controllers
│   ├── Core/            # Core framework classes
│   ├── Models/          # Data models
│   └── views/           # View templates
├── .env                 # Environment configuration
├── .htaccess           # Apache configuration
└── composer.json       # Composer dependencies
```

## Authentication

Authentication is handled through Logto.ai. The following routes are available:
- `/login` - Initiates the login process
- `/callback` - Handles the OAuth callback
- `/logout` - Logs out the current user

## Search

The search functionality is powered by Meilisearch:
- Fast, typo-tolerant search
- Filtering by category, price range
- Sorting by price, date
- Real-time search suggestions

## Database

The application uses a flat-file database system for data storage:
- JSON-based storage
- CRUD operations
- Transaction support
- Automatic backups
- Data validation

## Development

### Adding New Products

Products can be added through the Product model:

```php
$product = new Product();
$product->create([
    'title' => 'Product Name',
    'description' => 'Product Description',
    'price' => 99.99,
    'image_url' => 'https://example.com/image.jpg',
    'category' => 'category-name',
    'status' => 'active'
]);
```

### Search Configuration

Meilisearch settings can be modified in `init.php`:
- Searchable attributes
- Filterable attributes
- Sortable attributes
- Ranking rules

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a new Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Acknowledgments

- Meilisearch for search functionality
- Logto.ai for authentication
- Tailwind CSS for styling
