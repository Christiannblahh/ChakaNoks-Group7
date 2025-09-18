<?php

namespace App\Controllers;

class Login extends BaseController
{
	public function index()
	{
		return view('login');
	}

	public function authenticate()
	{
		$request = service('request');
		$session = session();

		$emailOrUsername = trim($request->getPost('username'));
		$password = (string) $request->getPost('password');

		if ($emailOrUsername === '' || $password === '') {
			return redirect()->back()->with('error', 'Username and password are required.');
		}

		$db = \Config\Database::connect();
		$builder = $db->table('users');
		$user = $builder
			->groupStart()
				->where('email', $emailOrUsername)
			->groupEnd()
			->get()->getRowArray();

		if (!$user || !password_verify($password, $user['password'])) {
			return redirect()->back()->with('error', 'Invalid credentials.');
		}

		$session->set([
			'user_id'   => $user['user_id'],
			'email'     => $user['email'],
			'role'      => $user['role'],
			'branch_id' => $user['branch_id'] ?? null,
			'logged_in' => true,
		]);

		return redirect()->to($this->routeForRole($user['role']));
	}

	private function routeForRole(string $role): string
	{
		switch ($role) {
			case 'Central Admin':
			case 'System Admin':
				return site_url('admin_dashboard');
			case 'Supplier':
				return site_url('supplier_dashboard');
			case 'Inventory Staff':
				return site_url('inventory_dashboard');
			case 'Branch Manager':
				return site_url('branch_dashboard');
			case 'Franchise Manager':
				return site_url('franchise_dashboard');
			case 'Logistics Coordinator':
			default:
				return site_url('logistic_dashboard');
		}
	}
}


