<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Login::index');
// Login page route
$routes->get('login', 'Login::index');
// Login submit
$routes->post('login', 'Login::authenticate');
// Dashboard (UI only)
$routes->get('logistic_dashboard', 'Dashboard::index');
// Admin UI-only dashboards
$routes->get('admin_dashboard', 'Dashboard::admin');
$routes->get('supplier_dashboard', 'Dashboard::supplier');
$routes->get('inventory_dashboard', 'Dashboard::inventory');
$routes->get('branch_dashboard', 'Dashboard::branch');
// Franchise/Central Office Admin Dashboard
$routes->get('franchise_dashboard', 'Dashboard::franchise');

// Navigation pages
$routes->get('pages/users', 'Pages::users');
$routes->get('pages/backups', 'Pages::backups');
$routes->get('pages/settings', 'Pages::settings');
$routes->get('pages/shipments', 'Pages::shipments');
$routes->get('pages/routes', 'Pages::routes');
$routes->get('pages/suppliers', 'Pages::suppliers');
$routes->get('pages/notifications', 'Pages::notifications');
$routes->get('pages/messages', 'Pages::messages');
$routes->get('pages/inventory', 'Pages::inventory');
$routes->get('pages/reports', 'Pages::reports');

// Logout
$routes->get('logout', 'Pages::logout');

// Actions (POST)
$routes->post('pages/users/create', 'Pages::createUser');
$routes->post('pages/backups/initiate', 'Pages::initiateBackup');
$routes->post('pages/backups/restore', 'Pages::restoreBackup');
$routes->post('pages/settings/update', 'Pages::updateSettings');

// Branch manager pages
$routes->get('branch/requests', 'Pages::branchRequests');
$routes->get('branch/transfers', 'Pages::branchTransfers');
$routes->get('branch/settings', 'Pages::branchSettings');
$routes->post('branch/requests/create', 'Pages::branchCreateRequest');
$routes->post('branch/transfers/create', 'Pages::branchCreateTransfer');