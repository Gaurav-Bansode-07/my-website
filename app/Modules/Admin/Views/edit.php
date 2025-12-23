<?php $this->extend('layouts/main') ?>
<?php $this->section('title') ?>Edit Post – <?= esc($post['title']) ?><?php $this->endSection() ?>

<?php $this->section('content') ?>
<style>
    #main-header {
        background: #111827;
        box-shadow: 0 2px 12px rgba(0,0,0,0.2);
    }
    #main-header .header-inner { justify-content: space-between; align-items: center; }
    #main-header nav, .btn-subscribe, .hamburger { display: none !important; }
    #main-header .logo { color: #fff; font-weight: 800; font-size: 20px; }
    footer { display: none !important; }

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
</style>

<div class="admin-container">
    <div class="admin-header">
        <div class="admin-title-group">
            <h1>Edit Post</h1>
            <p class="admin-subtitle">Refine and perfect your content</p>
        </div>
        <a href="<?= site_url('admin/blogs') ?>" class="btn-create" style="background:#6b7280; padding:12px 20px;">
            ← Back to Posts
        </a>
    </div>

    <?php if (session()->has('error')): ?>
        <div class="alert alert-error">✕ <?= esc(session('error')) ?></div>
    <?php endif; ?>

    <!-- IMPORTANT: enctype for file upload -->
    <form action="<?= site_url('admin/blogs/update/' . $post['id']) ?>" method="post" class="admin-form" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="form-group">
            <label>Title <span class="required">*</span></label>
            <input type="text"
                   name="title"
                   value="<?= esc(old('title', $post['title'])) ?>"
                   required
                   class="form-input">
        </div>

        <div class="form-group">
            <label>Subtitle</label>
            <input type="text"
                   name="subtitle"
                   value="<?= esc(old('subtitle', $post['subtitle'] ?? '')) ?>"
                   class="form-input">
        </div>

        <div class="form-group">
            <label>Summary / Excerpt</label>
            <textarea name="summary" rows="4" class="form-input"><?= esc(old('summary', $post['summary'] ?? '')) ?></textarea>
        </div>

        <div class="form-group">
            <label>Content <span class="required">*</span></label>
            <div id="editor"></div>
            <textarea name="content" id="content-hidden" style="display:none;"></textarea>
        </div>

        <!-- HERO IMAGE UPLOAD WITH PREVIEW (EDIT VERSION) -->
        <div class="form-group">
            <label>Hero Image <span class="required">(optional)</span></label>
            
            <!-- Preview Container -->
            <div id="hero-preview-container" style="margin-bottom:16px;">
                <?php if (!empty($post['hero_image_url'])): ?>
                    <img id="hero-preview" 
                         src="<?= base_url($post['hero_image_url']) ?>" 
                         style="max-width:400px; max-height:300px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
                    <p style="color:#64748b; margin-top:8px;">Current image. Upload a new one to replace it.</p>
                <?php else: ?>
                    <img id="hero-preview" src="" style="display:none; max-width:400px; max-height:300px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
                    <p id="no-image-text" style="color:#64748b; font-style:italic;">No image selected</p>
                <?php endif; ?>
            </div>

            <!-- File Input -->
            <input type="file"
                   name="hero_image_file"
                   id="hero_image_file"
                   accept="image/*"
                   class="form-input">

            <small class="text-muted">Upload a new image (JPG, PNG, GIF, WebP). Max 2MB. Will replace current hero image.</small>
        </div>

        <div class="form-group">
            <label>Category</label>
            <input type="text"
                   name="category"
                   value="<?= esc(old('category', $post['category'] ?? '')) ?>"
                   class="form-input">
        </div>

        <div class="form-group">
            <label>Tags (comma separated)</label>
            <?php
                $existingTags = '';
                if (!empty($post['tags']) && is_array($post['tags'])) {
                    $existingTags = implode(', ', $post['tags']);
                }
            ?>
            <input type="text"
                   name="tags"
                   value="<?= esc(old('tags', $existingTags)) ?>"
                   class="form-input"
                   placeholder="finance, investing, startups">
            <small class="text-muted">Separate tags with commas</small>
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-input">
                <option value="draft" <?= old('status', $post['is_published'] ? 'published' : 'draft') !== 'published' ? 'selected' : '' ?>>
                    Draft
                </option>
                <option value="published" <?= old('status', $post['is_published'] ? 'published' : 'draft') === 'published' ? 'selected' : '' ?>>
                    Published
                </option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-create-large">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <path d="M9 15h6"></path>
                    <path d="M12 12v6"></path>
                </svg>
                Save Changes
            </button>
        </div>
    </form>
</div>

<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize Quill Editor
        const quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ header: [1, 2, 3, 4, 5, 6, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote', 'code-block'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    [{ indent: '-1' }, { indent: '+1' }],
                    ['link', 'image', 'video'],
                    [{ align: [] }],
                    ['clean']
                ]
            },
            placeholder: 'Start writing your masterpiece...',
        });

        // Load existing content (from DB or old input)
        const savedContent = <?= json_encode(old('content', $post['content_html'] ?? '')) ?>;
        if (savedContent && savedContent.trim() !== '') {
            quill.root.innerHTML = savedContent;
        }

        // Sync on submit
        const form = document.querySelector('form');
        form.addEventListener('submit', function () {
            document.getElementById('content-hidden').value = quill.root.innerHTML;
        });

        // Hero Image Preview & Validation (Edit Version)
        const fileInput = document.getElementById('hero_image_file');
        const preview = document.getElementById('hero-preview');
        const noImageText = document.getElementById('no-image-text');

        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];

            if (!file) {
                // If cleared, show original or no image text
                if ("<?= !empty($post['hero_image_url']) ? 'true' : 'false' ?>" === 'true') {
                    preview.src = "<?= base_url($post['hero_image_url'] ?? '') ?>";
                    preview.style.display = 'block';
                    if (noImageText) noImageText.style.display = 'none';
                } else {
                    preview.style.display = 'none';
                    if (noImageText) noImageText.style.display = 'block';
                }
                return;
            }

            // Validate type
            if (!file.type.startsWith('image/')) {
                alert('Please select a valid image file.');
                fileInput.value = '';
                return;
            }

            // Validate size
            if (file.size > 2 * 1024 * 1024) {
                alert('Image must be less than 2MB.');
                fileInput.value = '';
                return;
            }

            // Preview new image
            const reader = new FileReader();
            reader.onload = function(event) {
                preview.src = event.target.result;
                preview.style.display = 'block';
                if (noImageText) noImageText.style.display = 'none';
            };
            reader.readAsDataURL(file);
        });
    });
</script>

<?php $this->endSection() ?>