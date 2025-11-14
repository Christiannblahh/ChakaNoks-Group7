<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Inventory extends BaseController
{
    public function settings()
    {
        // Get user role from session
        $session = session();
        $userRole = $session->get('role');

        // Check if user is System Admin
        if ($userRole === 'System Admin') {
            // Load view for admin settings
            return view('inventory/settings_admin');
        } else {
            // Show access denied message
            return view('inventory/access_denied');
        }
    }
}
