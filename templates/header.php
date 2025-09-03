
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php
// Detect page key
if (isset($_GET['page']) && $_GET['page'] !== '') {
    $pageParam = strtolower($_GET['page']);
} else {
    $pageParam = strtolower(pathinfo($_SERVER['SCRIPT_FILENAME'], PATHINFO_FILENAME));
}

// Load JSON relative to this file's directory
$metaFile = '../database/meta-tags.json';
if (file_exists($metaFile)) {
    $metaData = json_decode(file_get_contents($metaFile), true);
} else {
    $metaData = [];
}

// Default meta values
$page_title       = "Resteer | Home";
$page_description = "Welcome to Resteer's website.";
$page_url         = "https://resteerjohn.com";
$page_image       = "https://example.com/images/default.jpg";
$page_author      = "Resteer John Lumbab";
$page_language    = "en";
$page_type        = "website";

// Overwrite if found in JSON
if (isset($metaData[$pageParam])) {
    $data = $metaData[$pageParam];
    $page_title       = $data['title'] ?? $page_title;
    $page_description = $data['description'] ?? $page_description;
    $page_url         = $data['url'] ?? $page_url;
    $page_image       = $data['image'] ?? $page_image;
    $page_author      = $data['author'] ?? $page_author;
    $page_language    = $data['language'] ?? $page_language;
    $page_type        = $data['type'] ?? $page_type;
}


?>
<!-- Dynamic Meta Tags -->
<title><?= htmlspecialchars($page_title) ?></title>
<meta name="description" content="<?= htmlspecialchars($page_description) ?>">
<meta name="robots" content="index, follow">
<link rel="canonical" href="<?= htmlspecialchars($page_url) ?>">
<meta name="author" content="<?= htmlspecialchars($page_author) ?>">
<meta http-equiv="Content-Language" content="<?= htmlspecialchars($page_language) ?>">
<meta property="og:title" content="<?= htmlspecialchars($page_title) ?>">
<meta property="og:description" content="<?= htmlspecialchars($page_description) ?>">
<meta property="og:image" content="<?= htmlspecialchars($page_image) ?>">
<meta property="og:url" content="<?= htmlspecialchars($page_url) ?>">
<meta property="og:type" content="<?= htmlspecialchars($page_type) ?>">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= htmlspecialchars($page_title) ?>">
<meta name="twitter:description" content="<?= htmlspecialchars($page_description) ?>">
<meta name="twitter:image" content="<?= htmlspecialchars($page_image) ?>">
    
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="./assets/images/Resteer John L. Lumbab_PFP.webp">
    <!-- Remix icons -->
    <link href="/assets/css/remixicon.css" rel="stylesheet">
    <!-- Swiper.js styles -->
    <link rel="stylesheet" href="/assets/css/swiper-bundle.min.css"/>
    <!-- Custom styles -->
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/dev-gate.css">
    <link rel="stylesheet" href="/assets/css/age-gate.css">
    <link rel="stylesheet" href="/assets/css/newsletter.css">
    <link rel="stylesheet" href="/assets/css/link-preview.css">
    <link rel="stylesheet" href="/assets/css/video-js.css">
    <link rel="stylesheet" href="/assets/css/video-container.css">
    <link rel="stylesheet" href="/assets/css/photos-gate.css">
    <link rel="stylesheet" href="/assets/css/lightbox.css">
</head>



<body>


      
    <!-- Header -->
    <header class="header" id="header">

        <nav class="navbar container">
            <a href="/">
                <h2 class="logo">Resteer</h2>
            </a>

            <div class="menu" id="menu">
                <ul class="list">
                    <li class="list-item">
                        <a href="/" class="list-link current">Home</a>
                    </li>
                    <li class="list-item">
                        <a href="/blogs.php?page=blogs" class="list-link">Blog</a>
                    </li>
                    <li class="list-item">
                        <a href="/pages/photos.php" class="list-link">Photos</a>
                    </li>
                    <li class="list-item">
                        <a href="/pages/videos.php" class="list-link">Videos</a>
                    </li>
                    <li class="list-item">
                        <a href="/pages/about.php?page=about" class="list-link">About</a>
                    </li>
                    <li class="list-item">
                        <a href="/pages/contact.php?page=contact" class="list-link">Contact</a>
                    </li>
                    <li class="list-item">
                        <a href="/pages/credits.php?page=credits" class="list-link">Credits</a>
                    </li>
                    <li class="list-item">
                        <a href="#categories" class="list-link">Categories</a>
                    </li>
                    <li class="list-item">
                        <a href="#site_policies" class="list-link">Site Policies</a>
                    </li>


                </ul>
            </div>

            <div class="list list-right">
                <button class="btn place-items-center" id="theme-toggle-btn">
                    <i class="ri-sun-line sun-icon"></i>
                    <i class="ri-moon-line moon-icon"></i>
                </button>

                <button class="btn place-items-center" id="search-icon">
                    <i class="ri-search-line"></i>
                </button>

                <button class="btn place-items-center screen-lg-hidden menu-toggle-icon" id="menu-toggle-icon">
                    <i class="ri-menu-3-line open-menu-icon"></i>
                    <i class="ri-close-line close-menu-icon"></i>
                </button>
              
            </div>

        </nav>

    </header>

    <!-- Search -->
    <div class="search-form-container container" id="search-form-container">

        <div class="form-container-inner">

            <form action="search.php" method="get" class="form">
                <input class="form-input" type="text" placeholder="What are you looking for?" name= "q">
                <button class="btn form-btn" type="submit">
                    <i class="ri-search-line"></i>
                </button>
            </form>
            <span class="form-note">Or press ESC to close.</span>

        </div>

        <button class="btn form-close-btn place-items-center" id="form-close-btn">
            <i class="ri-close-line"></i>
        </button>

    </div>
    


<?php include("age-gate.php") ?>
<?php include("dev-gate.php") ?>
<?php include("link-tooltip.php") ?>
