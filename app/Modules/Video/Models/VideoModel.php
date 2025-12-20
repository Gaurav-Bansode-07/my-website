<?php

namespace App\Modules\Video\Models;

use CodeIgniter\Model;

class VideoModel extends Model
{
    protected $table            = 'video_posts';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useTimestamps    = true;

    protected $allowedFields = [
        'slug',
        'external_url',
        'title',
        'subtitle',
        'summary',
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
     * Homepage: latest published videos
     */
    // public function getLatestVideos(int $limit = 10): array
    // {
        // return $this->where('is_published', 1)
                    // ->where('published_at <=', date('Y-m-d H:i:s'))
                    // ->orderBy('published_at', 'DESC')
                    // ->limit($limit)
                    // ->findAll();
    // }
	
	public function getLatestVideos(int $limit = 10): array
{
    return $this->where('is_published', 1)
                ->orderBy('published_at', 'DESC')
                ->limit($limit)
                ->findAll();
}

}
