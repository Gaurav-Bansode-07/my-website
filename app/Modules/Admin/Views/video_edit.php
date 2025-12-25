<?php $this->extend('layouts/main') ?>
<?php $this->section('title') ?>Edit Video Post<?php $this->endSection() ?>
<?php $this->section('content') ?>

<style>
    #main-header, footer { display: none !important; }
</style>

<div class="admin-container">
    <div class="admin-header">
        <div class="admin-title-group">
            <h1>Edit Video Post</h1>
            <p class="admin-subtitle">Update video details and thumbnail</p>
        </div>
        <a href="<?= site_url('admin/videos') ?>" class="btn-create" style="background:#6b7280; padding:12px 20px;">
            ← Back to Videos
        </a>
    </div>

    <?php if (session()->has('error')): ?>
        <div class="alert alert-error">✕ <?= esc(session('error')) ?></div>
    <?php endif; ?>

    <form action="<?= site_url('admin/videos/update/' . $video['id']) ?>" method="post" class="admin-form" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="form-group">
            <label>Title <span class="required">*</span></label>
            <input type="text" name="title" value="<?= esc(old('title', $video['title'])) ?>" required class="form-input">
        </div>

        <div class="form-group">
            <label>Subtitle</label>
            <input type="text" name="subtitle" value="<?= esc(old('subtitle', $video['subtitle'])) ?>" class="form-input">
        </div>

        <div class="form-group">
            <label>Summary / Excerpt</label>
            <textarea name="summary" rows="4" class="form-input"><?= esc(old('summary', $video['summary'])) ?></textarea>
        </div>

        <div class="form-group">
            <label>External Video URL <span class="required">*</span></label>
            <input type="url" name="external_url" value="<?= esc(old('external_url', $video['external_url'])) ?>" required class="form-input">
        </div>

        <div class="form-group">
            <label>Hero Image (Thumbnail)</label>
            <div id="hero-preview-container" style="margin-bottom:16px;">
                <?php if (!empty($video['hero_image_url'])): ?>
                    <img id="hero-preview" src="<?= esc($video['hero_image_url']) ?>" style="display:block; max-width:100%; height:auto; max-height:300px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
                    <p style="color:#64748b; font-style:italic; margin:8px 0;">Current thumbnail</p>
                <?php else: ?>
                    <img id="hero-preview" src="" style="display:none;">
                    <p id="no-image-text" style="color:#64748b; font-style:italic;">No image selected</p>
                <?php endif; ?>
            </div>
            <input type="file" name="hero_image_file" id="hero_image_file" accept="image/*" class="form-input">
            <small class="text-muted">Leave empty to keep current image. Max 2MB.</small>
        </div>

        <div class="form-group">
            <label>Category</label>
            <input type="text" name="category" value="<?= esc(old('category', $video['category'])) ?>" class="form-input">
        </div>

        <div class="form-group">
            <label>Tags (comma separated)</label>
            <input type="text" name="tags" value="<?= esc(old('tags', is_array($video['tags']) ? implode(', ', $video['tags']) : $video['tags'])) ?>" class="form-input">
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="is_published" class="form-input">
                <option value="0" <?= old('is_published', $video['is_published']) == 0 ? 'selected' : '' ?>>Draft</option>
                <option value="1" <?= old('is_published', $video['is_published']) == 1 ? 'selected' : '' ?>>Published</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-create-large">
                Update Video Post
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fileInput = document.getElementById('hero_image_file');
        const preview = document.getElementById('hero-preview');
        const noImageText = document.getElementById('no-image-text');

        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            if (!file.type.startsWith('image/')) {
                alert('Please select a valid image.');
                fileInput.value = '';
                return;
            }
            if (file.size > 2 * 1024 * 1024) {
                alert('Image must be less than 2MB.');
                fileInput.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(ev) {
                preview.src = ev.target.result;
                preview.style.display = 'block';
                if (noImageText) noImageText.style.display = 'none';
            };
            reader.readAsDataURL(file);
        });
    });
</script>

<?php $this->endSection() ?>