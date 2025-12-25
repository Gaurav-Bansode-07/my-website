<?php

namespace App\Modules\Admin\Controllers;

use App\Controllers\BaseController;
use App\Modules\Admin\Models\AdminBlogModel;
use CodeIgniter\Files\File;
// Required for the S3 Fix
use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use App\Modules\Video\Models\VideoModel;

class AdminController extends BaseController
{
    protected AdminBlogModel $blogModel;
	protected VideoModel $videoModel;
	
    public function __construct()
    {
        helper(['url', 'text']);
        $this->blogModel = new AdminBlogModel();
		$this->videoModel = new VideoModel();
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
            if (!str_starts_with($uploadedFile->getMimeType(), 'image/')) {
                return redirect()->back()->withInput()->with('error', 'Only image files are allowed.');
            }

            if ($uploadedFile->getSize() > 2 * 1024 * 1024) {
                return redirect()->back()->withInput()->with('error', 'Image must be less than 2MB.');
            }

            $newName = $uploadedFile->getRandomName();

            // Production: Upload to S3 (Spaces) - FIXED VERSION
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
                        'use_path_style_endpoint' => false,
                    ]);

                    $key = 'blog/' . $newName;

                    $s3->putObject([
                        'Bucket'      => env('AWS_BUCKET'),
                        'Key'         => $key,
                        'Body'        => fopen($uploadedFile->getTempName(), 'rb'),
                        'ACL'         => 'public-read', // Ensures link works
                        'ContentType' => $uploadedFile->getMimeType(),
                    ]);

                    $heroImageUrl = rtrim(env('AWS_URL'), '/') . '/' . $key;
                } catch (AwsException $e) {
                    log_message('error', 'S3 upload failed: ' . $e->getMessage());
                    return redirect()->back()->withInput()->with('error', 'Image upload failed: ' . $e->getAwsErrorMessage());
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
            'tags' => !empty($tagsArray) ? $tagsArray : [],
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
            if (!str_starts_with($uploadedFile->getMimeType(), 'image/')) {
                return redirect()->back()->withInput()->with('error', 'Only image files are allowed.');
            }

            if ($uploadedFile->getSize() > 2 * 1024 * 1024) {
                return redirect()->back()->withInput()->with('error', 'Image must be less than 2MB.');
            }

            $newName = $uploadedFile->getRandomName();

            // Production: S3 - FIXED VERSION
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

                    $heroImageUrl = rtrim(env('AWS_URL'), '/') . '/' . $key;
                } catch (AwsException $e) {
                    log_message('error', 'S3 update failed: ' . $e->getMessage());
                    return redirect()->back()->withInput()->with('error', 'Image upload failed: ' . $e->getAwsErrorMessage());
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
            'tags' => !empty($tagsArray) ? $tagsArray : [],
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
	
	
	/**
 * Video list page
 */
public function videos()
{
    $data['videos'] = $this->videoModel
        ->orderBy('published_at', 'DESC')
        ->orderBy('id', 'DESC')
        ->findAll();

    return view('App\Modules\Admin\Views\video_list', $data); // We'll create this view next
}

/**
 * Video create form
 */
public function videoCreate()
{
    return view('App\Modules\Admin\Views\video_create');
}

/**
 * Store new video post
 */

public function videoStore()
{
    $title = trim($this->request->getPost('title') ?? '');
    if ($title === '') {
        return redirect()->back()->withInput()->with('error', 'Title is required.');
    }

    $externalUrl = trim($this->request->getPost('external_url') ?? '');
    if ($externalUrl === '' || !filter_var($externalUrl, FILTER_VALIDATE_URL)) {
        return redirect()->back()->withInput()->with('error', 'Valid External Video URL is required.');
    }

    $isPublished = (int)$this->request->getPost('is_published') === 1;

    $tagsInput = $this->request->getPost('tags');
    $tagsString = is_string($tagsInput) ? trim($tagsInput) : '';
    $tagsArray = $tagsString !== '' ? array_filter(array_map('trim', explode(',', $tagsString))) : [];

    $heroImageUrl = null;
    $uploadedFile = $this->request->getFile('hero_image_file');
    if ($uploadedFile && $uploadedFile->isValid() && !$uploadedFile->hasMoved()) {
        if (!str_starts_with($uploadedFile->getMimeType(), 'image/')) {
            return redirect()->back()->withInput()->with('error', 'Only image files are allowed.');
        }
        if ($uploadedFile->getSize() > 2 * 1024 * 1024) {
            return redirect()->back()->withInput()->with('error', 'Image must be less than 2MB.');
        }
        $newName = $uploadedFile->getRandomName();

        if (env('FILESYSTEM_DRIVER') === 's3') {
            try {
                $s3 = new S3Client([
                    'version' => 'latest',
                    'region' => env('AWS_REGION', 'us-east-1'),
                    'endpoint' => env('AWS_ENDPOINT'),
                    'credentials' => [
                        'key' => env('AWS_ACCESS_KEY_ID'),
                        'secret' => env('AWS_SECRET_ACCESS_KEY'),
                    ],
                ]);
                $key = 'videos/' . $newName;
                $s3->putObject([
                    'Bucket' => env('AWS_BUCKET'),
                    'Key' => $key,
                    'Body' => fopen($uploadedFile->getTempName(), 'rb'),
                    'ACL' => 'public-read',
                    'ContentType' => $uploadedFile->getMimeType(),
                ]);
                $heroImageUrl = rtrim(env('AWS_URL'), '/') . '/' . $key;
            } catch (AwsException $e) {
                return redirect()->back()->withInput()->with('error', 'Image upload failed.');
            }
        } else {
            $uploadPath = FCPATH . 'uploads/videos/';
            if (!is_dir($uploadPath)) mkdir($uploadPath, 0755, true);
            $uploadedFile->move($uploadPath, $newName);
            $heroImageUrl = 'uploads/videos/' . $newName;
        }
    }

    $data = [
        'title'        => $title,
        'slug'         => url_title($title, '-', true),
        'subtitle'     => $this->request->getPost('subtitle'),
        'summary'      => $this->request->getPost('summary'),
        'external_url' => $externalUrl,
        'hero_image_url' => $heroImageUrl,
        'category'     => $this->request->getPost('category'),
        'tags'         => $tagsArray,
        'is_published' => $isPublished ? 1 : 0,
        'published_at' => $isPublished ? date('Y-m-d H:i:s') : null,
    ];

    $this->videoModel->insert($data);

    return redirect()->to('/admin/videos')->with('success', 'Video post created successfully.');
}

/**
 * Video Edit Form
 */
public function videoEdit($id)
{
    $video = $this->videoModel->find($id);
    if (!$video) {
        return redirect()->to('/admin/videos')->with('error', 'Video not found.');
    }
    return view('App\Modules\Admin\Views\video_edit', ['video' => $video]);
}

/**
 * Update Video Post
 */
public function videoUpdate($id)
{
    $video = $this->videoModel->find($id);
    if (!$video) {
        return redirect()->to('/admin/videos')->with('error', 'Video not found.');
    }

    $title = trim($this->request->getPost('title') ?? '');
    if ($title === '') {
        return redirect()->back()->withInput()->with('error', 'Title is required.');
    }

    $externalUrl = trim($this->request->getPost('external_url') ?? '');
    if ($externalUrl === '' || !filter_var($externalUrl, FILTER_VALIDATE_URL)) {
        return redirect()->back()->withInput()->with('error', 'Valid External Video URL is required.');
    }

    $isPublished = (int)$this->request->getPost('is_published') === 1;

    // Tags
    $tagsInput = $this->request->getPost('tags');
    $tagsString = is_string($tagsInput) ? trim($tagsInput) : '';
    $tagsArray = $tagsString !== '' ? array_filter(array_map('trim', explode(',', $tagsString))) : [];

    // Hero Image - keep existing if no new upload
    $heroImageUrl = $video['hero_image_url'];
    $uploadedFile = $this->request->getFile('hero_image_file');

    if ($uploadedFile && $uploadedFile->isValid() && !$uploadedFile->hasMoved()) {
        if (!str_starts_with($uploadedFile->getMimeType(), 'image/')) {
            return redirect()->back()->withInput()->with('error', 'Only image files are allowed.');
        }
        if ($uploadedFile->getSize() > 2 * 1024 * 1024) {
            return redirect()->back()->withInput()->with('error', 'Image must be less than 2MB.');
        }

        $newName = $uploadedFile->getRandomName();

        if (env('FILESYSTEM_DRIVER') === 's3') {
            try {
                $s3 = new S3Client([
                    'version' => 'latest',
                    'region' => env('AWS_REGION', 'us-east-1'),
                    'endpoint' => env('AWS_ENDPOINT'),
                    'credentials' => [
                        'key' => env('AWS_ACCESS_KEY_ID'),
                        'secret' => env('AWS_SECRET_ACCESS_KEY'),
                    ],
                ]);
                $key = 'videos/' . $newName;
                $s3->putObject([
                    'Bucket' => env('AWS_BUCKET'),
                    'Key' => $key,
                    'Body' => fopen($uploadedFile->getTempName(), 'rb'),
                    'ACL' => 'public-read',
                    'ContentType' => $uploadedFile->getMimeType(),
                ]);
                $heroImageUrl = rtrim(env('AWS_URL'), '/') . '/' . $key;
            } catch (AwsException $e) {
                return redirect()->back()->withInput()->with('error', 'Image upload failed.');
            }
        } else {
            $uploadPath = FCPATH . 'uploads/videos/';
            if (!is_dir($uploadPath)) mkdir($uploadPath, 0755, true);
            $uploadedFile->move($uploadPath, $newName);
            $heroImageUrl = 'uploads/videos/' . $newName;
        }
    }

    $data = [
        'title'         => $title,
        'slug'          => url_title($title, '-', true),
        'subtitle'      => $this->request->getPost('subtitle'),
        'summary'       => $this->request->getPost('summary'),
        'external_url'  => $externalUrl,
        'hero_image_url'=> $heroImageUrl,
        'category'      => $this->request->getPost('category'),
        'tags'          => $tagsArray,
        'is_published'  => $isPublished ? 1 : 0,
        'published_at'  => $isPublished ? ($video['published_at'] ?? date('Y-m-d H:i:s')) : null,
    ];

    $this->videoModel->update($id, $data);
    return redirect()->to('/admin/videos')->with('success', 'Video updated successfully.');
}

/**
 * Delete Video Post
 */
public function videoDelete($id)
{
    $video = $this->videoModel->find($id);
    if (!$video) {
        return redirect()->to('/admin/videos')->with('error', 'Video not found.');
    }

    $this->videoModel->delete($id);
    return redirect()->to('/admin/videos')->with('success', 'Video deleted successfully.');
}
}