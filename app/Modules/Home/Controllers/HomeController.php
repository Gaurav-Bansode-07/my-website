<?php

namespace App\Modules\Home\Controllers;

use App\Controllers\BaseController;
use App\Modules\Blog\Models\BlogModel;
use App\Modules\Video\Models\VideoModel;

class HomeController extends BaseController
{
	
    public function index()
    {
		
        $blogModel = new BlogModel();

        $posts = $blogModel->getHomepagePosts(1); // 👈 force ONE post

        // 🔍 DEBUG – TEMPORARY
        // dd($posts);

        return view('App\Modules\Home\Views\home', [
            'posts' => $posts,
        ]);
		
		
		$videoModel = new VideoModel();
		$latestVideos = $videoModel->getLatestVideos(10);

		return view('Home/home', [
			'posts'        => $posts,          // existing blog posts
			'latestVideos' => $latestVideos,   // new
		]);

		
    }
}
