<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= esc($title ?? 'PrincipaCore') ?></title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">

    <!-- Site CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/site.css?v=1.2') ?>">
</head>
<body>

<?= view('partials/header') ?>

<?= $this->renderSection('content') ?>

<?= view('partials/footer') ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const hamburger = document.querySelector('.hamburger');
    const nav = document.querySelector('#main-header nav');

    if (!hamburger || !nav) return;

    hamburger.addEventListener('click', () => {
        const isOpen = nav.classList.toggle('is-open');
        hamburger.setAttribute('aria-expanded', isOpen);
    });
});
</script>


</body>
</html>
