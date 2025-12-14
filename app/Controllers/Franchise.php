<?php

namespace App\Controllers;

class Franchise extends BaseController
{
    public function dashboard()
    {
        return view('franchise/dashboard');
    }

    public function applications()
    {
        return view('franchise/applications');
    }

    public function allocation()
    {
        return view('franchise/allocation');
    }

    public function inventory()
    {
        return view('franchise/inventory');
    }

    public function orders()
    {
        return view('franchise/orders');
    }

    public function reports()
    {
        return view('franchise/reports');
    }

    public function settings()
    {
        return view('franchise/settings');
    }
}
