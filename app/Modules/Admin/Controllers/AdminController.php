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
        $data['posts'] = $this->blogModel->orderBy('created_at', 'DESC')->findAll();
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

        $heroImageUrl = null;
        $uploadedFile = $this->request->getFile('hero_image_file');

        if ($uploadedFile && $uploadedFile->isValid() && !$uploadedFile->hasMoved()) {
            $newName = $uploadedFile->getRandomName();

            if (env('FILESYSTEM_DRIVER') === 's3') {
                try {
                    // Use the Storage service directly (same as your successful debugger)
                    $storage = \Config\Services::storage('s3');
                    $targetPath = 'blog/' . $newName;

                    // Upload with explicit PUBLIC visibility and Content-Type
                    $storage->write(
                        $targetPath, 
                        file_get_contents($uploadedFile->getTempName()), 
                        [
                            'visibility' => 'public',
                            'contentType' => $uploadedFile->getMimeType()
                        ]
                    );

                    $baseUrl = rtrim(env('AWS_URL'), '/');
                    $heroImageUrl = $baseUrl . '/' . $targetPath;

                } catch (\Exception $e) {
                    log_message('error', 'S3 Upload Failed: ' . $e->getMessage());
                    return redirect()->back()->withInput()->with('error', 'Upload failed: ' . $e->getMessage());
                }
            } else {
                // Local Fallback
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
            'tags'           => is_string($this->request->getPost('tags')) ? array_filter(array_map('trim', explode(',', $this->request->getPost('tags')))) : [],
            'is_published'   => $this->request->getPost('status') === 'published' ? 1 : 0,
            'published_at'   => $this->request->getPost('status') === 'published' ? date('Y-m-d H:i:s') : null,
            'layout_mode'    => 'standard',
            'font_scale'     => 'normal',
        ];

        $this->blogModel->insert($data);
        return redirect()->to('/admin/blogs')->with('success', 'Post created. URL: ' . $heroImageUrl);
    }

    public function edit($id)
    {
        $post = $this->blogModel->find($id);
        if (!$post) return redirect()->to('/admin/blogs')->with('error', 'Post not found.');
        return view('App\Modules\Admin\Views\edit', ['post' => $post]);
    }

    public function update($id)
    {
        $post = $this->blogModel->find($id);
        if (!$post) return redirect()->to('/admin/blogs')->with('error', 'Post not found.');

        $heroImageUrl = $post['hero_image_url'];
        $uploadedFile = $this->request->getFile('hero_image_file');

        if ($uploadedFile && $uploadedFile->isValid() && !$uploadedFile->hasMoved()) {
            $newName = $uploadedFile->getRandomName();

            if (env('FILESYSTEM_DRIVER') === 's3') {
                try {
                    $storage = \Config\Services::storage('s3');
                    $targetPath = 'blog/' . $newName;

                    $storage->write(
                        $targetPath, 
                        file_get_contents($uploadedFile->getTempName()), 
                        ['visibility' => 'public', 'contentType' => $uploadedFile->getMimeType()]
                    );

                    $baseUrl = rtrim(env('AWS_URL'), '/');
                    $heroImageUrl = $baseUrl . '/' . $targetPath;
                } catch (\Exception $e) {
                    return redirect()->back()->withInput()->with('error', 'Update failed: ' . $e->getMessage());
                }
            } else {
                $uploadPath = FCPATH . 'uploads/blog/';
                if (!is_dir($uploadPath)) mkdir($uploadPath, 0755, true);
                $uploadedFile->move($uploadPath, $newName);
                $heroImageUrl = 'uploads/blog/' . $newName;
            }
        }

        $this->blogModel->update($id, [
            'title'          => $this->request->getPost('title'),
            'hero_image_url' => $heroImageUrl,
            'content_html'   => $this->request->getPost('content'),
            'is_published'   => $this->request->getPost('status') === 'published' ? 1 : 0,
        ]);

        return redirect()->to('/admin/blogs')->with('success', 'Post updated.');
    }

    public function delete($id)
    {
        $this->blogModel->delete($id);
        return redirect()->to('/admin/blogs')->with('success', 'Post deleted.');
    }
}