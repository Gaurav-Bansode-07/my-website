<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('blog_posts')->insert([
            'slug' => 'live-db-test',
            'title' => 'Live Database Working',
            'summary' => 'This post is coming from DigitalOcean Managed DB',
            'content_html' => '<p>Production database verified.</p>',
            'category' => 'General',
            'is_published' => 1,
            'published_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
