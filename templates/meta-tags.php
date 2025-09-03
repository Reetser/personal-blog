<?php
// Get page parameter
$pageParam = $_GET['page'] ?? 'default';

// Load JSON file
$metaFile = __DIR__ . '/../database/meta-tags.json';
if (file_exists($metaFile)) {
    $metaData = json_decode(file_get_contents($metaFile), true);
} else {
    $metaData = [];
}

// Default meta values
$page_title       = "Resteer";
$page_description = "Welcome to Resteer's website.";
$page_url         = "https://resteerjohn.com";
$page_image       = "https://resteerjohn.com/assets/images/default.jpeg";
$page_author      = "Resteer John Lumbab";
$page_language    = "en";
$page_type        = "website";

// If data exists for the page parameter, overwrite defaults
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

