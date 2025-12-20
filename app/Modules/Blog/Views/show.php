<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<article class="article-wrapper">

    <div class="article-hero">

        <header class="article-header">
            <div class="article-inner">

                <!-- TITLE -->
                <h1><?= esc($post['title']) ?></h1>

                <!-- META (DATE + AUTHOR) -->
                <div class="article-meta">
                    <?= esc(date('M d, Y', strtotime($post['published_at'] ?? 'now'))) ?>
                    • By <?= esc($post['author_name'] ?? 'PrincipaCore Team') ?>
                </div>

                <!-- SUBTITLE -->
                <?php if (!empty($post['subtitle'])): ?>
                    <p class="article-subtitle">
                        <?= esc($post['subtitle']) ?>
                    </p>
                <?php endif; ?>

            </div>
        </header>

        <!-- HERO IMAGE -->
        <?php if (!empty($post['hero_image_url'])): ?>
            <img
                src="<?= base_url(esc($post['hero_image_url'])) ?>"
                class="article-hero-img"
                alt="<?= esc($post['title']) ?>"
            >
        <?php endif; ?>

    </div>

    <!-- ARTICLE CONTENT -->
    <div class="article-content">
        <div class="content-rich-text">
            <?= $post['content_html'] ?>
        </div>
    </div>

</article>

<?= $this->endSection() ?>
