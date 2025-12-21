<?php

namespace App\Modules\Admin\Controllers;

use App\Controllers\BaseController;
use App\Modules\Admin\Models\AdminBlogModel;

class AdminController extends BaseController
{
    protected AdminBlogModel $blogModel;

    public function __construct()
    {
        helper(['url', 'text']);
        $this->blogModel = new AdminBlogModel();
    }

    /**
     * 🔐 Admin guard (DO NOT use in constructor)
     */
    private function requireAdmin()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Access denied');
        }

        return null;
    }

    /**
     * Redirect /admin → /admin/blogs
     */
    public function index()
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        return redirect()->to('/admin/blogs');
    }

    /**
     * Blog list
     * URL: /admin/blogs
     */
    public function blogs()
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $data['posts'] = $this->blogModel
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('App\Modules\Admin\Views\index', $data);
    }

    /**
     * Create form
     * URL: /admin/blogs/create
     */
    public function create()
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        return view('App\Modules\Admin\Views\create');
    }

    /**
     * Store post
     * URL: POST /admin/blogs/store
     */
    public function store()
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $title = trim($this->request->getPost('title'));

        if ($title === '') {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Title is required.');
        }

        $isPublished = $this->request->getPost('status') === 'published';

        $data = [
            'title'          => $title,
            'slug'           => url_title($title, '-', true),
            'subtitle'       => $this->request->getPost('subtitle'),
            'summary'        => $this->request->getPost('summary'),
            'content_html'   => $this->request->getPost('content'),
            'hero_image_url' => $this->request->getPost('hero_image'),
            'category'       => $this->request->getPost('category'),
            'tags'           => $this->request->getPost('tags'),
            'is_published'   => $isPublished ? 1 : 0,
            'published_at'   => $isPublished ? date('Y-m-d H:i:s') : null,

            // ✅ REQUIRED enum defaults
            'layout_mode'    => 'standard',
            'font_scale'     => 'normal',
        ];

        $this->blogModel->insert($data);

        return redirect()->to('/admin/blogs')
            ->with('success', 'Post created successfully.');
    }

    /**
     * Edit form
     * URL: /admin/blogs/edit/{id}
     */
    public function edit($id)
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $post = $this->blogModel->find($id);

        if (!$post) {
            return redirect()->to('/admin/blogs')->with('error', 'Post not found.');
        }

        return view('App\Modules\Admin\Views\edit', ['post' => $post]);
    }

    /**
     * Update post
     */
    public function update($id)
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $post = $this->blogModel->find($id);

        if (!$post) {
            return redirect()->to('/admin/blogs')->with('error', 'Post not found.');
        }

        $title = trim($this->request->getPost('title'));

        if ($title === '') {
            return redirect()->back()->withInput()->with('error', 'Title is required.');
        }

        $isPublished = $this->request->getPost('status') === 'published';

        $data = [
            'title'          => $title,
            'slug'           => url_title($title, '-', true),
            'subtitle'       => $this->request->getPost('subtitle'),
            'summary'        => $this->request->getPost('summary'),
            'content_html'   => $this->request->getPost('content'),
            'hero_image_url' => $this->request->getPost('hero_image'),
            'category'       => $this->request->getPost('category'),
            'tags'           => $this->request->getPost('tags'),
            'is_published'   => $isPublished ? 1 : 0,
            'published_at'   => $isPublished
                ? ($post['published_at'] ?? date('Y-m-d H:i:s'))
                : null,

            // ✅ Preserve enum safety
            'layout_mode'    => $post['layout_mode'] ?? 'standard',
            'font_scale'     => $post['font_scale']  ?? 'normal',
        ];

        $this->blogModel->update($id, $data);

        return redirect()->to('/admin/blogs')
            ->with('success', 'Post updated successfully.');
    }

    /**
     * Delete post
     * URL: /admin/blogs/delete/{id}
     */
    public function delete($id)
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        if (!$this->blogModel->find($id)) {
            return redirect()->to('/admin/blogs')->with('error', 'Post not found.');
        }

        $this->blogModel->delete($id);

        return redirect()->to('/admin/blogs')
            ->with('success', 'Post deleted successfully.');
    }
}
