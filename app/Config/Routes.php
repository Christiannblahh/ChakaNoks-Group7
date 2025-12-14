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
$routes->get('pages/users/edit/(:num)', 'Pages::editUser/$1');
$routes->post('pages/users/edit/(:num)', 'Pages::editUser/$1');
$routes->post('pages/users/restore/(:num)', 'Pages::restoreUser/$1');
$routes->post('pages/users/delete/(:num)', 'Pages::deleteUser/$1');
$routes->get('pages/backups', 'Pages::backups');
$routes->get('pages/settings', 'Pages::settings');
$routes->get('pages/shipments', 'Pages::shipments');
$routes->get('pages/routes', 'Pages::routes');
$routes->get('pages/suppliers', 'Pages::suppliers');
$routes->get('pages/notifications', 'Pages::notifications');
$routes->get('pages/messages', 'Pages::messages');
$routes->get('pages/inventory', 'Pages::inventory');
$routes->get('pages/reports', 'Pages::reports');
$routes->get('pages/stock_records', 'Pages::stockRecords');
$routes->get('pages/purchase-approvals', 'Pages::purchaseApprovals');
$routes->get('pages/purchase-orders', 'Pages::purchaseOrders');

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

// Inventory AJAX routes
$routes->get('inventory/get', 'Pages::getInventory');
$routes->post('inventory/add', 'Pages::addInventoryItem');
$routes->post('inventory/update', 'Pages::updateInventoryItem');
$routes->post('inventory/delete', 'Pages::deleteInventoryItem');
$routes->get('inventory/low-stock', 'Pages::getLowStockAlerts');
$routes->get('inventory/settings', 'Inventory::settings');

// Role-based settings routes
$routes->get('settings/logistics', 'Pages::logisticsSettings');
$routes->get('settings/inventory', 'Pages::inventorySettings');
$routes->get('settings/branch', 'Pages::branchSettings');
$routes->get('settings/franchise', 'Pages::franchiseSettings');
$routes->get('settings/supplier', 'Pages::supplierSettings');

// ===== PURCHASE REQUEST & ORDER ROUTES =====
// Purchase Request Routes
$routes->post('purchasing/requests/create', 'Purchasing::createRequest');
$routes->get('purchasing/requests/pending', 'Purchasing::getPendingRequests');
$routes->get('purchasing/requests/branch/(:num)', 'Purchasing::getRequestsByBranch/$1');
$routes->get('purchasing/requests/(:num)', 'Purchasing::getRequest/$1');
$routes->post('purchasing/requests/(:num)/approve', 'Purchasing::approveRequest/$1');
$routes->post('purchasing/requests/(:num)/deny', 'Purchasing::denyRequest/$1');

// Purchase Order Routes
$routes->get('purchasing/orders', 'Purchasing::getOrders');
$routes->get('purchasing/orders/pending', 'Purchasing::getPendingOrders');
$routes->get('purchasing/orders/(:num)', 'Purchasing::getOrder/$1');
$routes->post('purchasing/orders/(:num)/status', 'Purchasing::updateOrderStatus/$1');

// Supplier Routes - Specific routes first, then parameterized
$routes->post('purchasing/suppliers/create', 'Purchasing::createSupplier');
$routes->get('purchasing/suppliers', 'Purchasing::getSuppliers');
$routes->get('purchasing/suppliers/(:num)/orders', 'Purchasing::getSupplierOrders/$1');
$routes->get('purchasing/suppliers/(:num)/stats', 'Purchasing::getSupplierStats/$1');
$routes->post('purchasing/suppliers/(:num)/update', 'Purchasing::updateSupplier/$1');
$routes->get('purchasing/suppliers/(:num)', 'Purchasing::getSupplier/$1');

// Purchasing Statistics
$routes->get('purchasing/stats', 'Purchasing::getStats');

// ===== DELIVERY ROUTES =====
$routes->post('delivery/create', 'Delivery::create');
$routes->get('delivery', 'Delivery::index');
$routes->get('delivery/(:num)', 'Delivery::show/$1');
$routes->post('delivery/mark-delivered/(:num)', 'Delivery::markDelivered/$1');
$routes->post('delivery/(:num)/status', 'Delivery::updateStatus/$1');
$routes->post('delivery/schedule', 'Delivery::scheduleDelivery');
$routes->get('delivery/status/(:any)', 'Delivery::getByStatus/$1');
$routes->get('delivery/pending', 'Delivery::getPending');
$routes->get('delivery/overdue', 'Delivery::getOverdue');
$routes->get('delivery/branch/(:num)', 'Delivery::getByBranch/$1');
$routes->get('delivery/stats', 'Delivery::getStats');
