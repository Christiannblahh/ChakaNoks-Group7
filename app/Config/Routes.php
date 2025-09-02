<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Login::index');
// Login page route
$routes->get('login', 'Login::index');
// Dashboard (UI only)
$routes->get('logistic_dashboard', 'Dashboard::index');
// Admin UI-only dashboards
$routes->get('admin_dashboard', 'Dashboard::admin');
$routes->get('supplier_dashboard', 'Dashboard::supplier');
$routes->get('inventory_dashboard', 'Dashboard::inventory');
$routes->get('branch_dashboard', 'Dashboard::branch');
// Franchise/Central Office Admin Dashboard
$routes->get('franchise_dashboard', 'Dashboard::franchise');
