<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blog | PrincipaCore</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: Inter, sans-serif;
            background: #f8fafc;
            margin: 0;
            color: #0f172a;
        }
        .container {
            max-width: 1100px;
            margin: auto;
            padding: 60px 24px;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 24px;
        }
        .card {
            background: #fff;
            border-radius: 14px;
            padding: 24px;
            box-shadow: 0 10px 30px rgba(0,0,0,.06);
        }
        .card h2 {
            font-family: "Plus Jakarta Sans", sans-serif;
            font-size: 20px;
            margin: 0 0 10px;
        }
        .card p {
            color: #475569;
        }
        .card a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Blog</h1>

    <?php if (empty($posts)): ?>
        <p>No posts published yet.</p>
    <?php else: ?>
        <div class="grid">
            <?php foreach ($posts as $post): ?>
                <?php if (empty($post['slug'])) continue; ?>

                <div class="card">

                    <?php if (! empty($post['hero_image_url'])): ?>
                        <div style="
                            height:160px;
                            border-radius:10px;
                            background-image:url('<?= esc($post['hero_image_url']) ?>');
                            background-size:cover;
                            background-position:center;
                            margin-bottom:16px;
                        "></div>
                    <?php endif; ?>

                    <h2><?= esc($post['title']) ?></h2>
                    <p><?= esc($post['summary'] ?? '') ?></p>

                    <a href="<?= site_url('blog/' . $post['slug']) ?>">
                        Read more →
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
