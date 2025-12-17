<?php

namespace App\Modules\Home\Controllers;

use App\Controllers\BaseController;
use App\Modules\Blog\Models\BlogModel;
use App\Modules\Video\Models\VideoModel;

class HomeController extends BaseController
{
    public function index()
    {
        $blogModel  = new BlogModel();
        $videoModel = new VideoModel();

        return view('App\Modules\Home\Views\home', [
            'posts'  => $blogModel->getPublishedPosts(),
            'videos' => $videoModel->getLatestVideos(6),
        ]);
    }
}
