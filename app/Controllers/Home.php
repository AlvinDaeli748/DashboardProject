<?php

namespace App\Controllers;

use App\Models\AdminModel;

class Home extends BaseController
{
    public function __construct()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->send();
        }
    }

    public function index()
    {
        if (!session()->get('logged_in')) 
        {
            return redirect()->to('/login')->send();
        } else {
            $adminModel = new AdminModel();
            $data['users'] = $adminModel->findAll();
    
            return view('dashboard', $data);
        }
    }
}
