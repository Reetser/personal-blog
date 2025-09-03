  <section class="quick-read section">

        <div class="container">

            <h2 class="title section-title" data-name="Quick read">Quick read</h2>
            <!-- Slider main container -->
            <div class="swiper">
                <!-- Additional required wrapper -->
                <div class="swiper-wrapper">
                    <!-- Slides -->
<?php if (!empty($latestBlogs)): ?>
    <?php foreach ($latestBlogs as $blog): ?>
        <a href="<?= htmlspecialchars($blog['link'] ?? '#') ?>" class="article swiper-slide">
            <img src="<?= htmlspecialchars($blog['image'] ?? './assets/images/default.jpg') ?>" alt="" class="article-image">

            <div class="article-data-container">
                <div class="article-data">
                    <span><?= htmlspecialchars(!empty($blog['date']) ? date("d M Y", strtotime($blog['date'])) : '') ?></span>
                    <span class="article-data-spacer"></span>
                    <span><?= htmlspecialchars($blog['category'] ?? '') ?></span>
                </div>
                <h3 class="title article-title"><?= htmlspecialchars($blog['title'] ?? 'Untitled') ?></h3>
            </div>
        </a>
    <?php endforeach; ?>
<?php else: ?>
    <p>No blogs found.</p>
<?php endif; ?>

                </div>
                <!-- Navigation buttons -->
                <div class="swiper-button-prev swiper-controls"></div>
                <div class="swiper-button-next swiper-controls"></div>
                <!-- Pagination -->
                <div class="swiper-pagination"></div>
            </div>

        </div>

    </section>