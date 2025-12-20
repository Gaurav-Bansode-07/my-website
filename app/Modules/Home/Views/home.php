<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- =========================
     HERO
========================= -->
<section class="hero-grid">
    <?php if (!empty($posts)):
        $hero = $posts[0];
    ?>
        <a href="<?= site_url('blog/' . esc($hero['slug'])) ?>" class="main-story">
            <img
                src="<?= esc($hero['hero_image_url'] ?? 'https://via.placeholder.com/1200x800') ?>"
                alt="<?= esc($hero['title']) ?>"
                loading="lazy"
            >
            <div class="main-story-content">
                <span class="category-tag" style="background:#60a5fa;">FEATURED</span>
                <h1><?= esc($hero['title']) ?></h1>
                <p>
                    By <?= esc($hero['author_name'] ?? 'Editor') ?>
                    • <?= esc(date('M d, Y', strtotime($hero['published_at'] ?? 'now'))) ?>
                </p>
            </div>
        </a>
    <?php endif; ?>

    <div class="hero-stack">
        <?php for ($i = 1; $i <= 3; $i++): if (isset($posts[$i])): ?>
            <a href="<?= site_url('blog/' . esc($posts[$i]['slug'])) ?>" class="stack-item">
                <img
                    src="<?= esc($posts[$i]['hero_image_url'] ?? 'https://via.placeholder.com/300x200') ?>"
                    alt="<?= esc($posts[$i]['title']) ?>"
                    class="stack-thumb"
                    loading="lazy"
                >
                <div>
                    <span class="category-tag">Trending</span>
                    <h3><?= esc($posts[$i]['title']) ?></h3>
                </div>
            </a>
        <?php endif; endfor; ?>
    </div>
</section>

<!-- =========================
     MAIN LAYOUT
========================= -->
<div class="container">

    <!-- =========================
         MAIN COLUMN
    ========================= -->
    <main>

        <!-- ===== VIDEOS (RESTORED) ===== -->
        <?php if (!empty($videos)): ?>
            <h2 class="section-title">Latest Videos</h2>

            <div class="video-row">
                <?php foreach ($videos as $video): ?>
                    <a href="<?= esc($video['external_url'], 'attr') ?>" target="_blank" rel="noopener">
                        <div style="position:relative;border-radius:12px;overflow:hidden;height:160px;background:#000;">
                            <img
                                src="<?= esc($video['hero_image_url'] ?? 'https://via.placeholder.com/400x225') ?>"
                                alt="<?= esc($video['title']) ?>"
                                style="width:100%;height:100%;object-fit:cover;opacity:.7;"
                                loading="lazy"
                            >
                            <div style="
                                position:absolute;
                                inset:0;
                                display:flex;
                                align-items:center;
                                justify-content:center;
                                color:#fff;
                                font-size:48px;
                                pointer-events:none;
                            ">▶</div>
                        </div>

                        <h3 style="font-size:15px;margin-top:10px;">
                            <?= esc($video['title']) ?>
                        </h3>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- ===== MAIN FEED ===== -->
        <h2 class="section-title">Main Feed</h2>

        <div class="feed-grid">
            <?php foreach (array_slice($posts, 4) as $post): ?>
                <article class="card">
                    <a href="<?= site_url('blog/' . esc($post['slug'])) ?>">
                        <img
                            src="<?= esc($post['hero_image_url'] ?? 'https://via.placeholder.com/600x400') ?>"
                            alt="<?= esc($post['title']) ?>"
                            class="card-img"
                            loading="lazy"
                        >

                        <span class="category-tag">
                            <?= esc($post['category'] ?? 'General') ?>
                        </span>

                        <h2><?= esc($post['title']) ?></h2>

                        <p><?= esc($post['summary'] ?? '') ?></p>

                        <div style="font-size:12px;color:var(--text-muted);font-weight:600;">
                            <?= esc(date('M d, Y', strtotime($post['published_at'] ?? 'now'))) ?>
                            • 5 min read
                        </div>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>

    </main>

    <!-- =========================
         SIDEBAR (DESKTOP)
    ========================= -->
    <aside>

        <h2 class="section-title">Subscribe for Insights</h2>

        <div class="newsletter-box" style="background:var(--bg-alt);padding:32px;border-radius:var(--radius);">
            <h2>Get the latest insights delivered</h2>
            <p style="color:var(--text-muted);margin:12px 0;">
                Exclusive analysis and updates straight to your inbox.
            </p>

            <form>
                <input type="email" placeholder="Your email address" required>
                <button type="submit">Subscribe</button>
            </form>
        </div>

        <h2 class="section-title" style="margin-top:48px;">Popular This Week</h2>

        <div class="sidebar-card">
            <img src="https://via.placeholder.com/400x250" alt="Popular post" loading="lazy">
            <h3>Breaking Down the Latest Policy Changes</h3>
        </div>

        <div class="sidebar-card">
            <img src="https://via.placeholder.com/400x250" alt="Popular post" loading="lazy">
            <h3>The Future of AI Regulation in 2026</h3>
        </div>

    </aside>

</div>

<?= $this->endSection() ?>
