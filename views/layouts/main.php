<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Spoken Clone' ?></title>
    
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-white">
    <!-- Navigation -->
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="/" class="text-xl font-bold text-gray-900">Spoken</a>
                    </div>
                </div>
                
                <div class="flex items-center">
                    <?php if ($isAuthenticated): ?>
                        <a href="/dashboard" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                            Dashboard
                        </a>
                        <a href="/logout" class="ml-4 text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                            Logout
                        </a>
                    <?php else: ?>
                        <a href="/login" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                            Login
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-50 mt-12">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600 tracking-wider uppercase">Company</h3>
                    <ul class="mt-4 space-y-4">
                        <li>
                            <a href="/about" class="text-base text-gray-500 hover:text-gray-900">About</a>
                        </li>
                        <li>
                            <a href="/contact" class="text-base text-gray-500 hover:text-gray-900">Contact</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-600 tracking-wider uppercase">Legal</h3>
                    <ul class="mt-4 space-y-4">
                        <li>
                            <a href="/privacy" class="text-base text-gray-500 hover:text-gray-900">Privacy</a>
                        </li>
                        <li>
                            <a href="/terms" class="text-base text-gray-500 hover:text-gray-900">Terms</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-200 pt-8">
                <p class="text-base text-gray-400">&copy; <?= date('Y') ?> Spoken Clone. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
