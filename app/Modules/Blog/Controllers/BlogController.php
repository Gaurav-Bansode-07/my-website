<?php

namespace App\Modules\Blog\Controllers;

use App\Controllers\BaseController;
use App\Modules\Blog\Models\BlogModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class BlogController extends BaseController
{
    public function show(string $slug)
    {
        $model = new BlogModel();

        $post = $model->getBySlug($slug);

        if (! $post) {
            throw PageNotFoundException::forPageNotFound('Article not found');
        }

        return view('App\Modules\Blog\Views\show', [
            'post' => $post,
        ]);
    }
	
	public function index()
{
    $model = new BlogModel();

    $posts = $model->getPublishedPosts(); // or your canonical method

    return view('App\Modules\Blog\Views\index', [
        'posts' => $posts,
    ]);
}

}
