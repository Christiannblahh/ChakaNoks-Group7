# Shipment Management - Testing Guide

## Overview
The logistics coordinator dashboard now has full shipment management capabilities with dynamic loading and status updates.

## Features Implemented

### 1. View Shipments
- **Location**: Logistics Coordinator Dashboard
- **Endpoint**: `GET /delivery`
- **Display**: Real-time list of all shipments with:
  - Shipment ID (Delivery ID)
  - Order ID
  - Current Status (Scheduled, In Transit, Delivered)
  - Scheduled Date
  - Delivered Date (if applicable)
  - Action Buttons

### 2. Create New Shipment
- **Button**: "Add New Shipment" in dashboard
- **Modal Form**: Opens popup with fields:
  - Purchase Order ID (required)
  - Scheduled Delivery Date (required)
  - Initial Status (Scheduled or In Transit)
- **Endpoint**: `POST /delivery/create`
- **Response**: Automatically refreshes shipment list on success

### 3. Update Shipment Status
- **Scheduled Status**: Shows "Transit" button to mark as In Transit
- **In Transit Status**: Shows "Deliver" button to mark as Delivered
- **Endpoint**: `POST /delivery/{id}/status`
- **Auto-refresh**: List updates immediately after status change

## Test Data

### Test Purchase Orders (IDs: 1, 2, 3)
- Created by PurchaseOrderSeeder
- Supplier: Test Supplier Co.
- Status: Pending
- Can be used to create shipments

### Test Deliveries (IDs: 1, 2, 3)
- Created by DeliverySeeder
- Status variations: Scheduled, In Transit, Delivered
- Ready for immediate testing

## How to Test

### Test 1: View Existing Shipments
1. Log in as Logistics Coordinator (logistics.coord@example.com / Password123!)
2. Navigate to Logistics Dashboard
3. Observe shipment table loads with test deliveries
4. Verify statuses display correctly with color coding

### Test 2: Create New Shipment
1. Click "Add New Shipment" button
2. Enter Purchase Order ID: 1, 2, or 3
3. Select a date and time for delivery
4. Click "Create Shipment"
5. Verify new shipment appears in table with "Scheduled" status

### Test 3: Update Shipment Status
1. From "Scheduled" shipment, click "Transit" button
2. Verify status changes to "In Transit" immediately
3. From "In Transit" shipment, click "Deliver" button
4. Verify status changes to "Delivered" with current timestamp

### Test 4: Validate Error Handling
1. Try creating shipment with invalid Order ID (e.g., 999)
2. Verify error message: "Purchase Order #999 not found"
3. Try creating duplicate shipment (same Order ID)
4. Verify error message: "Delivery already exists for this purchase order"

## API Endpoints

### Get All Deliveries
```
GET /delivery
Response: Array of delivery objects
```

### Get Single Delivery
```
GET /delivery/:id
Response: Delivery object with details
```

### Create Delivery
```
POST /delivery/create
Body: order_id, scheduled_date (Y-m-d H:i:s), status
Response: { success: true, delivery_id: X, message: "..." }
```

### Update Delivery Status
```
POST /delivery/:id/status
Body: status (Scheduled|In Transit|Delivered)
Response: { success: true, status: "..." }
```

### Get Pending Deliveries
```
GET /delivery/pending
Response: Array of Scheduled or In Transit deliveries
```

### Get Overdue Deliveries
```
GET /delivery/overdue
Response: Array of deliveries past scheduled date that aren't delivered
```

## Database Tables

### Deliveries Table
- `delivery_id` (INT, PK, auto-increment)
- `order_id` (INT, FK to purchase_orders)
- `logistics_id` (INT, FK to users)
- `scheduled_date` (DATETIME)
- `delivered_at` (DATETIME, nullable)
- `status` (ENUM: Scheduled, In Transit, Delivered)

### Purchase Orders Table
- `order_id` (INT, PK)
- `request_id` (INT, FK)
- `supplier_id` (INT, FK)
- `approved_by` (INT, FK)
- `order_date` (DATETIME)
- `status` (ENUM: Pending, Shipped, Delivered, Cancelled)

## Test Credentials

**Logistics Coordinator**
- Email: logistics.coord@example.com
- Password: Password123!
- Role: Full access to shipment management

## Status Update Flow

```
Scheduled ─[Mark In Transit]─> In Transit ─[Mark Delivered]─> Delivered
                                                        ↓
                                              Set delivered_at timestamp
```

## Troubleshooting

### Shipments Not Showing
- Check browser console (F12) for error messages
- Verify logged in as Logistics Coordinator
- Ensure DeliverySeeder was run: `php spark db:seed DeliverySeeder`

### Status Update Not Working
- Verify delivery ID exists
- Check that status value is valid (Scheduled, In Transit, Delivered)
- Monitor network tab in browser DevTools for API response

### Form Submission Errors
- Verify Purchase Order ID exists (use 1, 2, or 3)
- Ensure date/time is in valid format
- Check that order doesn't already have a delivery
