<?php

namespace App\Modules\Blog\Controllers;

use App\Controllers\BaseController;
use App\Modules\Blog\Models\BlogModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class BlogController extends BaseController
{
    /**
     * Blog listing page
     * /blog
     */
    public function index()
    {
        $model = new BlogModel();

        $posts = $model->getPublishedPosts();

        return view('App\Modules\Blog\Views\index', [
            'posts' => $posts,
        ]);
    }

    /**
     * Blog detail page
     * /blog/{slug}
     */
    public function show(string $slug)
    {
        $model = new BlogModel();

        $post = $model->getPostBySlug($slug);

        if (! $post) {
            throw PageNotFoundException::forPageNotFound('Post not found');
        }

        return view('App\Modules\Blog\Views\show', [
            'post' => $post,
        ]);
    }
}
