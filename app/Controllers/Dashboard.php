<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
	public function index()
	{
		return view('logistic_dashboard');
	}

	public function admin()
	{
		return view('admin_dashboard');
	}

	public function supplier()
	{
		return view('supplier_dashboard');
	}

	public function inventory()
	{
		return view('inventory_dashboard');
	}

	public function branch()
	{
		return view('branch_dashboard');
	}

	public function franchise()
	{
		return view('franchise_dashboard');
	}
}


