<?php $this->extend('layouts/main') ?>
<?php $this->section('title') ?>Create New Video Post<?php $this->endSection() ?>
<?php $this->section('content') ?>
<style>
    /* Reuse most of the existing styles from your blog create page */
    /* Admin Panel Overrides */
    #main-header {
        background: #111827;
        box-shadow: 0 2px 12px rgba(0,0,0,0.2);
    }
    #main-header .header-inner { justify-content: space-between; align-items: center; }
    #main-header nav, .btn-subscribe, .hamburger { display: none !important; }
    #main-header .logo { color: #fff; font-weight: 800; font-size: 20px; }
    footer { display: none !important; }

    /* Quill Editor Styling */
    #editor {
        background: white;
        border-radius: 12px;
        min-height: 480px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    }
    .ql-toolbar {
        background: #f8fafc;
        border: none;
        border-bottom: 1px solid #e2e8f0;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        padding: 12px 16px;
    }
    .ql-container {
        border: none;
        border-bottom-left-radius: 12px;
        border-bottom-right-radius: 12px;
        font-size: 17px;
        line-height: 1.7;
    }
    .ql-editor {
        padding: 24px;
        min-height: 420px;
    }
    .ql-editor.ql-blank::before {
        color: #94a3b8;
        font-style: normal;
    }

    /* Mobile Responsiveness (same as blog create page) */
    @media (max-width: 767px) {
        .admin-container { padding: 0 16px; margin: 32px auto 60px; }
        .admin-header { flex-direction: column; align-items: stretch; gap: 16px; margin-bottom: 32px; }
        .admin-title-group h1 { font-size: 1.8rem; }
        .admin-title-group .admin-subtitle { font-size: 1rem; }
        .btn-create[style*="background:#6b7280"] { align-self: flex-start; padding: 10px 18px !important; font-size: 14px; }
        .admin-form { padding: 24px 20px !important; }
        .form-input { padding: 14px 16px; font-size: 16px !important; }
        textarea.form-input { min-height: 100px; }
        .ql-toolbar { padding: 8px 10px !important; }
        .ql-toolbar button { width: 38px !important; height: 38px !important; margin: 0 2px !important; }
        #hero-preview-container img#hero-preview { max-width: 100% !important; width: 100%; height: auto; max-height: 280px; object-fit: contain; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .btn-create-large { width: 100%; padding: 18px 32px !important; font-size: 16px; justify-content: center; }
        .form-actions { margin-top: 32px; }
    }
</style>

<div class="admin-container">
    <div class="admin-header">
        <div class="admin-title-group">
            <h1>Create New Video Post</h1>
            <p class="admin-subtitle">Add an external video (YouTube, Vimeo, etc.) to your site</p>
        </div>
        <a href="<?= site_url('admin/videos') ?>" class="btn-create" style="background:#6b7280; padding:12px 20px;">
            ← Back to Videos
        </a>
    </div>

    <?php if (session()->has('error')): ?>
        <div class="alert alert-error">✕ <?= esc(session('error')) ?></div>
    <?php endif; ?>

    <form action="<?= site_url('admin/videos/store') ?>" method="post" class="admin-form" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="form-group">
            <label>Title <span class="required">*</span></label>
            <input type="text" name="title" value="<?= esc(old('title')) ?>" required class="form-input" placeholder="Enter a compelling title">
        </div>

        <div class="form-group">
            <label>Subtitle</label>
            <input type="text" name="subtitle" value="<?= esc(old('subtitle')) ?>" class="form-input" placeholder="Optional subtitle">
        </div>

        <div class="form-group">
            <label>Summary / Excerpt</label>
            <textarea name="summary" rows="4" class="form-input"><?= esc(old('summary')) ?></textarea>
        </div>

        <div class="form-group">
            <label>External Video URL <span class="required">*</span></label>
            <input type="url" name="external_url" value="<?= esc(old('external_url')) ?>" required class="form-input" placeholder="https://www.youtube.com/watch?v=... or https://vimeo.com/...">
            <small class="text-muted">Full URL to the video on YouTube, Vimeo, or another platform.</small>
        </div>

        <div class="form-group">
            <label>Hero Image (Thumbnail) <span class="required">(optional)</span></label>
            <div id="hero-preview-container" style="margin-bottom:16px;">
                <img id="hero-preview" src="" style="display:none; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); max-width:100%; height:auto;">
                <p id="no-image-text" style="color:#64748b; font-style:italic; margin:8px 0;">No image selected</p>
            </div>
            <input type="file" name="hero_image_file" id="hero_image_file" accept="image/*" class="form-input">
            <small class="text-muted">JPG, PNG, GIF, WebP. Max 2MB. Recommended: 1280x720 or similar.</small>
        </div>

        <div class="form-group">
            <label>Category</label>
            <input type="text" name="category" value="<?= esc(old('category')) ?>" class="form-input" placeholder="e.g. Tutorials, Reviews">
        </div>

        <div class="form-group">
            <label>Tags (comma separated)</label>
            <input type="text" name="tags" value="<?= esc(old('tags')) ?>" class="form-input" placeholder="tutorial, php, codeigniter">
            <small class="text-muted">Separate tags with commas</small>
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="is_published" class="form-input">
                <option value="0" <?= old('is_published', '0') == '0' ? 'selected' : '' ?>>Draft</option>
                <option value="1" <?= old('is_published') == '1' ? 'selected' : '' ?>>Published</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-create-large">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                    <polyline points="7 3 7 8 15 8"></polyline>
                </svg>
                Create Video Post
            </button>
        </div>
    </form>
</div>

<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Hero image preview (same as blog create page)
        const fileInput = document.getElementById('hero_image_file');
        const preview = document.getElementById('hero-preview');
        const noImageText = document.getElementById('no-image-text');

        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) {
                preview.style.display = 'none';
                noImageText.style.display = 'block';
                return;
            }
            if (!file.type.startsWith('image/')) {
                alert('Please select a valid image file.');
                fileInput.value = '';
                return;
            }
            if (file.size > 2 * 1024 * 1024) {
                alert('Image must be less than 2MB.');
                fileInput.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(event) {
                preview.src = event.target.result;
                preview.style.display = 'block';
                noImageText.style.display = 'none';
            };
            reader.readAsDataURL(file);
        });
    });
</script>
<?php $this->endSection() ?>