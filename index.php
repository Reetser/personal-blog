
   
    <?php include("./templates/header.php") ?>

    <?php
// Load JSON file
$jsonFile = './database/blogs.json';
if (file_exists($jsonFile)) {
    $blogs = json_decode(file_get_contents($jsonFile), true);
} else {
    $blogs = [];
}

// Sort by date (latest first)
usort($blogs, function($a, $b) {
    return strtotime($b['date'] ?? '1970-01-01') - strtotime($a['date'] ?? '1970-01-01');
});

// Take only first 5
$latestBlogs = array_slice($blogs, 0, 5);
?>

    <!-- Age Get-->

    
    <!-- Featured articles -->
    <?php include("./templates/featured_articles.php") ?>

    <!-- Quick read -->
    <?php include("./templates/quick_read.php") ?>

    <!-- Older posts -->
    <?php include("./templates/older_posts.php") ?>

    <!-- Popular tags -->
    <?php include("./templates/popular_tags.php") ?>

    <!-- Newsletter -->
     <?php include("./templates/newsletter.php") ?>

    <!-- Footer -->
    <?php include("./templates/footer.php") ?>
    
    



