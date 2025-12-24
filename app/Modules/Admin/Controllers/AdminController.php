<?php

namespace App\Modules\Admin\Controllers;

use App\Controllers\BaseController;
use App\Modules\Admin\Models\AdminBlogModel;
use CodeIgniter\Files\File;
// Import the S3 Client
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

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

        // TAGS HANDLING - Fixed to never be null
        $tagsInput  = $this->request->getPost('tags');
        $tagsString = is_string($tagsInput) ? trim($tagsInput) : '';
        $tagsArray  = $tagsString !== '' ? array_filter(array_map('trim', explode(',', $tagsString))) : [];

        // HERO IMAGE UPLOAD
        $heroImageUrl = null;
        $uploadedFile = $this->request->getFile('hero_image_file');

        if ($uploadedFile && $uploadedFile->isValid() && !$uploadedFile->hasMoved()) {
            $newName = $uploadedFile->getRandomName();

            if (env('FILESYSTEM_DRIVER') === 's3') {
                try {
                    // Use Direct S3 Client to avoid "member function write() on null"
                    $s3 = new S3Client([
                        'version'     => 'latest',
                        'region'      => env('AWS_REGION', 'us-east-1'),
                        'endpoint'    => env('AWS_ENDPOINT'),
                        'credentials' => [
                            'key'    => env('AWS_ACCESS_KEY_ID'),
                            'secret' => env('AWS_SECRET_ACCESS_KEY'),
                        ],
                        'use_path_style_endpoint' => false,
                    ]);

                    $key = 'blog/' . $newName;

                    $s3->putObject([
                        'Bucket'      => env('AWS_BUCKET'),
                        'Key'         => $key,
                        'Body'        => fopen($uploadedFile->getTempName(), 'rb'),
                        'ACL'         => 'public-read', // Makes the URL work
                        'ContentType' => $uploadedFile->getMimeType(),
                    ]);

                    $baseUrl = rtrim(env('AWS_URL'), '/');
                    $heroImageUrl = $baseUrl . '/' . $key;
                } catch (AwsException $e) {
                    return redirect()->back()->withInput()->with('error', 'S3 Error: ' . $e->getAwsErrorMessage());
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
            'tags'           => $tagsArray, // Sent as array to prevent Null Exception
            'is_published'   => $isPublished ? 1 : 0,
            'published_at'   => $isPublished ? date('Y-m-d H:i:s') : null,
            'layout_mode'    => 'standard',
            'font_scale'     => 'normal',
        ];

        try {
            $this->blogModel->insert($data);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Database Error: ' . $e->getMessage());
        }

        return redirect()->to('/admin/blogs')->with('success', 'Post created successfully.');
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

        $title = trim($this->request->getPost('title') ?? '');
        $isPublished = $this->request->getPost('status') === 'published';

        // TAGS HANDLING - Fixed to never be null
        $tagsInput  = $this->request->getPost('tags');
        $tagsString = is_string($tagsInput) ? trim($tagsInput) : '';
        $tagsArray  = $tagsString !== '' ? array_filter(array_map('trim', explode(',', $tagsString))) : [];

        $heroImageUrl = $post['hero_image_url'];
        $uploadedFile = $this->request->getFile('hero_image_file');

        if ($uploadedFile && $uploadedFile->isValid() && !$uploadedFile->hasMoved()) {
            $newName = $uploadedFile->getRandomName();

            if (env('FILESYSTEM_DRIVER') === 's3') {
                try {
                    $s3 = new S3Client([
                        'version'     => 'latest',
                        'region'      => env('AWS_REGION', 'us-east-1'),
                        'endpoint'    => env('AWS_ENDPOINT'),
                        'credentials' => [
                            'key'    => env('AWS_ACCESS_KEY_ID'),
                            'secret' => env('AWS_SECRET_ACCESS_KEY'),
                        ],
                    ]);

                    $key = 'blog/' . $newName;
                    $s3->putObject([
                        'Bucket'      => env('AWS_BUCKET'),
                        'Key'         => $key,
                        'Body'        => fopen($uploadedFile->getTempName(), 'rb'),
                        'ACL'         => 'public-read',
                        'ContentType' => $uploadedFile->getMimeType(),
                    ]);

                    $baseUrl = rtrim(env('AWS_URL'), '/');
                    $heroImageUrl = $baseUrl . '/' . $key;
                } catch (AwsException $e) {
                    return redirect()->back()->withInput()->with('error', 'S3 Update Error: ' . $e->getAwsErrorMessage());
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
            'tags'           => $tagsArray, // Sent as array to prevent Null Exception
            'is_published'   => $isPublished ? 1 : 0,
            'published_at'   => $isPublished ? ($post['published_at'] ?? date('Y-m-d H:i:s')) : null,
        ];

        $this->blogModel->update($id, $data);
        return redirect()->to('/admin/blogs')->with('success', 'Post updated successfully.');
    }

    public function delete($id)
    {
        $this->blogModel->delete($id);
        return redirect()->to('/admin/blogs')->with('success', 'Post deleted successfully.');
    }
}