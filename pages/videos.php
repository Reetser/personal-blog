<?php
// Example video sources (local or remote URLs)
// $videos = [
//     [
//         'src' => '../assets/videos/video1.mp4',
//         'title' => 'Video 1',
//         'thumb' => '../assets/images/default.jpeg'
//     ],
//     [
//         'src' => '../assets/videos/video2.mp4',
//         'title' => 'Video 2',
//         'thumb' => '../assets/images/default.jpeg'
//     ],
//     [
//         'src' => '../assets/videos/video3.mp4',
//         'title' => 'Video 3',
//         'thumb' => '../assets/images/default.jpeg'
//     ]
// ];
?>
<?php
// Settings
$videoFolder = '../assets/videos';
$thumbFolder = '../assets/thumbnails';
$videoUrlPath = '../assets/videos';
$thumbUrlPath = 'thumbnails';

// Allowed file extensions
$allowedVideos = ['mp4', 'webm', 'ogg'];
$allowedThumbs = ['jpg', 'jpeg', 'png', 'gif'];

$videos = [];

// Helper function to format titles nicely
function formatTitle($filename) {
    $name = pathinfo($filename, PATHINFO_FILENAME);
    $name = str_replace(['-', '_'], ' ', $name);
    return ucwords(strtolower($name));
}

// Scan local videos
foreach (scandir($videoFolder) as $file) {
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    if (in_array($ext, $allowedVideos)) {
        $name = pathinfo($file, PATHINFO_FILENAME);

        // Match thumbnail if exists
        $thumbFile = '';
        foreach ($allowedThumbs as $thumbExt) {
            if (file_exists("$thumbFolder/$name.$thumbExt")) {
                $thumbFile = "$thumbUrlPath/$name.$thumbExt";
                break;
            }
        }

        $videos[] = [
            'src' => "$videoUrlPath/$file",
            'title' => formatTitle($file),
            'thumb' => $thumbFile ?: '../assets/images/default.jpeg'
        ];
    }
}

// Load external videos from playlist.json if it exists
$jsonFile = '../database/video-playlist.json';
if (file_exists($jsonFile)) {
    $externalVideos = json_decode(file_get_contents($jsonFile), true);
    if (is_array($externalVideos)) {
        $videos = array_merge($videos, $externalVideos);
    }
}

// Sort playlist by title
usort($videos, fn($a, $b) => strcmp($a['title'], $b['title']));
?>





    <?php include("../templates/header.php") ?>


<section class="section section-header-offset container">
<video
    id="vp-videoPlayer"
    class="video-js vjs-default-skin vjs-fluid"
    controls
    preload="auto"
    data-setup='{"fluid": true}'>
    <source src="<?= htmlspecialchars($videos[0]['src']) ?>" type="video/mp4">
</video>

<ul id="vp-playlist">
    <?php foreach($videos as $index => $video): ?>
        <li data-src="<?= htmlspecialchars($video['src']) ?>" class="<?= $index === 0 ? 'vp-active' : '' ?>">
            <img src="<?= htmlspecialchars($video['thumb']) ?>" alt="<?= htmlspecialchars($video['title']) ?>">
            <span><?= htmlspecialchars($video['title']) ?></span>
        </li>
    <?php endforeach; ?>
</ul>
  
  
</section>

    <?php include("../templates/footer.php") ?>
    
