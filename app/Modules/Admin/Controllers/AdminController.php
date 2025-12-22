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
     * Blog list
     * URL: /admin/blogs (and /admin)
     */
    public function blogs()
    {
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
        return view('App\Modules\Admin\Views\create');
    }

    /**
     * Store new post
     * POST: /admin/blogs/store
     */
    public function store()
    {
        $title = trim($this->request->getPost('title') ?? '');
        if ($title === '') {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Title is required.');
        }

        $isPublished = $this->request->getPost('status') === 'published';

        // === SAFE & ROBUST TAGS HANDLING ===
        $tagsInput  = $this->request->getPost('tags');
        $tagsString = is_string($tagsInput) ? trim($tagsInput) : '';
        $tagsArray  = $tagsString !== ''
            ? array_filter(array_map('trim', explode(',', $tagsString)))
            : [];

        $data = [
            'title'          => $title,
            'slug'           => url_title($title, '-', true),
            'subtitle'       => $this->request->getPost('subtitle'),
            'summary'        => $this->request->getPost('summary'),
            'content_html'   => $this->request->getPost('content'),
            'hero_image_url' => $this->request->getPost('hero_image'),
            'category'       => $this->request->getPost('category'),
            'tags'           => $tagsArray,                    // Always an array, never null
            'is_published'   => $isPublished ? 1 : 0,
            'published_at'   => $isPublished ? date('Y-m-d H:i:s') : null,
            'layout_mode'    => 'standard',
            'font_scale'     => 'normal',
        ];

        try {
            $this->blogModel->insert($data);
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Database Error: ' . $e->getMessage());
        }

        return redirect()->to('/admin/blogs')
            ->with('success', 'Post created successfully.');
    }

    /**
     * Edit form
     * URL: /admin/blogs/edit/{id}
     */
    public function edit($id)
    {
        $post = $this->blogModel->find($id);
        if (!$post) {
            return redirect()->to('/admin/blogs')->with('error', 'Post not found.');
        }

        return view('App\Modules\Admin\Views\edit', ['post' => $post]);
    }

    /**
     * Update post
     * POST: /admin/blogs/update/{id}
     */
    public function update($id)
    {
        $post = $this->blogModel->find($id);
        if (!$post) {
            return redirect()->to('/admin/blogs')->with('error', 'Post not found.');
        }

        $title = trim($this->request->getPost('title') ?? '');
        if ($title === '') {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Title is required.');
        }

        $isPublished = $this->request->getPost('status') === 'published';

        // === SAME SAFE TAGS HANDLING FOR UPDATE ===
        $tagsInput  = $this->request->getPost('tags');
        $tagsString = is_string($tagsInput) ? trim($tagsInput) : '';
        $tagsArray  = $tagsString !== ''
            ? array_filter(array_map('trim', explode(',', $tagsString)))
            : [];

        $data = [
            'title'          => $title,
            'slug'           => url_title($title, '-', true),
            'subtitle'       => $this->request->getPost('subtitle'),
            'summary'        => $this->request->getPost('summary'),
            'content_html'   => $this->request->getPost('content'),
            'hero_image_url' => $this->request->getPost('hero_image'),
            'category'       => $this->request->getPost('category'),
            'tags'           => $tagsArray,                    // Always an array
            'is_published'   => $isPublished ? 1 : 0,
            'published_at'   => $isPublished
                ? ($post['published_at'] ?? date('Y-m-d H:i:s'))
                : null,
            'layout_mode'    => $post['layout_mode'] ?? 'standard',
            'font_scale'     => $post['font_scale'] ?? 'normal',
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
        if (!$this->blogModel->find($id)) {
            return redirect()->to('/admin/blogs')->with('error', 'Post not found.');
        }

        $this->blogModel->delete($id);

        return redirect()->to('/admin/blogs')
            ->with('success', 'Post deleted successfully.');
    }
}