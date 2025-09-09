<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ViewController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function users()
    {
        return view('auth-user');
    }

    public function form()
    {   
        return view('form');
    }

    // Admin Dashboard
    public function adminDashboard()
{
    return view('admin.dashboard');
}    
}
