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
        return view('admin.admin-dashboard');
    }
    // pmo dashboard
    public function pmoDashboard()
    {
        return view('pmo.pmo-dashboard');      
}
    // fco dashboard
    public function fcoDashboard()
    {
        return view('fco.fco-dashboard');      
}
    // cso dashboard
    public function csoDashboard()
    {
        return view('cso.cso-dashboard');      
}
    // pm dashboard
    public function pmDashboard()
    {
        return view('pm.pm-dashboard');      
}
}
