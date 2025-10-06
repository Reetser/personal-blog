<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php
    // Set to true temporarily to see debugging info inside HTML comments (view-source)
    $debug = false;

    // Try document-root path first (avoids problems when including from different folders)
    $jsonPath = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/database/meta-tags.json';

    // fallback to relative path if not found
    if (!file_exists($jsonPath)) {
        $jsonPath = './database/meta-tags.json';
    }

    // read JSON (suppress warnings with @ and handle errors)
    $raw = @file_get_contents($jsonPath);
    if ($raw === false) {
        $metaRaw = [];
        $jsonError = 'file_get_contents failed';
    } else {
        $metaRaw = json_decode($raw, true);
        $jsonError = (json_last_error() === JSON_ERROR_NONE) ? null : json_last_error_msg();
        if (!is_array($metaRaw))
            $metaRaw = [];
    }

    // Normalize keys to lowercase so lookups are case-insensitive
    $metaData = array_change_key_case($metaRaw, CASE_LOWER);

    // page param (lowercased)
    $page = strtolower($_GET['page'] ?? 'home');

    // defaults
    $defaults = [
        'title' => 'Resteer | Home',
        'description' => "Welcome to Resteer's website.",
        'url' => '/',
        'image' => '/assets/images/default.jpg',
        'author' => 'Resteer John Lumbab',
        'language' => 'en',
        'type' => 'website',
    ];

    // merge defaults with page data (if exists)
    $currentMeta = array_merge($defaults, $metaData[$page] ?? []);

    // debug output (HTML comments, invisible on page)
    if ($debug) {
        $keys = implode(', ', array_keys($metaData));
        echo "\n<!-- DEBUG: jsonPath: {$jsonPath} -->\n";
        echo "<!-- DEBUG: file_exists: " . (file_exists($jsonPath) ? 'yes' : 'no') . " -->\n";
        echo "<!-- DEBUG: jsonError: " . ($jsonError ?? 'none') . " -->\n";
        echo "<!-- DEBUG: page param: " . htmlspecialchars($page, ENT_QUOTES, 'UTF-8') . " -->\n";
        echo "<!-- DEBUG: json keys: " . htmlspecialchars($keys, ENT_QUOTES, 'UTF-8') . " -->\n";
        echo "<!-- DEBUG: currentMeta: " . htmlspecialchars(json_encode($currentMeta), ENT_QUOTES, 'UTF-8') . " -->\n";
    }
    ?>

    <title><?= htmlspecialchars($currentMeta['title'], ENT_QUOTES, 'UTF-8') ?></title>
    <meta name="description" content="<?= htmlspecialchars($currentMeta['description'], ENT_QUOTES, 'UTF-8') ?>">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?= htmlspecialchars($currentMeta['url'], ENT_QUOTES, 'UTF-8') ?>">
    <meta name="author" content="<?= htmlspecialchars($currentMeta['author'], ENT_QUOTES, 'UTF-8') ?>">
    <meta http-equiv="Content-Language"
        content="<?= htmlspecialchars($currentMeta['language'], ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:title" content="<?= htmlspecialchars($currentMeta['title'], ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:description" content="<?= htmlspecialchars($currentMeta['description'], ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:image" content="<?= htmlspecialchars($currentMeta['image'], ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:url" content="<?= htmlspecialchars($currentMeta['url'], ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:type" content="<?= htmlspecialchars($currentMeta['type'], ENT_QUOTES, 'UTF-8') ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($currentMeta['title'], ENT_QUOTES, 'UTF-8') ?>">
    <meta name="twitter:description"
        content="<?= htmlspecialchars($currentMeta['description'], ENT_QUOTES, 'UTF-8') ?>">
    <meta name="twitter:image" content="<?= htmlspecialchars($currentMeta['image'], ENT_QUOTES, 'UTF-8') ?>">


    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/Resteer John L. Lumbab_PFP.webp">
    <!-- Remix icons -->
    <link href="/assets/css/remixicon.css" rel="stylesheet">
    <!-- Swiper.js styles -->
    <link rel="stylesheet" href="/assets/css/swiper-bundle.min.css" />
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
                        <a href="/pages/photos.php?page=photos" class="list-link">Photos</a>
                    </li>
                    <li class="list-item">
                        <a href="/pages/videos.php?page=videos" class="list-link">Videos</a>
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
                <input class="form-input" type="text" placeholder="What are you looking for?" name="q">
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