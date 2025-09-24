<?php

namespace App\Controllers;

class Pages extends BaseController
{
	public function index()
	{
		return redirect()->to(site_url('/'));
	}

	public function users()
	{
        return view('pages/users');
	}

	public function backups()
	{
        return view('pages/backups');
	}

	public function settings()
	{
        return view('pages/settings');
	}

	public function shipments()
	{
		return view('pages/shipments');
	}

	public function routes()
	{
		return view('pages/routes');
	}

	public function suppliers()
	{
		return view('pages/suppliers');
	}

	public function notifications()
	{
		return view('pages/notifications');
	}

	public function messages()
	{
		return view('pages/messages');
	}

    // Branch Manager pages
    public function branchRequests()
    {
        return view('branch/requests');
    }

    public function branchTransfers()
    {
        return view('branch/transfers');
    }

    public function branchSettings()
    {
        return view('branch/settings');
    }

    // Actions
    public function createUser()
    {
        $request = service('request');
        $session = session();

        $name = trim((string) $request->getPost('name'));
        $email = trim((string) $request->getPost('email'));
        $role = trim((string) $request->getPost('role'));

        if ($name === '' || $email === '' || $role === '') {
            return redirect()->back()->with('error', 'All fields are required.');
        }

        // Placeholder: integrate with database later
        $session->setFlashdata('success', 'User created (simulation): ' . esc($name) . ' — ' . esc($role));
        return redirect()->to(site_url('pages/users'));
    }

    public function initiateBackup()
    {
        $session = session();
        // Placeholder for actual backup logic
        $session->setFlashdata('success', 'Backup started (simulation).');
        return redirect()->to(site_url('pages/backups'));
    }

    public function restoreBackup()
    {
        $request = service('request');
        $session = session();

        $backupId = trim((string) $request->getPost('backup_id'));
        if ($backupId === '') {
            return redirect()->back()->with('error', 'Select a backup to restore.');
        }

        // Placeholder for restore logic
        $session->setFlashdata('success', 'Restore queued for backup #' . esc($backupId) . ' (simulation).');
        return redirect()->to(site_url('pages/backups'));
    }

    public function updateSettings()
    {
        $request = service('request');
        $session = session();

        $appName = trim((string) $request->getPost('app_name'));
        $timezone = trim((string) $request->getPost('timezone'));

        if ($appName === '' || $timezone === '') {
            return redirect()->back()->with('error', 'All fields are required.');
        }

        // Placeholder: persist to config or DB later
        $session->setFlashdata('success', 'Settings updated (simulation): ' . esc($appName) . ' — ' . esc($timezone));
        return redirect()->to(site_url('pages/settings'));
    }

    // Branch Manager actions (simulated)
    public function branchCreateRequest()
    {
        $request = service('request');
        $session = session();
        $item = trim((string) $request->getPost('item'));
        $quantity = (int) $request->getPost('quantity');
        if ($item === '' || $quantity <= 0) {
            return redirect()->back()->with('error', 'Item and quantity are required.');
        }
        $session->setFlashdata('success', 'Purchase request submitted (simulation).');
        return redirect()->to(site_url('branch/requests'));
    }

    public function branchCreateTransfer()
    {
        $request = service('request');
        $session = session();
        $from = trim((string) $request->getPost('from_branch'));
        $to = trim((string) $request->getPost('to_branch'));
        $item = trim((string) $request->getPost('item'));
        $quantity = (int) $request->getPost('quantity');
        if ($from === '' || $to === '' || $item === '' || $quantity <= 0) {
            return redirect()->back()->with('error', 'All fields are required.');
        }
        $session->setFlashdata('success', 'Transfer request sent (simulation).');
        return redirect()->to(site_url('branch/transfers'));
    }

	public function logout()
	{
		$session = session();
		$session->destroy();
		return redirect()->to(site_url('login'));
	}
}



