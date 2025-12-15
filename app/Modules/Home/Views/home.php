<?php // dd($posts); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | PrincipaCore</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #4f46e5;
            --secondary: #0ea5e9;
            --bg-body: #f8fafc;
            --bg-card: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --radius: 12px;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.1);
            --shadow-hover: 0 10px 15px rgba(0,0,0,0.1);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-body);
            color: var(--text-main);
            overflow-x: hidden;
        }

        h1, h2, h3 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 800;
        }

        header {
            position: sticky;
            top: 0;
            background: #fff;
            border-bottom: 1px solid var(--border);
            z-index: 100;
        }

        .header-inner {
            max-width: 1200px;
            margin: auto;
            padding: 16px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 26px;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        nav { display: flex; gap: 32px; }
        nav a { font-weight: 600; color: var(--text-muted); }

        .container {
            max-width: 1200px;
            margin: auto;
            padding: 40px 24px;
            display: grid;
            grid-template-columns: 1fr;
            gap: 48px 32px;
        }

        .hero {
            text-align: center;
            padding: 50px 0 70px;
            grid-column: 1 / -1;
        }

        .hero h1 { font-size: 52px; margin-bottom: 16px; }
        .hero p { color: var(--text-muted); font-size: 18px; }

        .section-title {
            font-size: 26px;
            margin: 40px 0 20px;
            color: var(--primary);
            border-bottom: 2px solid var(--border);
            padding-bottom: 8px;
            grid-column: 1 / -1;
        }

        .blog-grid {
            display: flex;
            gap: 24px;
            overflow-x: auto;
            padding-bottom: 16px;
            scroll-snap-type: x mandatory;
            grid-column: 1 / -1;
        }

        .card {
            min-width: 320px;
            max-width: 320px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            scroll-snap-align: start;
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-hover);
        }

        .card-image,
        .card-video {
            height: 180px;
            background: #000;
        }

        .card-image {
            background-size: cover;
            background-position: center;
        }

        .card-video video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .card-content {
            padding: 24px;
            flex-grow: 1;
        }

        .badge {
            font-size: 13px;
            font-weight: 700;
            padding: 5px 14px;
            border-radius: 4px;
            background: #eef2ff;
            color: var(--primary);
            margin-bottom: 12px;
            display: inline-block;
        }

        .card-footer {
            font-size: 14px;
            color: var(--text-muted);
            border-top: 1px solid var(--border);
            padding-top: 12px;
            margin-top: auto;
        }

        .about-section {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 40px;
        }

        .core-value-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 24px;
        }

        .value-card {
            background: var(--bg-body);
            padding: 24px;
            border-radius: var(--radius);
            border: 1px solid var(--border);
        }

        .sidebar-widget {
            padding: 28px;
            border-radius: var(--radius);
            border: 1px solid var(--border);
        }

        @media (min-width: 901px) {
            .container {
                grid-template-columns: 1fr 300px;
            }

            main { grid-column: 1 / 2; }
            aside { grid-column: 2 / 3; }
        }
    </style>
</head>
<body>

<header>
    <div class="header-inner">
        <div class="logo">PrincipaCore</div>
        <nav>
            <a href="#">Tools</a>
            <a href="#">Finance</a>
            <a href="#">Real Estate</a>
            <a href="#">HRM</a>
            <a href="#">Contact</a>
        </nav>
    </div>
</header>

<div class="container">

    <section class="hero">
        <h1>Insights that Matter</h1>
        <p>Research-driven articles across finance, tools, and business.</p>
    </section>

    <!-- 🔥 VIDEOS ROW -->
    <h2 class="section-title">Featured Videos</h2>
    <div class="blog-grid">
        <?php for ($i = 1; $i <= 5; $i++): ?>
            <article class="card">
                <div class="card-video">
                    <video controls muted playsinline preload="metadata">
                        <source src="<?= base_url('uploads/videos/sample.mp4') ?>" type="video/mp4">
                    </video>
                </div>
                <div class="card-content">
                    <span class="badge">Video</span>
                    <h2>PrincipaCore Overview <?= $i ?></h2>
                    <div class="card-footer">2 min watch</div>
                </div>
            </article>
        <?php endfor; ?>
    </div>

    <!-- 📰 BLOG POSTS -->
    <h2 class="section-title">Latest Articles</h2>
    <div class="blog-grid">
        <?php foreach ($posts as $post): ?>
            <a href="<?= site_url('blog/' . esc($post['slug'])) ?>">
                <article class="card">
                    <div class="card-image" style="background-image:url('<?= esc($post['hero_image_url'] ?? '') ?>');"></div>
                    <div class="card-content">
                        <span class="badge"><?= esc($post['category'] ?? 'General') ?></span>
                        <h2><?= esc($post['title']) ?></h2>
                        <p><?= esc($post['summary'] ?? '') ?></p>
                        <div class="card-footer">
                            <?= esc(date('M d, Y', strtotime($post['published_at'] ?? 'now'))) ?>
                        </div>
                    </div>
                </article>
            </a>
        <?php endforeach; ?>
    </div>

    <main>
        <section class="about-section">
            <h2>Our Core Philosophy</h2>
            <p>PrincipaCore delivers decision-ready insights across finance, technology, real estate, and operations.</p>

            <div class="core-value-grid">
                <div class="value-card">🔍 Independent Research</div>
                <div class="value-card">✂️ Zero Fluff</div>
                <div class="value-card">📚 Built for Reference</div>
                <div class="value-card">🏗️ Production-Grade Thinking</div>
            </div>
        </section>
    </main>

    <aside>
        <div class="sidebar-widget">
            <h3>Trending</h3>
            <p>Coming soon…</p>
        </div>
    </aside>

</div>

</body>
</html>
