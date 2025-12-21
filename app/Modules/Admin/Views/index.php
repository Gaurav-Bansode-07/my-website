<?php $this->extend('layouts/main') ?>

<?php $this->section('title') ?>Admin – Blog Posts<?php $this->endSection() ?>

<?php $this->section('content') ?>

<style>
    /* Strong admin header */
    #main-header {
        background: #111827;
        box-shadow: 0 2px 12px rgba(0,0,0,0.2);
    }
    #main-header .header-inner {
        justify-content: space-between;
        align-items: center;
    }
    #main-header nav, .btn-subscribe, .hamburger { display: none !important; }
    #main-header .logo { 
        color: #fff; 
        font-weight: 800; 
        font-size: 20px; 
    }
    footer { display: none !important; }
    body { padding-top: 0; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const logo = document.querySelector('.logo');
        if (logo) {
            logo.textContent = 'Admin Panel';
            logo.href = '<?= site_url('admin') ?>';
        }

        const headerTop = document.querySelector('.header-top');
        if (headerTop) {
            const backLink = document.createElement('a');
            backLink.href = '<?= site_url('/') ?>';
            backLink.innerHTML = '← Back to Site';
            backLink.style.color = '#9ca3af';
            backLink.style.fontSize = '14px';
            backLink.style.fontWeight = '600';
            backLink.style.textDecoration = 'none';
            headerTop.appendChild(backLink);
        }
    });
</script>

<div class="admin-container">
    <div class="admin-header">
    <div class="admin-title-group">
        <h1>Blog Management</h1>
        <p class="admin-subtitle">
            Create new posts or 
            <svg style="width:16px;height:16px;display:inline;vertical-align:-2px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
            </svg>
            edit existing ones
        </p>
    </div>
    <div class="admin-actions">
        <a href="<?= site_url('admin/blogs/create') ?>" class="btn-create">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            New Post
        </a>
    </div>
</div>
    <!-- Flash Messages -->
    <?php if (session()->has('success')): ?>
        <div class="alert alert-success">✓ <?= esc(session('success')) ?></div>
    <?php endif; ?>
    <?php if (session()->has('error')): ?>
        <div class="alert alert-error">✕ <?= esc(session('error')) ?></div>
    <?php endif; ?>

    <?php if (empty($posts)): ?>
        <div class="empty-state">
            <h2>No Posts Yet</h2>
            <p>Start building your content library</p>
            <a href="<?= site_url('admin/blogs/create') ?>" class="btn-create-large">
                Create Your First Post
            </a>
        </div>
    <?php else: ?>
        <div class="admin-table-container bordered">
            <table class="admin-table bordered">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Published</th>
                        <th>Created</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($posts as $post): ?>
                        <tr>
                            <td class="title-cell">
                                <div class="post-title"><?= esc($post['title']) ?></div>
                                <?php if (!empty($post['subtitle'])): ?>
                                    <div class="post-subtitle"><?= esc($post['subtitle']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($post['category'] ?? '—') ?></td>
                            <td>
                                <span class="status-badge <?= $post['is_published'] ? 'published' : 'draft' ?>">
                                    <?= $post['is_published'] ? 'Published' : 'Draft' ?>
                                </span>
                            </td>
                            <td>
                                <?= $post['published_at'] 
                                    ? date('M j, Y', strtotime($post['published_at'])) 
                                    : '<span class="text-muted">—</span>' ?>
                            </td>
                            <td><?= date('M j, Y', strtotime($post['created_at'])) ?></td>
                            <td class="actions text-right">
                                <a href="<?= site_url('blog/' . $post['slug']) ?>" class="action-btn view" target="_blank" title="View">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </a>
                                <a href="<?= site_url('admin/blogs/edit/' . $post['id']) ?>" class="action-btn edit" title="Edit">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </a>
                                <a href="<?= site_url('admin/blogs/delete/' . $post['id']) ?>" 
   class="action-btn delete" 
   onclick="return confirm('⚠️ Delete «<?= esc($post['title']) ?>»?\n\nThis action cannot be undone.')"
   title="Delete Post">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M3 6h18"></path>
        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
        <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
        <line x1="10" y1="11" x2="10" y2="17"></line>
        <line x1="14" y1="11" x2="14" y2="17"></line>
    </svg>
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