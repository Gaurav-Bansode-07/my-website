<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Videos<?= $this->endSection() ?>

<?= $this->section('content') ?>

<main class="container blog-page">
    <!-- Consistent spacing -->
    <div style="margin-bottom:40px;"></div>

    <?php if (empty($videos)): ?>
        <p style="text-align:center; font-size:18px; color:#64748b;">No videos published yet. Check back soon.</p>
    <?php else: ?>
        <div class="feed-grid">
            <?php foreach ($videos as $video): ?>
                <article class="card">
                    <a href="<?= esc($video['external_url'], 'attr') ?>" target="_blank" rel="noopener noreferrer">
                        <!-- Thumbnail with play icon overlay -->
                        <div style="position:relative; border-radius:var(--radius); overflow:hidden;">
                            <img
                                src="<?= esc($video['hero_image_url'] ?? base_url('assets/images/placeholder.jpg')) ?>"
                                class="card-img"
                                alt="<?= esc($video['title']) ?>"
                                loading="lazy"
                            >
                            <div style="
                                position:absolute;
                                inset:0;
                                display:flex;
                                align-items:center;
                                justify-content:center;
                                background:rgba(0,0,0,0.4);
                                transition:background 0.3s ease;
                            " onmouseover="this.style.background='rgba(0,0,0,0.6)'" onmouseout="this.style.background='rgba(0,0,0,0.4)'">
                                <svg width="80" height="80" viewBox="0 0 24 24" fill="#ffffff" stroke="#000000" stroke-width="1">
                                    <circle cx="12" cy="12" r="11" fill="rgba(0,0,0,0.5)" />
                                    <path d="M9 8l7 4-7 4V8z" fill="#ffffff"/>
                                </svg>
                            </div>
                        </div>

                        <div style="padding:20px;">
                            <span style="color:var(--primary); font-weight:700; font-size:11px; text-transform:uppercase;">
                                <?= esc($video['category'] ?? 'Video') ?>
                            </span>
                            <h2 style="margin:12px 0 8px; font-size:1.25rem;">
                                <?= esc($video['title']) ?>
                            </h2>
                            <?php if (!empty($video['summary'])): ?>
                                <p style="color:var(--text-muted); margin:0; line-height:1.5;">
                                    <?= esc($video['summary']) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<?= $this->endSection() ?>