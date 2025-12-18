<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PrincipaCore | Authority Insights</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url('assets/css/site.css') ?>">

<style>

/* === SCROLL STABILITY FIX (NO SHIFT) === */
html{
    scrollbar-gutter: stable;
}

html, body{
    overflow-y: auto;
}

/* =========================
   MOBILE-FIRST FOUNDATION
========================= */
:root{
    --nav-bg:#1a1a1a;
    --primary:#4f46e5;
    --accent:#f97316;
    --bg-body:#ffffff;
    --bg-alt:#f8fafc;
    --text-main:#0f172a;
    --text-muted:#64748b;
    --border:#e2e8f0;
    --radius:12px;
}

*,
*::before,
*::after{
    box-sizing:border-box;
}

html,body{
    margin:0;
    padding:0;
    width:100%;
    overflow-x:hidden;
}

body{
    font-family:'Inter',sans-serif;
    background:var(--bg-body);
    color:var(--text-main);
    line-height:1.6;
}

img{
    max-width:100%;
    display:block;
}

a{
    color:inherit;
    text-decoration:none;
}

/* =========================
   HEADER
========================= */
header{
    background:var(--nav-bg);
    color:#fff;
    position:sticky;
    top:0;
    z-index:1000;
}

.header-inner{
    display:flex;
    flex-direction:column;
    gap:12px;
    padding:14px 16px 12px;
}

.header-top{
    display:flex;
    align-items:center;
    justify-content:space-between;
}

.logo{
    font-size:20px;
    font-weight:800;
    letter-spacing:.04em;
}

.btn-subscribe{
    background:var(--accent);
    padding:6px 14px;
    border-radius:999px;
    font-weight:700;
    font-size:12px;
}

/* Nav */
nav{
    display:flex;
    gap:10px;
    overflow-x:auto;
    white-space:nowrap;
    -webkit-overflow-scrolling:touch;
}

nav::-webkit-scrollbar{ display:none; }

nav a{
    font-size:12px;
    font-weight:700;
    padding:6px 14px;
    border-radius:999px;
    background:rgba(255,255,255,.08);
    flex-shrink:0;
}

/* =========================
   SOCIAL BAR
========================= */
.social-bar{
    border-bottom:1px solid var(--border);
    padding:12px 16px;
    font-size:12px;
}

.social-inner{
    display:flex;
    flex-direction:column;
    gap:8px;
}

/* =========================
   HERO
========================= */
.hero-grid{
    display:grid;
    grid-template-columns:1fr;
    gap:20px;
    padding:20px 16px;
}

.main-story{
    position:relative;
    height:300px;
    border-radius:var(--radius);
    overflow:hidden;
}

.main-story-content{
    position:absolute;
    inset:0;
    padding:20px;
    display:flex;
    flex-direction:column;
    justify-content:flex-end;
    background:linear-gradient(transparent,rgba(0,0,0,.9));
    color:#fff;
}

.hero-stack{
    display:grid;
    gap:12px;
}

/* =========================
   TAGS
========================= */
.tags-row{
    display:flex;
    gap:12px;
    padding:0 16px 16px;
    overflow-x:auto;
    white-space:nowrap;
    font-size:13px;
    font-weight:700;
    -webkit-overflow-scrolling:touch;
}

.tags-row::-webkit-scrollbar{ display:none; }

/* =========================
   MAIN CONTENT
========================= */
.container{
    display:flex;
    flex-direction:column;
    gap:40px;
    padding:24px 16px;
}

.section-title{
    font-size:22px;
    display:flex;
    align-items:center;
    gap:10px;
}

.section-title::after{
    content:"";
    flex:1;
    height:2px;
    background:var(--border);
}

/* =========================
   VIDEO ROW
========================= */
.video-row{
    display:flex;
    gap:16px;
    overflow-x:auto;
    padding-bottom:16px;
    -webkit-overflow-scrolling:touch;
}

.video-row a{
    min-width:240px;
    flex-shrink:0;
}

/* =========================
   FEED
========================= */
.feed-grid{
    display:grid;
    grid-template-columns:1fr;
    gap:24px;
    width:100%;
}

.feed-grid > *{ min-width:0; }

.card{
    width:100%;
    overflow:hidden;
}

.card-img{
    width:100%;
    height:180px;
    object-fit:cover;
    border-radius:var(--radius);
}

/* =========================
   FOOTER
========================= */
footer{
    background:var(--nav-bg);
    color:#fff;
    padding:40px 16px;
}

/* =========================
   DESKTOP
========================= */
@media (min-width:768px){

    .header-inner,
    .hero-grid,
    .container,
    .social-inner{
        max-width:1300px;
        margin:auto;
    }

    .header-inner{
        flex-direction:row;
        align-items:center;
        justify-content:space-between;
        padding:16px 24px;
    }

    nav{ overflow:visible; }

    nav a{
        background:none;
        padding:0;
        font-size:14px;
    }

    .hero-grid{
        grid-template-columns:2fr 1fr;
    }

    .container{
        flex-direction:row;
    }

    main{
        flex:1;
        min-height:0; /* ✅ FIXED */
    }

    aside{ width:350px; }

    .feed-grid{
        grid-template-columns:repeat(auto-fill,minmax(260px,1fr));
    }

    .main-story{ height:500px; }
}
</style>
</head>

<body>


<?= view('partials/header') ?>

<section class="hero-grid">
    <?php if (!empty($posts)): 
        $hero = $posts[0]; // First post for large slot
    ?>
    <a href="<?= site_url('blog/' . esc($hero['slug'])) ?>" class="main-story">
        <img src="<?= esc($hero['hero_image_url'] ?? 'https://via.placeholder.com/800x500') ?>" alt="">
        <div class="main-story-content">
            <span class="category-tag" style="color: #60a5fa;">FEATURED</span>
            <h1><?= esc($hero['title']) ?></h1>
            <p>By Editor | 7 min read</p>
        </div>
    </a>
    <?php endif; ?>

    <div class="hero-stack">
        <?php for($i=1; $i<=3; $i++): if(isset($posts[$i])): ?>
        <a href="<?= site_url('blog/' . esc($posts[$i]['slug'])) ?>" class="stack-item">
            <img src="<?= esc($posts[$i]['hero_image_url'] ?? '') ?>" class="stack-thumb">
            <div>
                <span class="category-tag">Trending</span>
                <h3><?= esc($posts[$i]['title']) ?></h3>
            </div>
        </a>
        <?php endif; endfor; ?>
    </div>
</section>

<div class="container">
    <main>
        <?php if (!empty($videos)): ?>
        <h2 class="section-title">Latest Videos</h2>
        <div class="video-row">
            <?php foreach ($videos as $video): ?>
            <a href="<?= esc($video['external_url'], 'attr') ?>" target="_blank" style="min-width: 280px;">
                <div style="position: relative; border-radius: 12px; overflow: hidden; height: 160px; background: #000;">
                    <img src="<?= esc($video['hero_image_url'] ?? '') ?>" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.7;">
                    <div style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; color: white; font-size: 30px;">▶</div>
                </div>
                <h3 style="font-size: 15px; margin-top: 10px;"><?= esc($video['title']) ?></h3>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <h2 class="section-title">Main Feed</h2>
        <div class="feed-grid">
            <?php 
            // Skip the first 4 posts as they are in the Hero section
            foreach (array_slice($posts, 4) as $post): 
            ?>
            <article class="card">
                <a href="<?= site_url('blog/' . esc($post['slug'])) ?>">
                    <img src="<?= esc($post['hero_image_url'] ?? '') ?>" class="card-img">
                    <span class="category-tag"><?= esc($post['category'] ?? 'General') ?></span>
                    <h2><?= esc($post['title']) ?></h2>
                    <p><?= esc($post['summary'] ?? '') ?></p>
                    <div style="font-size: 12px; color: var(--text-muted); font-weight: 600;">
                        <?= esc(date('M d, Y', strtotime($post['published_at'] ?? 'now'))) ?> • 5 min read
                    </div>
                </a>
            </article>
            <?php endforeach; ?>
        </div>
    </main>
</div>

<?= view('partials/footer') ?>


</body>
</html>