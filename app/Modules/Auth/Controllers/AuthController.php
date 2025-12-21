<?php

namespace App\Modules\Auth\Controllers;

use App\Controllers\BaseController;

class AuthController extends BaseController
{
    public function login()
    {
        return view('App\Modules\Auth\Views\login');
    }

   public function attemptLogin()
{
    $auth = service('auth');

    // ✅ Shield safety: logout existing session
    if ($auth->loggedIn()) {
        $auth->logout();
        session()->regenerate(true);
    }

    $credentials = [
        'email'    => $this->request->getPost('login'),
        'password' => $this->request->getPost('password'),
    ];

    if (! $auth->attempt($credentials)) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'Invalid login credentials');
    }

    $user = $auth->user();

    session()->set([
        'user_id'    => $user->id,
        'username'   => $user->username,
        'isLoggedIn' => true,
        'role'       => 'admin',
        'login_time' => time(), // ✅ needed for 6-hour timeout
    ]);

    return redirect()->to('/admin/blogs')
        ->with('success', 'Welcome back!');
}



    public function logout()
{
    // Shield logout
    auth()->logout();

    // Destroy app session
    session()->destroy();

    return redirect()->to('/login')
        ->with('success', 'You have been logged out.');
}

}
