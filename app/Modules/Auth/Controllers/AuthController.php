<?php

namespace App\Modules\Auth\Controllers;

use App\Controllers\BaseController;

class AuthController extends BaseController
{
    public function login()
    {
        // REMOVE THIS ENTIRE BLOCK
        // if (auth()->loggedIn()) {
        //     return redirect()->to('/admin/blogs');
        // }

        // Just always show the login form
        // Shield's filter will redirect here when needed
        return view('App\Modules\Auth\Views\login');
    }

    public function attemptLogin()
    {
        if (auth()->loggedIn()) {
            auth()->logout();
        }

        $credentials = [
            'email'    => $this->request->getPost('login'),
            'password' => $this->request->getPost('password'),
        ];

        $result = auth()->attempt($credentials, true);

        if (! $result->isOK()) {
            return redirect()->back()
                ->withInput()
                ->with('error', $result->reason());
        }

        // Shield automatically redirects to /admin/blogs now (from Auth config)
        return redirect()->to('/admin/blogs')
            ->with('success', 'Welcome back, ' . esc(auth()->user()->username) . '!');
    }

    public function logout()
    {
        auth()->logout();
        session()->destroy();
        return redirect()->to('/login')
            ->with('success', 'You have been logged out.');
    }
}