    <section class="older-posts section">

        <div class="container">

            <h2 class="title section-title" data-name="Older posts">Older posts</h2>

            <div class="older-posts-grid-wrapper d-grid">


<?php if (!empty($latestBlogs)): ?>
    <?php foreach ($latestBlogs as $blog): ?>
        <a href="<?= htmlspecialchars($blog['link'] ?? '#') ?>" class="article d-grid">
            <div class="older-posts-article-image-wrapper">
                <img src="<?= htmlspecialchars($blog['image'] ?? './assets/images/default.jpg') ?>" alt="" class="article-image">
            </div>

            <div class="article-data-container">
                <div class="article-data">
                    <span><?= htmlspecialchars(!empty($blog['date']) ? date("d M Y", strtotime($blog['date'])) : '') ?></span>
                    <span class="article-data-spacer"></span>
                    <span><?= htmlspecialchars($blog['category'] ?? '') ?></span>
                </div>

                <h3 class="title article-title"><?= htmlspecialchars($blog['title'] ?? 'Untitled') ?></h3>
                <p class="article-description"><?= htmlspecialchars($blog['description'] ?? '') ?></p>
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