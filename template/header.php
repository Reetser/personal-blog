<?php
// Load JSON safely
$metaFile = "../assets/secure/meta-data.json";
$metaData = [];

if (file_exists($metaFile)) {
    $json = file_get_contents($metaFile);
    $metaData = json_decode($json, true) ?? [];
}

// Get page/post ID (?id=post-1), fallback to "home"
$id = filter_input(INPUT_GET, 'id', FILTER_UNSAFE_RAW) ?? "home";

// Sanitize manually (only allow letters, numbers, dash)
$id = preg_replace('/[^a-zA-Z0-9\-]/', '', $id);

// Default meta (fallback if key missing)
$meta = $metaData['home'] ?? [
    "title" => "Resteer John L. Lumbab",
    "description" => "Welcome, I'm Resteer. Here's my space in digital world",
    "keywords" => "blog, minimal, privacy, simple",
    "author" => "Resteer John L. Lumbab"
];

// Override if entry exists
if (!empty($metaData[$id])) {
    $meta = $metaData[$id];
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Dynamic Meta -->
  <title><?= htmlspecialchars($meta['title'] ?? '', ENT_QUOTES, 'UTF-8') ?></title>
  <meta name="description" content="<?= htmlspecialchars($meta['description'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
  <meta name="keywords" content="<?= htmlspecialchars($meta['keywords'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
  <meta name="author" content="<?= htmlspecialchars($meta['author'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

  <!-- Open Graph (SEO + Social Sharing) -->
  <meta property="og:title" content="<?= htmlspecialchars($meta['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:description" content="<?= htmlspecialchars($meta['description'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:type" content="website">
  <meta property="og:url" content="<?= htmlspecialchars('https://resteerjohn.com' . $_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8') ?>">

  <link rel="stylesheet" href="/assets/css/main.css">
  <link rel="stylesheet" href="/assets/css/consent.css">

</head>
<body>

  <header>
    <h2>Resteer John L. Lumbab</h2>
    <p>Thoughts • Experience • Discovery</p>
  </header>

  <nav class="site">
    <a href="/">Home</a>
    <a href="/pages/about.php?id=about">About</a>
    <a href="/pages/contact.php?id=contact">Contact</a>
  </nav>