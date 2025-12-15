<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBlogAndVideoTables extends Migration
{
    public function up()
    {
        // BLOG POSTS
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'slug' => ['type' => 'VARCHAR', 'constraint' => 255, 'unique' => true],
            'title' => ['type' => 'VARCHAR', 'constraint' => 255],
            'summary' => ['type' => 'TEXT', 'null' => true],
            'content_html' => ['type' => 'LONGTEXT'],
            'category' => ['type' => 'VARCHAR', 'constraint' => 50],
            'is_published' => ['type' => 'TINYINT', 'default' => 0],
            'published_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'],
            'updated_at' => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP', 'on_update' => 'CURRENT_TIMESTAMP'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('blog_posts');

        // VIDEO POSTS
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'slug' => ['type' => 'VARCHAR', 'constraint' => 255, 'unique' => true],
            'external_url' => ['type' => 'VARCHAR', 'constraint' => 500],
            'title' => ['type' => 'VARCHAR', 'constraint' => 255],
            'category' => ['type' => 'VARCHAR', 'constraint' => 50],
            'is_published' => ['type' => 'TINYINT', 'default' => 0],
            'published_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'],
            'updated_at' => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP', 'on_update' => 'CURRENT_TIMESTAMP'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('video_posts');
    }

    public function down()
    {
        $this->forge->dropTable('blog_posts');
        $this->forge->dropTable('video_posts');
    }
}
