    <?php include("./templates/header.php") ?>

    <?php
// Load JSON file
$jsonFile = './database/blogs.json';
if (file_exists($jsonFile)) {
    $blogs = json_decode(file_get_contents($jsonFile), true);
} else {
    $blogs = [];
}
?>

    <section class="older-posts section section-header-offset">

        <div class="container">

            <h2 class="title section-title" data-name="Blog Posts">Blog</h2>

            <div class="older-posts-grid-wrapper d-grid">

<?php if (!empty($blogs)): ?>
    <?php foreach ($blogs as $blog): ?>
        <a href="<?php echo htmlspecialchars($blog['link']); ?>" class="article d-grid">
            <div class="older-posts-article-image-wrapper">
                <img src="<?php echo htmlspecialchars($blog['image']); ?>" alt="" class="article-image">
            </div>

            <div class="article-data-container">
                <div class="article-data">
                    <span><?php echo htmlspecialchars(date("d M Y", strtotime($blog['date']))); ?></span>
                    <span class="article-data-spacer"></span>
                    <span><?php echo htmlspecialchars($blog['category']); ?></span>
                </div>

                <h3 class="title article-title"><?php echo htmlspecialchars($blog['title']); ?></h3>
                <p class="article-description"><?php echo htmlspecialchars($blog['description']); ?></p>
            </div>
        </a>
    <?php endforeach; ?>
<?php else: ?>
    <p>No blogs found.</p>
<?php endif; ?>


            </div>

            <div class="see-more-container">
                <a href="#" class="btn see-more-btn place-items-center">See more <i class="ri-arrow-right-s-line"></i></a>
            </div>

        </div>

    </section>
    
    <?php include("./templates/footer.php") ?>