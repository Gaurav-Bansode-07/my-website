<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<article class="article-wrapper">

    <!-- FULL WIDTH HERO -->
    <div class="article-hero">

        <header class="article-header">
            <div class="article-inner">
                <div class="article-meta">
                    <?= esc(date('M d, Y', strtotime($post['published_at'] ?? 'now'))) ?>
                    • By <?= esc($post['author_name'] ?? 'PrincipaCore Team') ?>
                </div>

                <h1><?= esc($post['title']) ?></h1>
            </div>
        </header>

        <?php if (!empty($post['hero_image_url'])): ?>
            <img
                src="<?= esc($post['hero_image_url']) ?>"
                class="article-hero-img"
                alt="<?= esc($post['title']) ?>"
            >
        <?php endif; ?>

    </div>

    <!-- ARTICLE CONTENT (NO .container HERE – IMPORTANT) -->
    <div class="article-content">
        <div class="content-rich-text">
            <?= $post['content_html'] ?>
        </div>
    </div>

</article>

<?= $this->endSection() ?>
