# ChakaNoks System - Test Credentials

## Default Test Users (Password: Password123!)

### 1. Central Admin (Can approve purchase requests)
- **Email:** central.admin@example.com
- **Password:** Password123!
- **Role:** Central Admin
- **Access:** Can view and approve/deny purchase requests

### 2. System Admin (Full system access)
- **Email:** system.admin@example.com
- **Password:** Password123!
- **Role:** System Admin
- **Access:** Full system access, can approve requests

### 3. Branch Manager (Can create purchase requests)
- **Email:** branch.manager@example.com
- **Password:** Password123!
- **Role:** Branch Manager
- **Access:** Can create purchase requests from branch

### 4. Inventory Staff (Can create purchase requests)
- **Email:** inventory.staff@example.com
- **Password:** Password123!
- **Role:** Inventory Staff
- **Access:** Can create and view purchase requests

### 5. Logistics Coordinator (Can manage deliveries)
- **Email:** logistics.coord@example.com
- **Password:** Password123!
- **Role:** Logistics Coordinator
- **Access:** Can schedule and manage deliveries

### 6. Supplier (Can view orders and update delivery status)
- **Email:** supplier@example.com
- **Password:** Password123!
- **Role:** Supplier
- **Access:** Can view orders and update delivery status

### 7. Franchise Manager (Can manage franchise operations)
- **Email:** franchise.manager@example.com
- **Password:** Password123!
- **Role:** Franchise Manager
- **Access:** Can manage franchise applications and allocations

## How to Test Purchase Approvals

### Step 1: Create a Purchase Request
1. Log in as **Branch Manager** (branch.manager@example.com)
2. Go to "Branch" menu → "Purchase Requests"
3. Click "Create Purchase Request"
4. Add items and submit

### Step 2: Approve the Request
1. Log out and log in as **Central Admin** (central.admin@example.com)
2. Go to "Admin" menu → "Purchase Approvals"
3. Select a supplier for the pending request
4. Click "Approve & Create Order"
5. The purchase order will be created

### Step 3: Track the Order
1. Central Admin can view the order in "Purchase Orders" page
2. Branch Manager can see the order status in their dashboard

## Available Functionality

✅ **Inventory Management**
- Add/Edit suppliers
- View inventory levels
- Create purchase requests

✅ **Purchase Workflow**
- Create requests (Branch Manager)
- Approve/Deny requests (Central Admin)
- Create purchase orders automatically
- Track order status

✅ **Delivery Management**
- Schedule deliveries
- Update delivery status
- Track shipments

✅ **Dashboard & Reports**
- Admin dashboard with statistics
- Supplier performance metrics
- Inventory reports

## Database

- **Database:** CodeIgniter 4 with MySQL
- **Default Branch:** Main Branch (ID: 1)
- **Migrations:** Auto-run on refresh

## To Reset Data

```bash
php spark migrate:refresh --seed
```

This will reset all tables and reload test data with default users.
