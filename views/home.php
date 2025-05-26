<!-- Hero Section -->
<div class="relative bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto">
        <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
            <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                <div class="sm:text-center lg:text-left">
                    <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                        <span class="block">Never overpay making</span>
                        <span class="block text-pink-600">your home beautiful</span>
                    </h1>
                    <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                        Discover beautiful home decor items at the best prices. Join our community of home enthusiasts.
                    </p>
                    <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                        <div class="rounded-md shadow">
                            <a href="/search" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-pink-600 hover:bg-pink-700 md:py-4 md:text-lg md:px-10">
                                Start exploring
                            </a>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
        <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full" 
             src="https://images.pexels.com/photos/1571460/pexels-photo-1571460.jpeg" 
             alt="Modern home interior">
    </div>
</div>

<!-- Featured Items Section -->
<div class="bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
            <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                Featured Items
            </h2>
            <p class="mt-4 max-w-2xl mx-auto text-xl text-gray-500">
                Discover our latest and most popular home decor items
            </p>
        </div>

        <div class="mt-12 grid gap-8 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($featured as $item): ?>
            <div class="group relative bg-white rounded-lg shadow-sm hover:shadow-lg transition-shadow duration-200">
                <div class="w-full min-h-80 aspect-w-1 aspect-h-1 rounded-t-lg overflow-hidden group-hover:opacity-75">
                    <img src="<?= htmlspecialchars($item['image_url']) ?>" 
                         alt="<?= htmlspecialchars($item['title']) ?>"
                         class="w-full h-full object-center object-cover">
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-medium text-gray-900">
                        <a href="/item/<?= htmlspecialchars($item['id']) ?>">
                            <?= htmlspecialchars($item['title']) ?>
                        </a>
                    </h3>
                    <p class="mt-1 text-sm text-gray-500"><?= htmlspecialchars($item['description']) ?></p>
                    <p class="mt-2 text-xl font-semibold text-gray-900">$<?= number_format($item['price'], 2) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-12 text-center">
            <a href="/search" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-pink-600 bg-white hover:bg-gray-50">
                View all items
                <svg class="ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </a>
        </div>
    </div>
</div>

<!-- Search Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center">
        <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">
            Find Your Perfect Item
        </h2>
        <p class="mt-4 max-w-2xl mx-auto text-xl text-gray-500">
            Search through thousands of carefully curated home decor items
        </p>
    </div>

    <div class="mt-8 max-w-3xl mx-auto">
        <form action="/search" method="GET" class="mt-1 flex rounded-md shadow-sm">
            <input type="text" 
                   name="q" 
                   class="focus:ring-pink-500 focus:border-pink-500 flex-1 block w-full rounded-l-md sm:text-sm border-gray-300" 
                   placeholder="Search for items...">
            <button type="submit" 
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-r-md text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                Search
            </button>
        </form>
    </div>
</div>
