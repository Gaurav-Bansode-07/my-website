<?php $this->extend('layouts/main') ?>
<?php $this->section('title') ?>Manage Videos<?php $this->endSection() ?>
<?php $this->section('content') ?>

<!-- Hide header/footer in admin -->
<style>
    #main-header, footer { display: none !important; }
</style>

<div class="admin-container">
    <div class="admin-header">
        <div class="admin-title-group">
            <h1>Manage Video Posts</h1>
            <p class="admin-subtitle">External videos (YouTube, Vimeo, etc.)</p>
        </div>
        <a href="<?= site_url('admin/videos/create') ?>" class="btn-create-large">
            + Add New Video
        </a>
    </div>

    <a href="<?= site_url('admin/blogs') ?>" style="color:#64748b; margin-bottom:24px; display:inline-block; font-size:15px;">
        ← Back to Blog Posts
    </a>

    <?php if (session()->has('success')): ?>
        <div class="alert" style="background:#dcfce7; color:#166534; border:1px solid #86efac; margin-bottom:32px;">
            ✓ <?= esc(session('success')) ?>
        </div>
    <?php endif; ?>

    <?php if (empty($videos)): ?>
        <div class="empty-state">
            <p style="font-size:18px; color:#64748b; margin-bottom:20px;">No videos yet.</p>
            <a href="<?= site_url('admin/videos/create') ?>" class="btn-create-large" style="display:inline-flex;">
                Create Your First Video Post
            </a>
        </div>
    <?php else: ?>
        <!-- Desktop & Mobile Responsive Table using your global admin styles -->
        <div class="admin-table-container bordered">
            <table class="admin-table bordered">
                <thead>
                    <tr>
                        <th style="width:40%;">Title</th>
                        <th>Status</th>
                        <th>Published</th>
                        <th style="width:25%;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($videos as $video): ?>
                        <tr>
                            <td class="title-cell">
                                <div class="post-title"><?= esc($video['title']) ?></div>
                                <?php if (!empty($video['subtitle'])): ?>
                                    <div class="post-subtitle"><?= esc($video['subtitle']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="status-badge" style="background: <?= $video['is_published'] ? '#dcfce7; color:#166534' : '#fed7aa; color:#9a3412' ?>;">
                                    <?= $video['is_published'] ? 'Published' : 'Draft' ?>
                                </span>
                            </td>
                            <td>
                                <?= $video['published_at'] ? date('M d, Y', strtotime($video['published_at'])) : '<span style="color:#94a3b8;">—</span>' ?>
                            </td>
                            <td class="actions">
                                <a href="<?= site_url('admin/videos/edit/' . $video['id']) ?>" class="btn-create" style="padding:8px 16px; font-size:13px;">
                                    Edit
                                </a>
                                <a href="<?= site_url('admin/videos/delete/' . $video['id']) ?>" 
                                   class="btn-create" 
                                   style="background:#ef4444; padding:8px 16px; font-size:13px;" 
                                   onclick="return confirm('Delete this video permanently?')">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php $this->endSection() ?>