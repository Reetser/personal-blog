<?php
// Load JSON data
$data = json_decode(file_get_contents('./features/search-engine/data.json'), true);

// Get search query
$query = isset($_GET['q']) ? trim($_GET['q']) : '';

$results = [];
if ($query !== '') {
    foreach ($data as $item) {
        if (
            stripos($item['title'] ?? '', $query) !== false ||
            stripos($item['description'] ?? '', $query) !== false
        ) {
            $results[] = $item;
        }
    }
}
?>

    <?php include("./templates/header.php") ?>

<main class="search-results section section-header-offset  older-posts-grid-wrapper d-grid">
    <h2>Search Results for "<?php echo htmlspecialchars($query ?? '', ENT_QUOTES, 'UTF-8'); ?>"</h2>

    <?php if ($query === ''): ?>
        <p>Please enter a search term.</p>
    <?php elseif (count($results) > 0): ?>
        <?php foreach ($results as $item): ?>
            <a href="<?php echo htmlspecialchars($item['url'] ?? '#', ENT_QUOTES, 'UTF-8'); ?>" class="article d-grid">
                <div class="older-posts-article-image-wrapper">
                    <img src="<?php echo htmlspecialchars($item['image'] ?? './assets/images/default.jpg', ENT_QUOTES, 'UTF-8'); ?>" 
                         alt="<?php echo htmlspecialchars($item['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" 
                         class="article-image">
                </div>

                <div class="article-data-container">
                    <div class="article-data">
                        <span><?php echo htmlspecialchars($item['date'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
                        <span class="article-data-spacer"></span>
                        <span><?php echo htmlspecialchars($item['category'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>

                    <h3 class="title article-title">
                        <?php echo htmlspecialchars($item['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                    </h3>
                    <p class="article-description">
                        <?php echo htmlspecialchars($item['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                    </p>
                </div>
            </a>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No results found.</p>
    <?php endif; ?>
</main>
    <?php include("./templates/footer.php") ?>
