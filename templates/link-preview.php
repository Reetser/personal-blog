<?php
header('Content-Type: application/json');

if (!isset($_GET['url']) || empty($_GET['url'])) {
    echo json_encode(['error' => 'No URL provided']);
    exit;
}

$url = filter_var($_GET['url'], FILTER_SANITIZE_URL);

if (!filter_var($url, FILTER_VALIDATE_URL)) {
    echo json_encode(['error' => 'Invalid URL']);
    exit;
}

// Basic malicious check
$maliciousKeywords = ['.exe', '.zip', '.scr', 'javascript:', 'data:'];
foreach ($maliciousKeywords as $keyword) {
    if (stripos($url, $keyword) !== false) {
        echo json_encode(['error' => 'Potentially unsafe URL']);
        exit;
    }
}

// Fetch HTML safely
$context = stream_context_create([
    'http' => ['timeout' => 5, 'header' => "User-Agent: PrivacyLinkPreview/1.0\r\n"]
]);

$html = @file_get_contents($url, false, $context);

if (!$html) {
    echo json_encode(['error' => 'Could not fetch URL']);
    exit;
}

$doc = new DOMDocument();
@$doc->loadHTML($html);

// Title
$title = '';
if ($nodes = $doc->getElementsByTagName('title')) {
    $title = trim($nodes->item(0)->textContent);
}

// Meta description
$description = '';
$metas = $doc->getElementsByTagName('meta');
foreach ($metas as $meta) {
    if (strtolower($meta->getAttribute('name')) === 'description') {
        $description = $meta->getAttribute('content');
        break;
    }
}

// Open Graph image
$image = '';
foreach ($metas as $meta) {
    if (strtolower($meta->getAttribute('property')) === 'og:image') {
        $image = $meta->getAttribute('content');
        break;
    }
}

echo json_encode([
    'title' => $title ?: parse_url($url, PHP_URL_HOST),
    'description' => $description ?: '',
    'image' => $image ?: '',
    'url' => $url
]);

