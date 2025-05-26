<!-- Search Header -->
<div class="bg-white">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl font-extrabold tracking-tight text-gray-900">Search Results</h1>
            <p class="mt-4 max-w-xl mx-auto text-base text-gray-500">
                <?= $total ?> items found <?= $query ? "for \"" . htmlspecialchars($query) . "\"" : "" ?>
            </p>
        </div>

        <!-- Search Form -->
        <div class="mt-8 max-w-3xl mx-auto">
            <form action="/search" method="GET" class="mt-1 flex rounded-md shadow-sm">
                <input type="text" 
                       name="q" 
                       value="<?= htmlspecialchars($query) ?>"
                       class="focus:ring-pink-500 focus:border-pink-500 flex-1 block w-full rounded-l-md sm:text-sm border-gray-300" 
                       placeholder="Search for items...">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-r-md text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                    Search
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Results Grid -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 gap-y-10 sm:grid-cols-2 gap-x-6 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
        <?php foreach ($items as $item): ?>
        <div class="group">
            <div class="w-full aspect-w-1 aspect-h-1 bg-gray-200 rounded-lg overflow-hidden xl:aspect-w-7 xl:aspect-h-8">
                <img src="<?= htmlspecialchars($item['image_url']) ?>" 
                     alt="<?= htmlspecialchars($item['title']) ?>" 
                     class="w-full h-full object-center object-cover group-hover:opacity-75">
            </div>
            <h3 class="mt-4 text-sm text-gray-700">
                <?= htmlspecialchars($item['title']) ?>
            </h3>
            <p class="mt-1 text-lg font-medium text-gray-900">
                $<?= number_format($item['price'], 2) ?>
            </p>
            <p class="mt-1 text-sm text-gray-500">
                <?= htmlspecialchars(substr($item['description'], 0, 100)) ?>...
            </p>
            <a href="/item/<?= $item['id'] ?>" class="mt-2 inline-block text-sm text-pink-600 hover:text-pink-500">
                View Details →
            </a>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6 mt-8">
        <div class="flex-1 flex justify-between sm:hidden">
            <?php if ($currentPage > 1): ?>
            <a href="?q=<?= urlencode($query) ?>&page=<?= $currentPage - 1 ?>" 
               class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Previous
            </a>
            <?php endif; ?>
            
            <?php if ($currentPage < $totalPages): ?>
            <a href="?q=<?= urlencode($query) ?>&page=<?= $currentPage + 1 ?>" 
               class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Next
            </a>
            <?php endif; ?>
        </div>
        
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Showing 
                    <span class="font-medium"><?= (($currentPage - 1) * $perPage) + 1 ?></span>
                    to 
                    <span class="font-medium"><?= min($currentPage * $perPage, $total) ?></span>
                    of 
                    <span class="font-medium"><?= $total ?></span>
                    results
                </p>
            </div>
            
            <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <?php if ($currentPage > 1): ?>
                    <a href="?q=<?= urlencode($query) ?>&page=<?= $currentPage - 1 ?>" 
                       class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Previous</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <?php endif; ?>

                    <?php
                    $start = max(1, $currentPage - 2);
                    $end = min($totalPages, $currentPage + 2);
                    
                    for ($i = $start; $i <= $end; $i++):
                    ?>
                    <a href="?q=<?= urlencode($query) ?>&page=<?= $i ?>" 
                       class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium <?= $i === $currentPage ? 'text-pink-600 bg-pink-50' : 'text-gray-700 hover:bg-gray-50' ?>">
                        <?= $i ?>
                    </a>
                    <?php endfor; ?>

                    <?php if ($currentPage < $totalPages): ?>
                    <a href="?q=<?= urlencode($query) ?>&page=<?= $currentPage + 1 ?>" 
                       class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Next</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- No Results Message -->
<?php if (empty($items)): ?>
<div class="text-center py-12">
    <h3 class="mt-2 text-sm font-medium text-gray-900">No items found</h3>
    <p class="mt-1 text-sm text-gray-500">Try adjusting your search terms or browse our featured items.</p>
    <div class="mt-6">
        <a href="/" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
            View Featured Items
        </a>
    </div>
</div>
<?php endif; ?>
