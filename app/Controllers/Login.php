<?php

namespace App\Controllers;

use App\Models\AdminModel;

use CodeIgniter\Controller;
use CodeIgniter\Session\Session;

class Login extends Controller
{
    public function index()
    {
        if (session()->get('logged_in')) 
        {
            return redirect()->to('/dashboard')->send();
        } else {
            return view('login');
        }
    }

    public function auth()
    {
        $session = session();
        $adminModel = new AdminModel();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $admin = $adminModel->where('username', $username)->first();

        if ($admin) {
            if (password_verify($password, $admin['password'])) 
            {
                $session->set([
                    'username' => $admin['username'],
                    'role' => $admin['role'],
                    'region' => $admin['region'],
                    'logged_in' => true,
                ]);
                $session->setFlashdata('success', 'Login Success.');
                return redirect()->to('/dashboard');
            } else {
                $session->setFlashdata('error', 'Password salah.');
                return redirect()->to('/login');
            }
        } else {
            $session->setFlashdata('error', 'Username tidak ditemukan.');
            return redirect()->to('/login');
        }
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/login');
    }
}
