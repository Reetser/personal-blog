// Initialize Video.js player
const player = videojs('vp-videoPlayer');
const playlistItems = document.querySelectorAll('#vp-playlist li');

playlistItems.forEach(item => {
    item.addEventListener('click', () => {
        // Remove active class from all
        playlistItems.forEach(i => i.classList.remove('vp-active'));

        // Set active
        item.classList.add('vp-active');

        // Change video source and play
        player.src({ type: 'video/mp4', src: item.dataset.src });
        player.play();
    });
});

// Auto-play next video
player.on('ended', () => {
    let current = document.querySelector('#vp-playlist li.vp-active');
    if (!current) current = playlistItems[0];
    let next = current.nextElementSibling || playlistItems[0];
    next.click();
});