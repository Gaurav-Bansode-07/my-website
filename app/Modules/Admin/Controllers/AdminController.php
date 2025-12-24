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
        $title = trim($this->request->getPost('title') ?? '');
        if ($title === '') {
            return redirect()->back()->withInput()->with('error', 'Title is required.');
        }

        $isPublished = $this->request->getPost('status') === 'published';
        $tagsInput  = $this->request->getPost('tags');
        $tagsString = is_string($tagsInput) ? trim($tagsInput) : '';
        $tagsArray  = $tagsString !== '' ? array_filter(array_map('trim', explode(',', $tagsString))) : [];

        $heroImageUrl = null;
        $uploadedFile = $this->request->getFile('hero_image_file');

        // --- START DEBUGGING ---
        log_message('debug', 'STARTING BLOG STORE: ' . $title);
        // --- END DEBUGGING ---

        if ($uploadedFile && $uploadedFile->isValid() && !$uploadedFile->hasMoved()) {
            
            $newName = $uploadedFile->getRandomName();

            if (env('FILESYSTEM_DRIVER') === 's3') {
                try {
                    $path = $uploadedFile->store('blog', $newName, 's3');
                    
                    $baseUrl = rtrim(env('AWS_URL'), '/');
                    $cleanPath = ltrim($path, '/');
                    $heroImageUrl = $baseUrl . '/' . $cleanPath;

                    // --- START DEBUGGING ---
                    log_message('debug', 'S3 UPLOAD SUCCESS. Returned Path: ' . $path);
                    log_message('debug', 'FINAL GENERATED URL: ' . $heroImageUrl);
                    // --- END DEBUGGING ---

                } catch (\Exception $e) {
                    log_message('error', 'S3 upload failed: ' . $e->getMessage());
                    return redirect()->back()->withInput()->with('error', 'Image upload failed: ' . $e->getMessage());
                }
            } else {
                $uploadPath = FCPATH . 'uploads/blog/';
                if (!is_dir($uploadPath)) mkdir($uploadPath, 0755, true);
                $uploadedFile->move($uploadPath, $newName);
                $heroImageUrl = 'uploads/blog/' . $newName;
                log_message('debug', 'LOCAL UPLOAD SUCCESS: ' . $heroImageUrl);
            }
        }

        $data = [
            'title'          => $title,
            'slug'           => url_title($title, '-', true),
            'subtitle'       => $this->request->getPost('subtitle'),
            'summary'        => $this->request->getPost('summary'),
            'content_html'   => $this->request->getPost('content'),
            'hero_image_url' => $heroImageUrl,
            'category'       => $this->request->getPost('category'),
            'tags'           => $tagsArray,
            'is_published'   => $isPublished ? 1 : 0,
            'published_at'   => $isPublished ? date('Y-m-d H:i:s') : null,
            'layout_mode'    => 'standard',
            'font_scale'     => 'normal',
        ];

        try {
            $this->blogModel->insert($data);
        } catch (\Exception $e) {
            log_message('error', 'DB INSERT FAILED: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Database Error: ' . $e->getMessage());
        }

        // We add a debug message to the success notification so you can see the URL in the browser
        $msg = 'Post created successfully.';
        if ($heroImageUrl) $msg .= ' Image URL: ' . $heroImageUrl;

        return redirect()->to('/admin/blogs')->with('success', $msg);
    }

    public function edit($id)
    {
        $post = $this->blogModel->find($id);
        if (!$post) {
            return redirect()->to('/admin/blogs')->with('error', 'Post not found.');
        }

        return view('App\Modules\Admin\Views\edit', ['post' => $post]);
    }

    public function update($id)
    {
        $post = $this->blogModel->find($id);
        if (!$post) return redirect()->to('/admin/blogs')->with('error', 'Post not found.');

        $title = trim($this->request->getPost('title') ?? '');
        if ($title === '') return redirect()->back()->withInput()->with('error', 'Title is required.');

        $isPublished = $this->request->getPost('status') === 'published';
        $tagsInput  = $this->request->getPost('tags');
        $tagsString = is_string($tagsInput) ? trim($tagsInput) : '';
        $tagsArray  = $tagsString !== '' ? array_filter(array_map('trim', explode(',', $tagsString))) : [];

        $heroImageUrl = $post['hero_image_url'];
        $uploadedFile = $this->request->getFile('hero_image_file');

        log_message('debug', 'STARTING BLOG UPDATE FOR ID: ' . $id);

        if ($uploadedFile && $uploadedFile->isValid() && !$uploadedFile->hasMoved()) {
            $newName = $uploadedFile->getRandomName();

            if (env('FILESYSTEM_DRIVER') === 's3') {
                try {
                    $path = $uploadedFile->store('blog', $newName, 's3');
                    $baseUrl = rtrim(env('AWS_URL'), '/');
                    $cleanPath = ltrim($path, '/');
                    $heroImageUrl = $baseUrl . '/' . $cleanPath;
                    
                    log_message('debug', 'UPDATE S3 SUCCESS. Path: ' . $path . ' | URL: ' . $heroImageUrl);
                } catch (\Exception $e) {
                    log_message('error', 'Update S3 failed: ' . $e->getMessage());
                    return redirect()->back()->withInput()->with('error', 'Image upload failed: ' . $e->getMessage());
                }
            } else {
                $uploadPath = FCPATH . 'uploads/blog/';
                if (!is_dir($uploadPath)) mkdir($uploadPath, 0755, true);
                $uploadedFile->move($uploadPath, $newName);
                $heroImageUrl = 'uploads/blog/' . $newName;
            }
        }

        $data = [
            'title'          => $title,
            'slug'           => url_title($title, '-', true),
            'subtitle'       => $this->request->getPost('subtitle'),
            'summary'        => $this->request->getPost('summary'),
            'content_html'   => $this->request->getPost('content'),
            'hero_image_url' => $heroImageUrl,
            'category'       => $this->request->getPost('category'),
            'tags'           => $tagsArray,
            'is_published'   => $isPublished ? 1 : 0,
            'published_at'   => $isPublished ? ($post['published_at'] ?? date('Y-m-d H:i:s')) : null,
            'layout_mode'    => $post['layout_mode'] ?? 'standard',
            'font_scale'     => $post['font_scale'] ?? 'normal',
        ];

        $this->blogModel->update($id, $data);

        return redirect()->to('/admin/blogs')->with('success', 'Post updated. Current Image URL: ' . $heroImageUrl);
    }

    public function delete($id)
    {
        $post = $this->blogModel->find($id);
        if (!$post) return redirect()->to('/admin/blogs')->with('error', 'Post not found.');

        $this->blogModel->delete($id);
        return redirect()->to('/admin/blogs')->with('success', 'Post deleted successfully.');
    }
}