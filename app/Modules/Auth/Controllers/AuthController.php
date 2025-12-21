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
    // ✅ If already logged in, reset first (Shield safety)
    if (auth()->loggedIn()) {
        auth()->logout();
        session()->regenerate(true);
    }

    $credentials = [
        'email'    => $this->request->getPost('login'),
        'password' => $this->request->getPost('password'),
    ];

    $auth = service('auth');

    // ❌ Login failed
    if (! $auth->attempt($credentials)) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'Invalid email or password');
    }

    // ✅ EXTRA SAFETY: ensure user exists
    $user = auth()->user();

    if (! $user) {
        auth()->logout();
        session()->regenerate(true);

        return redirect()->back()
            ->with('error', 'Authentication failed. Please try again.');
    }

    // ✅ App-level session flags (temporary bridge)
    session()->set([
        'user_id'    => $user->id,
        'username'   => $user->username,
        'isLoggedIn' => true,
        'role'       => 'admin',
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
