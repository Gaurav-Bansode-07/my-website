<?php

namespace App\Modules\Admin\Controllers;

use App\Controllers\BaseController;
use App\Modules\Admin\Models\AdminBlogModel;
use CodeIgniter\Files\File;

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
     */
    public function create()
    {
        return view('App\Modules\Admin\Views\create');
    }

    /**
     * Store new post
     */
    public function store()
    {
        $title = trim($this->request->getPost('title') ?? '');
        if ($title === '') {
            return redirect()->back()->withInput()->with('error', 'Title is required.');
        }

        $isPublished = $this->request->getPost('status') === 'published';

        // TAGS HANDLING
        $tagsInput  = $this->request->getPost('tags');
        $tagsString = is_string($tagsInput) ? trim($tagsInput) : '';
        $tagsArray  = $tagsString !== '' ? array_filter(array_map('trim', explode(',', $tagsString))) : [];

        // HERO IMAGE UPLOAD
        $heroImageUrl = null;
        $uploadedFile = $this->request->getFile('hero_image_file');

        if ($uploadedFile && $uploadedFile->isValid() && !$uploadedFile->hasMoved()) {
            // Validate type
            if (!str_starts_with($uploadedFile->getMimeType(), 'image/')) {
                return redirect()->back()->withInput()->with('error', 'Only image files are allowed.');
            }

            // CORRECT 2MB CHECK
            if ($uploadedFile->getSize() > 2 * 1024 * 1024) {
                return redirect()->back()->withInput()->with('error', 'Image must be less than 2MB.');
            }

            $newName = $uploadedFile->getRandomName();

            // Production: Upload to S3 (Spaces)
            if (env('FILESYSTEM_DRIVER') === 's3') {
                try {
                    $options = ['visibility' => 'public'];
					// Remove the 'visibility' option for a moment to see if it's a permission conflict
					$path = $uploadedFile->store('blog', $newName, 's3');
                    // $path = $uploadedFile->store('blog/', $newName, 's3', $options);
                    $heroImageUrl = env('AWS_URL') . '/' . $path;
                } catch (\Exception $e) {
                    // Log error for debugging
                    log_message('error', 'S3 upload failed: ' . $e->getMessage());
                    return redirect()->back()->withInput()->with('error', 'Image upload failed. Please try again.');
                }
            } else {
                // Local fallback
                $uploadPath = FCPATH . 'uploads/blog/';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                $uploadedFile->move($uploadPath, $newName);
                $heroImageUrl = 'uploads/blog/' . $newName;
            }
        }

        $data = [
            'title' => $title,
            'slug' => url_title($title, '-', true),
            'subtitle' => $this->request->getPost('subtitle'),
            'summary' => $this->request->getPost('summary'),
            'content_html' => $this->request->getPost('content'),
            'hero_image_url' => $heroImageUrl,
            'category' => $this->request->getPost('category'),
            'tags' => $tagsArray,
            'is_published' => $isPublished ? 1 : 0,
            'published_at' => $isPublished ? date('Y-m-d H:i:s') : null,
            'layout_mode' => 'standard',
            'font_scale' => 'normal',
        ];

        try {
            $this->blogModel->insert($data);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Database Error: ' . $e->getMessage());
        }

        return redirect()->to('/admin/blogs')->with('success', 'Post created successfully.');
    }

    /**
     * Edit form
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
     */
    public function update($id)
    {
        $post = $this->blogModel->find($id);
        if (!$post) {
            return redirect()->to('/admin/blogs')->with('error', 'Post not found.');
        }

        $title = trim($this->request->getPost('title') ?? '');
        if ($title === '') {
            return redirect()->back()->withInput()->with('error', 'Title is required.');
        }

        $isPublished = $this->request->getPost('status') === 'published';

        // TAGS HANDLING
        $tagsInput  = $this->request->getPost('tags');
        $tagsString = is_string($tagsInput) ? trim($tagsInput) : '';
        $tagsArray  = $tagsString !== '' ? array_filter(array_map('trim', explode(',', $tagsString))) : [];

        // HERO IMAGE (keep existing)
        $heroImageUrl = $post['hero_image_url'];

        $uploadedFile = $this->request->getFile('hero_image_file');

        if ($uploadedFile && $uploadedFile->isValid() && !$uploadedFile->hasMoved()) {
            // Validate type
            if (!str_starts_with($uploadedFile->getMimeType(), 'image/')) {
                return redirect()->back()->withInput()->with('error', 'Only image files are allowed.');
            }

            // CORRECT 2MB CHECK
            if ($uploadedFile->getSize() > 2 * 1024 * 1024) {
                return redirect()->back()->withInput()->with('error', 'Image must be less than 2MB.');
            }

            $newName = $uploadedFile->getRandomName();

            // Production: S3
            if (env('FILESYSTEM_DRIVER') === 's3') {
                try {
                    $options = ['visibility' => 'public'];
                    $path = $uploadedFile->store('blog/', $newName, 's3', $options);
                    $heroImageUrl = env('AWS_URL') . '/' . $path;
                } catch (\Exception $e) {
                    log_message('error', 'S3 upload failed: ' . $e->getMessage());
                    return redirect()->back()->withInput()->with('error', 'Image upload failed. Please try again.');
                }
            } else {
                // Local fallback
                $uploadPath = FCPATH . 'uploads/blog/';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                $uploadedFile->move($uploadPath, $newName);
                $heroImageUrl = 'uploads/blog/' . $newName;
            }
        }

        $data = [
            'title' => $title,
            'slug' => url_title($title, '-', true),
            'subtitle' => $this->request->getPost('subtitle'),
            'summary' => $this->request->getPost('summary'),
            'content_html' => $this->request->getPost('content'),
            'hero_image_url' => $heroImageUrl,
            'category' => $this->request->getPost('category'),
            'tags' => $tagsArray,
            'is_published' => $isPublished ? 1 : 0,
            'published_at' => $isPublished ? ($post['published_at'] ?? date('Y-m-d H:i:s')) : null,
            'layout_mode' => $post['layout_mode'] ?? 'standard',
            'font_scale' => $post['font_scale'] ?? 'normal',
        ];

        $this->blogModel->update($id, $data);

        return redirect()->to('/admin/blogs')->with('success', 'Post updated successfully.');
    }

    public function delete($id)
    {
        $post = $this->blogModel->find($id);
        if (!$post) {
            return redirect()->to('/admin/blogs')->with('error', 'Post not found.');
        }

        $this->blogModel->delete($id);

        return redirect()->to('/admin/blogs')->with('success', 'Post deleted successfully.');
    }
}