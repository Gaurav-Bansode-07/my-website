<?php

namespace App\Modules\Admin\Models;

use CodeIgniter\Model;

class AdminBlogModel extends Model
{
    protected $table = 'blog_posts';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'title',
        'slug',
        'subtitle',
        'summary',
        'content_html',
        'hero_image_url',
        'category',
        'tags',
        'is_published',
        'published_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}