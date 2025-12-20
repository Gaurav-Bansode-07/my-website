<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PrincipaCore | Authority Insights</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url('assets/css/site.css') ?>">
<style>
/* =========================
   RESET & GLOBAL
========================= */
*,
*::before,
*::after{
    box-sizing:border-box;
}
html{
    scrollbar-gutter: stable;
}
html,body{
    margin:0;
    padding:0;
    width:100%;
    overflow-x:hidden;
}
body{
    font-family:'Inter',sans-serif;
    background:#ffffff;
    color:#0f172a;
    line-height:1.6;
    font-size:clamp(14px,1vw,16px);
}
img{
    max-width:100%;
    display:block;
}
a{
    color:inherit;
    text-decoration:none;
    transition: opacity .3s ease;
}
a:hover{
    opacity: .9;
}

/* =========================
   VARIABLES
========================= */
:root{
    --nav-bg:#1a1a1a;
    --primary:#4f46e5;
    --accent:#f97316;
    --bg-alt:#f8fafc;
    --text-muted:#64748b;
    --border:#e2e8f0;
    --radius:12px;
}

/* =========================
   HEADER (FULL WIDTH BG)
========================= */
header{
    background:var(--nav-bg);
    color:#fff;
    position:sticky;
    top:0;
    z-index:1000;
    width:100%;
}
.header-inner{
    display:flex;
    flex-direction:column;
    gap:12px;
    padding:14px 16px;
    max-width:min(1300px,92vw);
    margin-inline:auto;
}
.header-top{
    display:flex;
    justify-content:space-between;
    align-items:center;
}
.logo{
    font-weight:800;
    font-size:clamp(18px,2vw,22px);
    letter-spacing:.04em;
}
.btn-subscribe{
    background:var(--accent);
    padding:6px 14px;
    border-radius:999px;
    font-weight:700;
    font-size:12px;
    transition: transform .2s;
}
.btn-subscribe:hover{
    transform:scale(1.05);
}

/* =========================
   NAV
========================= */
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
    transition: background .3s;
}
nav a:hover{
    background:rgba(255,255,255,.2);
}

/* =========================
   HAMBURGER (MOBILE ONLY)
========================= */
.hamburger{
    display:flex;
    flex-direction:column;
    justify-content:center;
    gap:5px;
    width:32px;
    height:32px;
    background:none;
    border:none;
    cursor:pointer;
}

.hamburger span{
    height:2px;
    width:100%;
    background:#fff;
    border-radius:2px;
    transition:transform .3s ease, opacity .3s ease;
}

/* Hide nav by default on mobile */
@media (max-width:767px){
    nav{
        display:none;
        flex-direction:column;
        gap:12px;
        padding-top:8px;
    }

    nav.is-open{
        display:flex;
    }

    .btn-subscribe{
        display:none;
    }
}

/* Desktop reset */
@media (min-width:768px){
    .hamburger{
        display:none;
    }
}


/* =========================
   HERO
========================= */
.hero-grid{
    display:grid;
    grid-template-columns:1fr;
    gap:20px;
    padding:20px 16px;
    max-width:min(1300px,92vw);
    margin-inline:auto;
}
.main-story{
    position:relative;
    height:clamp(260px,60vw,380px);
    border-radius:var(--radius);
    overflow:hidden;
    transition: transform .4s ease, box-shadow .4s ease;
}
.main-story:hover{
    transform: translateY(-8px);
    box-shadow: 0 24px 48px rgba(0,0,0,.15);
}
.main-story img{
    width:100%;
    height:100%;
    object-fit:cover;
    transition: transform .6s ease;
}
.main-story:hover img{
    transform: scale(1.05);
}
.main-story-content{
    position:absolute;
    inset:0;
    padding:20px;
    display:flex;
    flex-direction:column;
    justify-content:flex-end;
    background:linear-gradient(transparent, rgba(0,0,0,.85));
    color:#fff;
}
.main-story-content h1{
    font-size:clamp(26px,4vw,44px);
    margin:6px 0;
    font-weight:700;
}

/* Category Tags */
.category-tag{
    display:inline-block;
    font-size:11px;
    font-weight:700;
    text-transform:uppercase;
    letter-spacing:.06em;
    background:rgba(0,0,0,.5);
    color:#fff;
    padding:4px 10px;
    border-radius:4px;
    backdrop-filter:blur(6px);
    margin-bottom:8px;
}

/* Hero Stack */
.hero-stack{
    display:grid;
    gap:16px;
}
.stack-item{
    display:flex;
    gap:12px;
    align-items:center;
    padding:8px;
    border-radius:var(--radius);
    transition: transform .3s ease, box-shadow .3s ease;
}
.stack-item:hover{
    transform:translateY(-4px);
    box-shadow:0 12px 24px rgba(0,0,0,.08);
}
.stack-thumb{
    width:90px;
    height:70px;
    object-fit:cover;
    border-radius:10px;
    flex-shrink:0;
}

/* =========================
   TAGS ROW
========================= */
.tags-row{
    display:flex;
    gap:12px;
    padding:0 16px 20px;
    overflow-x:auto;
    white-space:nowrap;
    font-size:13px;
    font-weight:700;
}
.tags-row::-webkit-scrollbar{ display:none; }
.tags-row a{
    background:var(--bg-alt);
    color:var(--primary);
    padding:8px 18px;
    border-radius:999px;
    font-weight:600;
    transition:all .3s ease;
}
.tags-row a:hover{
    background:var(--primary);
    color:#fff;
}

/* =========================
   MAIN CONTAINER
========================= */
.container{
    display:flex;
    flex-direction:column;
    gap:40px;
    padding:24px 16px;
    max-width:min(1300px,92vw);
    margin-inline:auto;
}

/* =========================
   TITLES
========================= */
.section-title{
    font-size:clamp(18px,2.4vw,24px);
    display:flex;
    align-items:center;
    gap:10px;
    font-weight:700;
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
}
.video-row a{
    min-width:280px;
    flex-shrink:0;
    transition: transform .3s;
}
.video-row a:hover{
    transform:translateY(-6px);
}

/* =========================
   FEED GRID & CARDS
========================= */
.feed-grid{
    display:grid;
    grid-template-columns:1fr;
    gap:24px;
}
.card{
    width:100%;
    border-radius:var(--radius);
    overflow:hidden;
    transition: transform .4s ease, box-shadow .4s ease;
}
.card:hover{
    transform:translateY(-8px);
    box-shadow:0 20px 40px rgba(0,0,0,.1);
}
.card a{
    display:block;
}
.card-img{
    width:100%;
    height:clamp(160px,30vw,200px);
    object-fit:cover;
}
.card h2{
    font-size:clamp(16px,2vw,20px);
    margin:12px 0 8px;
    font-weight:600;
}
.card p{
    color:var(--text-muted);
    margin-bottom:8px;
}

/* =========================
   SIDEBAR (ASIDE)
========================= */
aside .section-title{
    margin-bottom:20px;
}
.sidebar-card{
    background:var(--bg-alt);
    border-radius:var(--radius);
    overflow:hidden;
    margin-bottom:20px;
}
.sidebar-card img{
    width:100%;
    height:140px;
    object-fit:cover;
}
.sidebar-card h3{
    font-size:16px;
    padding:12px;
    margin:0;
}

/* =========================
   FOOTER
========================= */
footer{
    background:var(--nav-bg);
    color:#fff;
    width:100%;
    margin-top:80px;
}
.footer-inner{
    max-width:min(1300px,92vw);
    margin-inline:auto;
    padding:40px 16px;
    display:flex;
    flex-direction:column;
    align-items:center;
    text-align:center;
    gap:24px;
}
.newsletter-box{
    max-width:520px;
    width:100%;
}
.newsletter-box h2{
    font-size:clamp(20px,2.6vw,28px);
    margin-bottom:12px;
}
.newsletter-box form{
    display:flex;
    flex-direction:column;
    gap:12px;
}
.newsletter-box input{
    width:100%;
    padding:14px 16px;
    border-radius:999px;
    border:none;
    outline:none;
    font-size:14px;
}
.newsletter-box button{
    background:var(--accent);
    color:#fff;
    border:none;
    padding:14px 24px;
    border-radius:999px;
    font-weight:700;
    cursor:pointer;
    transition:transform .2s;
}
.newsletter-box button:hover{
    transform:scale(1.05);
}

/* =========================
   DESKTOP
========================= */
@media (min-width:768px){
    .header-inner{
        flex-direction:row;
        align-items:center;
        justify-content:space-between;
        padding:16px 24px;
    }
    nav{
        overflow:visible;
    }
    nav a{
        background:none;
        padding:0 16px;
        font-size:14px;
    }
    .hero-grid{
        grid-template-columns:2fr 1fr;
    }
    .main-story{
        height:clamp(360px,45vw,560px);
    }
    .main-story-content h1{
        font-size:clamp(32px,5vw,52px);
    }
    .container{
        flex-direction:row;
        align-items:flex-start;
        gap:48px;
    }
    main{
        flex:1;
        min-width:0;
    }
    aside{
        width:min(360px,30vw);
        flex-shrink:0;
        position:sticky;
        top:100px;
    }
    .feed-grid{
        grid-template-columns:repeat(auto-fill,minmax(280px,1fr));
        gap:32px;
    }
    .footer-inner{
        padding:64px 24px;
    }
    .newsletter-box form{
        flex-direction:row;
        justify-content:center;
        gap:12px;
    }
    .newsletter-box input{
        max-width:320px;
    }
}
 
 
 /* Fix social bar wrapping on mobile only */
@media (max-width: 767px) {
    .social-inner {
        flex-direction: column;
        gap: 6px;
        text-align: center;
    }

    .logos {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 6px 10px;
        line-height: 1.4;
    }

    .logos span {
        white-space: normal;
    }
}


</style>
</head>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const hamburger = document.querySelector('.hamburger');
    const nav = document.querySelector('header nav');

    if (!hamburger || !nav) return;

    hamburger.addEventListener('click', () => {
        const isOpen = nav.classList.toggle('is-open');
        hamburger.setAttribute('aria-expanded', isOpen);
    });
});
</script>

<body>
<?= view('partials/header') ?>

<section class="hero-grid">
    <?php if (!empty($posts)):
        $hero = $posts[0];
    ?>
    <a href="<?= site_url('blog/' . esc($hero['slug'])) ?>" class="main-story">
        <img src="<?= esc($hero['hero_image_url'] ?? 'https://via.placeholder.com/1200x800') ?>" alt="<?= esc($hero['title']) ?>" loading="lazy">
        <div class="main-story-content">
            <span class="category-tag" style="background:#60a5fa;">FEATURED</span>
            <h1><?= esc($hero['title']) ?></h1>
            <p>By Editor | 7 min read</p>
        </div>
    </a>
    <?php endif; ?>

    <div class="hero-stack">
        <?php for($i=1; $i<=3; $i++): if(isset($posts[$i])): ?>
        <a href="<?= site_url('blog/' . esc($posts[$i]['slug'])) ?>" class="stack-item">
            <img src="<?= esc($posts[$i]['hero_image_url'] ?? 'https://via.placeholder.com/300x200') ?>" alt="<?= esc($posts[$i]['title']) ?>" class="stack-thumb" loading="lazy">
            <div>
                <span class="category-tag">Trending</span>
                <h3><?= esc($posts[$i]['title']) ?></h3>
            </div>
        </a>
        <?php endif; endfor; ?>
    </div>
</section>

<!-- Category Tags Row -->
<div class="tags-row">
    <a href="#">Politics</a>
    <a href="#">Economy</a>
    <a href="#">Technology</a>
    <a href="#">Health</a>
    <a href="#">Science</a>
    <a href="#">Culture</a>
    <a href="#">Opinion</a>
</div>

<div class="container">
    <main>
        <?php if (!empty($videos)): ?>
        <h2 class="section-title">Latest Videos</h2>
        <div class="video-row">
            <?php foreach ($videos as $video): ?>
            <a href="<?= esc($video['external_url'], 'attr') ?>" target="_blank">
                <div style="position:relative;border-radius:12px;overflow:hidden;height:160px;background:#000;">
                    <img src="<?= esc($video['hero_image_url'] ?? 'https://via.placeholder.com/400x225') ?>" alt="<?= esc($video['title']) ?>" style="width:100%;height:100%;object-fit:cover;opacity:0.7;" loading="lazy">
                    <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;color:white;font-size:48px;pointer-events:none;">▶</div>
                </div>
                <h3 style="font-size:15px;margin-top:10px;"><?= esc($video['title']) ?></h3>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <h2 class="section-title">Main Feed</h2>
        <div class="feed-grid">
            <?php foreach (array_slice($posts, 4) as $post): ?>
            <article class="card">
                <a href="<?= site_url('blog/' . esc($post['slug'])) ?>">
                    <img src="<?= esc($post['hero_image_url'] ?? 'https://via.placeholder.com/600x400') ?>" alt="<?= esc($post['title']) ?>" class="card-img" loading="lazy">
                    <span class="category-tag"><?= esc($post['category'] ?? 'General') ?></span>
                    <h2><?= esc($post['title']) ?></h2>
                    <p><?= esc($post['summary'] ?? '') ?></p>
                    <div style="font-size:12px;color:var(--text-muted);font-weight:600;">
                        <?= esc(date('M d, Y', strtotime($post['published_at'] ?? 'now'))) ?> • 5 min read
                    </div>
                </a>
            </article>
            <?php endforeach; ?>
        </div>
    </main>

    <!-- Sidebar (visible on desktop) -->
    <aside>
        <h2 class="section-title">Subscribe for Insights</h2>
        <div class="newsletter-box" style="background:var(--bg-alt);padding:32px;border-radius:var(--radius);">
            <h2>Get the latest insights delivered</h2>
            <p style="color:var(--text-muted);margin:12px 0;">Exclusive analysis and updates straight to your inbox.</p>
            <form>
                <input type="email" placeholder="Your email address" required>
                <button type="submit">Subscribe</button>
            </form>
        </div>

        <h2 class="section-title" style="margin-top:48px;">Popular This Week</h2>
        <!-- Example popular posts – replace with dynamic if needed -->
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

<?= view('partials/footer') ?>
</body>
</html>