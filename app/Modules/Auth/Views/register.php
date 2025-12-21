<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register – MyWebsite</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/site.css') ?>">
    <style>
        body {
            background: linear-gradient(135deg, #f3f4f6 0%, #e0e7ff 100%);
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        .register-container {
            background: white;
            padding: 48px;
            border-radius: 24px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.12);
            width: 100%;
            max-width: 440px;
            text-align: center;
        }
        .register-header h2 {
            font-size: 32px;
            font-weight: 800;
            color: #111827;
            margin-bottom: 12px;
        }
        .register-header p {
            color: #64748b;
            font-size: 16px;
        }
        .form-group {
            margin-bottom: 24px;
            text-align: left;
        }
        label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 10px;
            display: block;
        }
        input {
            width: 100%;
            padding: 16px 20px;
            border: 1px solid #d1d5db;
            border-radius: 16px;
            font-size: 16px;
            background: #f9fafb;
        }
        input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(79,70,229,0.15);
            background: white;
        }
        button {
            width: 100%;
            padding: 18px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 16px;
            font-size: 17px;
            font-weight: 700;
            box-shadow: 0 8px 25px rgba(79,70,229,0.25);
        }
        button:hover {
            background: #4338ca;
            transform: translateY(-3px);
        }
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            padding: 16px;
            border-radius: 16px;
            margin-bottom: 28px;
            border-left: 5px solid #ef4444;
        }
        .links {
            margin-top: 32px;
        }
        .links a {
            color: var(--primary);
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <h2>Create Account</h2>
            <p>Join your blog community</p>
        </div>

        <?php if (session('error')): ?>
            <div class="alert-error"><?= esc(session('error')) ?></div>
        <?php elseif (session('errors') !== null): ?>
            <div class="alert-error">
                <?php if (is_array(session('errors'))): ?>
                    <?php foreach (session('errors') as $error): ?>
                        <?= esc($error) ?><br>
                    <?php endforeach ?>
                <?php else: ?>
                    <?= esc(session('errors')) ?>
                <?php endif ?>
            </div>
        <?php endif ?>

        <form action="<?= url_to('register') ?>" method="post">
            <?= csrf_field() ?>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" value="<?= old('email') ?>" required placeholder="you@example.com">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Create a strong password">
            </div>

            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="password_confirm" required placeholder="Repeat your password">
            </div>

            <button type="submit">Create Account</button>
        </form>

        <div class="links">
            <a href="<?= site_url('login') ?>">Already have an account? Login</a><br>
            <a href="<?= site_url('/') ?>">← Back to Home</a>
        </div>
    </div>
</body>
</html>