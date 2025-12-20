<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<main class="container blog-page">
    <!-- Optional intro block (kept for spacing consistency) -->
    <div style="margin-bottom:40px;"></div>

    <?php if (empty($posts)): ?>
        <p>No insights published yet. Check back soon.</p>
    <?php else: ?>

        <div class="feed-grid">
            <?php foreach ($posts as $post): ?>
                <article class="card">
                    <a href="<?= site_url('blog/' . esc($post['slug'])) ?>">

                        <img
                            src="<?= esc($post['hero_image_url'] ?? base_url('assets/images/placeholder.jpg')) ?>"
                            class="card-img"
                            alt="<?= esc($post['title']) ?>"
                            loading="lazy"
                        >

                        <div style="padding:20px;">
                            <span style="color:var(--primary); font-weight:700; font-size:11px;">
                                <?= esc($post['category'] ?? 'Intelligence') ?>
                            </span>

                            <h2 style="margin:10px 0;">
                                <?= esc($post['title']) ?>
                            </h2>

                            <p style="color:var(--text-muted);">
                                <?= esc($post['summary']) ?>
                            </p>
                        </div>

                    </a>
                </article>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>

</main>

<?= $this->endSection() ?>
