<?php

namespace App\Modules\Admin\Models;

use CodeIgniter\Model;

class AdminBlogModel extends Model
{
    protected $table      = 'blog_posts';
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
        'layout_mode',
        'font_scale'
    ];

    // Add the type declaration here: protected array $casts
    protected array $casts = [
        'tags'         => 'json-array',      // Automatically encodes/decodes JSON <-> array
        'is_published' => 'boolean',   // Casts 1/0 <-> true/false
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}