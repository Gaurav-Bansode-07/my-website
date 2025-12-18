<?php

namespace App\Modules\Blog\Models;

use CodeIgniter\Model;

class BlogModel extends Model
{
    protected $table      = 'blog_posts';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'slug',
        'title',
        'summary',
        'content_html',
        'hero_image_url',
        'author_name',
        'meta_title',
        'meta_description',
        'is_published',
        'published_at',
        'created_at',
        'updated_at',
    ];

    /**
     * Homepage / blog listing
     */
    public function getPublishedPosts(): array
{
    return $this->where('is_published', 1)
                ->where('published_at <=', 'NOW()', false)
                ->orderBy('published_at', 'DESC')
                ->findAll();
}


    /**
     * Single post by slug
     */
   public function getPostBySlug(string $slug): ?array
{
    return $this->where('slug', $slug)
                ->where('is_published', 1)
                ->where('published_at <=', 'NOW()', false)
                ->first();
}

}
