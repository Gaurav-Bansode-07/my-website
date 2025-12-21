<?php

namespace App\Modules\Auth\Controllers;

use App\Controllers\BaseController;

class AuthController extends BaseController
{
    public function login()
    {
        // If already logged in as admin, redirect to admin panel
        if (session('isLoggedIn') && session('role') === 'admin') {
            return redirect()->to('/admin/blogs');
        }

        return view('App\Modules\Auth\Views\login');
    }

    public function attemptLogin()
    {
        $db = db_connect();

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $db->table('users')
                   ->where('email', $email)
                   ->get()
                   ->getRowArray();

        if ($user && password_verify($password, $user['password'])) {
            session()->set([
                'user_id'    => $user['id'],
                'email'      => $user['email'],
                'role'       => $user['role'],
                'isLoggedIn' => true,
            ]);

            if ($user['role'] === 'admin') {
                return redirect()->to('/admin/blogs')->with('success', 'Welcome back, Admin!');
            }

            return redirect()->to('/')->with('success', 'Logged in successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Invalid email or password');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'You have been logged out');
    }
}