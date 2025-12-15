<?php

namespace App\Modules\Blog\Models;

use CodeIgniter\Model;

class BlogModel extends Model
{
    protected $table      = 'blog_posts';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $useSoftDeletes = false;
    protected $useTimestamps  = true;

    protected $allowedFields = [
        'slug',
        'title',
        'subtitle',
        'summary',
        'content_html',
        'hero_image_url',
        'category',
        'tags',
        'author_name',
        'theme_primary',
        'theme_secondary',
        'accent_color',
        'layout_mode',
        'font_scale',
        'meta_title',
        'meta_description',
        'is_published',
        'published_at',
    ];

    /**
     * Homepage cards only (published posts)
     */
    public function getHomepagePosts(): array
{
    return $this
        ->where('is_published', 1)
        ->where('published_at IS NOT NULL', null, false)
        ->orderBy('published_at', 'DESC')
        ->findAll(); // 🔥 no limit
}

	
	public function getPostBySlug(string $slug): ?array
{
    return $this
        ->where('slug', $slug)
        ->where('is_published', 1)
        ->where('published_at IS NOT NULL', null, false)
        ->first();
}
public function getBySlug(string $slug): ?array
{
    return $this
        ->where('slug', $slug)
        ->where('is_published', 1)
        ->where('published_at IS NOT NULL', null, false)
        ->first();
}

	
	
	
	
}
