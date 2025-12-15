<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= esc($post['meta_title'] ?? $post['title']) ?> | PrincipaCore</title>

    <meta name="description" content="<?= esc($post['meta_description'] ?? $post['summary']) ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: Inter, sans-serif;
            background: #f8fafc;
            color: #0f172a;
            margin: 0;
        }
        .container {
            max-width: 900px;
            margin: auto;
            padding: 60px 24px;
        }
        h1 {
            font-family: "Plus Jakarta Sans", sans-serif;
            font-size: 40px;
            margin-bottom: 16px;
        }
        .meta {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 32px;
        }
        .hero {
            width: 100%;
            height: 380px;
            background-size: cover;
            background-position: center;
            border-radius: 14px;
            margin-bottom: 40px;
        }
        article {
            font-size: 18px;
            line-height: 1.75;
        }
    </style>
</head>

<body>

<div class="container">

    <?php if (! empty($post['hero_image_url'])): ?>
        <div class="hero"
             style="background-image:url('<?= esc($post['hero_image_url']) ?>')">
        </div>
    <?php endif; ?>

    <h1><?= esc($post['title']) ?></h1>

    <div class="meta">
        <?= date('M d, Y', strtotime($post['published_at'])) ?>
        • <?= esc($post['author_name'] ?? 'PrincipaCore') ?>
    </div>

    <article>
        <?= $post['content_html'] ?>
    </article>

</div>

</body>
</html>
