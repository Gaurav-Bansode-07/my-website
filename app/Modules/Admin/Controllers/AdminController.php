<?php

namespace App\Modules\Admin\Controllers;

use App\Controllers\BaseController;
use App\Modules\Admin\Models\AdminBlogModel;

class AdminController extends BaseController
{
    protected AdminBlogModel $blogModel;

    public function __construct()
    {
        $this->blogModel = new AdminBlogModel();
        helper(['url', 'text']);
    }

    public function index()
    {
        return redirect()->to('/admin/blogs');
    }

    public function blogs()
    {
        $data['posts'] = $this->blogModel
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('App\Modules\Admin\Views\index', $data);
    }

    public function create()
    {
        return view('App\Modules\Admin\Views\create');
    }

    public function store()
    {
        $title = trim($this->request->getPost('title'));

        if (empty($title)) {
            return redirect()->back()->withInput()->with('error', 'Title is required.');
        }

        $status = $this->request->getPost('status') === 'published' ? 1 : 0;

        $data = [
            'title'          => $title,
            'slug'           => url_title($title, '-', true),
            'subtitle'       => $this->request->getPost('subtitle'),
            'summary'        => $this->request->getPost('summary'),         // ← matches DB
            'content_html'   => $this->request->getPost('content'),         // ← matches DB
            'hero_image_url' => $this->request->getPost('hero_image'),      // ← matches DB
            'category'       => $this->request->getPost('category'),
            'tags'           => $this->request->getPost('tags'),            // JSON string or array
            'is_published'   => $status,
            'published_at'   => $status ? date('Y-m-d H:i:s') : null,
        ];

        $this->blogModel->insert($data);

        return redirect()->to('/admin/blogs')->with('success', 'Post created successfully.');
    }

    public function edit($id)
    {
        $post = $this->blogModel->find($id);

        if (!$post) {
            return redirect()->to('/admin/blogs')->with('error', 'Post not found.');
        }

        $data['post'] = $post;
        return view('App\Modules\Admin\Views\edit', $data);
    }

    public function update($id)
    {
        $post = $this->blogModel->find($id);

        if (!$post) {
            return redirect()->to('/admin/blogs')->with('error', 'Post not found.');
        }

        $title = trim($this->request->getPost('title'));

        if (empty($title)) {
            return redirect()->back()->withInput()->with('error', 'Title is required.');
        }

        $status = $this->request->getPost('status') === 'published' ? 1 : 0;

        $data = [
            'title'          => $title,
            'slug'           => url_title($title, '-', true),
            'subtitle'       => $this->request->getPost('subtitle'),
            'summary'        => $this->request->getPost('summary'),
            'content_html'   => $this->request->getPost('content'),
            'hero_image_url' => $this->request->getPost('hero_image'),
            'category'       => $this->request->getPost('category'),
            'tags'           => $this->request->getPost('tags'),
            'is_published'   => $status,
            'published_at'   => $status 
                ? ($post['is_published'] ? $post['published_at'] : date('Y-m-d H:i:s'))
                : null,
        ];

        $this->blogModel->update($id, $data);

        return redirect()->to('/admin/blogs')->with('success', 'Post updated successfully.');
    }

    public function test()
    {
        return view('App\Modules\Admin\Views\test');
    }
	
	
	/**
 * Delete blog post
 * URL: GET /admin/blogs/delete/{id}
 */
public function delete($id)
{
    $post = $this->blogModel->find($id);

    if (!$post) {
        return redirect()->to('/admin/blogs')->with('error', 'Post not found.');
    }

    // Optional: Add confirmation step
    // For now, we'll delete directly with JS confirmation in view

    $this->blogModel->delete($id);

    return redirect()->to('/admin/blogs')->with('success', 'Post deleted successfully.');
}
}