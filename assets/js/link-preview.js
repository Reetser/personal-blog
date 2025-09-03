let currentUrl = null; // Store the URL of the clicked link

document.addEventListener('DOMContentLoaded', () => {
    const card = document.getElementById('previewCard');
    const title = document.getElementById('previewTitle');
    const description = document.getElementById('previewDescription');
    const image = document.getElementById('previewImage');

    const agreeBtn = document.getElementById('agreeBtn');
    const disagreeBtn = document.getElementById('disagreeBtn');

    function positionCard(e) {
        const cardRect = card.getBoundingClientRect();
        let left = e.pageX + 15;
        let top = e.pageY + 15;

        if (left + cardRect.width > window.pageXOffset + window.innerWidth) {
            left = e.pageX - cardRect.width - 15;
        }
        if (top + cardRect.height > window.pageYOffset + window.innerHeight) {
            top = e.pageY - cardRect.height - 15;
        }

        card.style.left = left + 'px';
        card.style.top = top + 'px';
    }

    document.querySelectorAll('a[target="_blank"]').forEach(a => {
        a.addEventListener('click', async (e) => {
            e.preventDefault(); 
            positionCard(e);
            card.style.display = 'flex';
            currentUrl = a.href;

            if (!currentUrl || currentUrl === "#") {
                alert("Invalid link, cannot preview.");
                card.style.display = 'none';
                return;
            }

            try {
                const res = await fetch(`/templates/link-preview.php?url=${encodeURIComponent(currentUrl)}`);
                const data = await res.json();

                if (data.error) {
                    alert(data.error);
                    card.style.display = 'none';
                    return;
                }

                title.textContent = data.title;
                description.textContent = data.description || 'No description available';
                image.style.backgroundImage = data.image 
                    ? `url('${data.image}')` 
                    : `url('https://via.placeholder.com/320x150?text=No+Image')`;

            } catch (err) {
                alert('Error fetching preview');
                card.style.display = 'none';
            }
        });

        a.addEventListener('mousemove', positionCard);
    });

    agreeBtn.addEventListener('click', () => {
        if (currentUrl) {
            window.open(currentUrl, '_blank');
            card.style.display = 'none';
        }
    });

    disagreeBtn.addEventListener('click', () => {
        card.style.display = 'none';
    });

    // Hide preview if clicking outside
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.preview-link') && !e.target.closest('.preview-card')) {
            card.style.display = 'none';
        }
    });
});