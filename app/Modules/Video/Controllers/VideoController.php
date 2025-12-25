<?php
namespace App\Modules\Video\Controllers;

use App\Controllers\BaseController;
use App\Modules\Video\Models\VideoModel;

class VideoController extends BaseController
{
    protected $videoModel;

    public function __construct()
    {
        $this->videoModel = new VideoModel();
    }

    public function index()
    {
        $data = [
            'videos' => $this->videoModel->getLatestVideos(),
            'title'  => 'Videos',
        ];

        return view('App\Modules\Video\Views\index', $data);
    }

    public function show($slug)
    {
        $video = $this->videoModel
            ->where('slug', $slug)
            ->where('is_published', 1)
            ->first();

        if (!$video) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Video not found');
        }

        return redirect()->to($video['external_url']);
    }
}
