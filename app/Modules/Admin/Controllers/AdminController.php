<?php

namespace App\Modules\Admin\Controllers;

use App\Controllers\BaseController;
use App\Modules\Admin\Models\AdminBlogModel;
use CodeIgniter\Files\File;

// Import the AWS S3 classes directly
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class AdminController extends BaseController
{
    protected AdminBlogModel $blogModel;

    public function __construct()
    {
        helper(['url', 'text', 'filesystem']);
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

    /**
     * Store new post
     */
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
                    // Direct S3 Client Initialization
                    $s3 = new S3Client([
                        'version'     => 'latest',
                        'region'      => env('AWS_REGION', 'us-east-1'),
                        'endpoint'    => env('AWS_ENDPOINT'), // e.g., https://atl1.digitaloceanspaces.com
                        'credentials' => [
                            'key'    => env('AWS_ACCESS_KEY_ID'),
                            'secret' => env('AWS_SECRET_ACCESS_KEY'),
                        ],
                        'use_path_style_endpoint' => false,
                    ]);

                    $bucket = env('AWS_BUCKET');
                    $key    = 'blog/' . $newName;

                    // Direct Upload
                    $s3->putObject([
                        'Bucket'      => $bucket,
                        'Key'         => $key,
                        'Body'        => fopen($uploadedFile->getTempName(), 'rb'),
                        'ACL'         => 'public-read',
                        'ContentType' => $uploadedFile->getMimeType(),
                    ]);

                    $baseUrl = rtrim(env('AWS_URL'), '/');
                    $heroImageUrl = $baseUrl . '/' . $key;

                } catch (AwsException $e) {
                    log_message('error', 'S3 Direct Upload Error: ' . $e->getMessage());
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
            'content_html'   => $this->request->getPost('content'),
            'hero_image_url' => $heroImageUrl,
            'is_published'   => $this->request->getPost('status') === 'published' ? 1 : 0,
            'published_at'   => date('Y-m-d H:i:s'),
        ];

        $this->blogModel->insert($data);
        return redirect()->to('/admin/blogs')->with('success', 'Post created successfully.');
    }

    /**
     * Update post
     */
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

        $this->blogModel->update($id, [
            'title'          => $this->request->getPost('title'),
            'hero_image_url' => $heroImageUrl,
            'content_html'   => $this->request->getPost('content'),
        ]);

        return redirect()->to('/admin/blogs')->with('success', 'Post updated successfully.');
    }

    public function delete($id)
    {
        $this->blogModel->delete($id);
        return redirect()->to('/admin/blogs')->with('success', 'Post deleted.');
    }
}