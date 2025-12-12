<?php

namespace Modules\Home\Controllers;

use App\Controllers\BaseController;

class Home extends BaseController
{
    public function index()
    {
        return view('Modules\Home\Views\index');
    }
}
