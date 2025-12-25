<?php

namespace App\Modules\Video\Models;

use CodeIgniter\Model;

class VideoModel extends Model
{
    protected $table         = 'video_posts';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'slug',
        'external_url',
        'title',
        'subtitle',
        'summary',
        'hero_image_url',
        'category',
        'tags',
        'is_published',
        'published_at',
    ];

    public function getLatestVideos(): array
    {
        return $this->where('is_published', 1)
                    ->orderBy('published_at', 'DESC')
                    ->orderBy('id', 'DESC')
                    ->findAll();
    }
}