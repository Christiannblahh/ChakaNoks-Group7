# Inventory Management System Guide

## Overview
The Inventory module manages stock levels, item tracking, and reorder management across all branches.

## Features

### 1. Inventory CRUD Operations
- **Create:** Add new inventory items to branch
- **Read:** View inventory by branch
- **Update:** Modify quantities and reorder levels
- **Delete:** Remove items from inventory

### 2. Low Stock Alerts
- **Automatic Detection:** Items below reorder level
- **API Endpoint:** `GET /pages/low-stock-alerts`
- **Response:** List of items needing reorder
- **Used By:** Branch managers for ordering

### 3. Inventory by Branch
- **Get Branch Inventory:** `GET /pages/inventory/:branch_id`
- **Filter By:** Branch, item name, status
- **Display:** Item name, quantity, unit, reorder level

### 4. Automatic Inventory Updates
When deliveries are marked complete:
- Items automatically added to receiving branch
- Quantities incremented if item exists
- New items created if not in inventory
- Timestamps updated

## Database Schema

```sql
CREATE TABLE inventory (
  inventory_id INT PRIMARY KEY AUTO_INCREMENT,
  branch_id INT NOT NULL,
  item_name VARCHAR(150),
  item_description VARCHAR(255),
  unit VARCHAR(20),
  quantity INT,
  reorder_level INT,
  expiry_date DATE,
  created_at DATETIME,
  updated_at DATETIME,
  FOREIGN KEY (branch_id) REFERENCES branches(branch_id)
);
```

## Current Seeded Items (Ingredients)

ChakaNoks uses the following ingredients in production:

| Item | Unit | Qty | Reorder | Description |
|------|------|-----|---------|-------------|
| Rice Flour | kg | 500 | 100 | For Chakli & Murukku |
| Urad Flour | kg | 300 | 75 | For snack binding |
| Gram Flour | kg | 250 | 50 | For binding & thickening |
| Refined Oil | liter | 800 | 150 | For frying |
| Salt (Refined) | kg | 200 | 40 | Seasoning |
| Red Chili Powder | kg | 80 | 15 | Spice flavoring |
| Turmeric Powder | kg | 60 | 12 | Color & health |
| Cumin Seeds | kg | 50 | 10 | Aroma |
| Sesame Seeds | kg | 40 | 8 | Coating & flavor |
| Black Pepper | kg | 25 | 5 | Sharp spice |
| Asafoetida (Hing) | kg | 5 | 1 | Traditional flavor |
| Fenugreek Seeds | kg | 30 | 6 | Authentic taste |
| Coriander Seeds | kg | 35 | 7 | Spice blend |
| Carom Seeds (Ajwain) | kg | 20 | 4 | Digestive benefits |
| Moong Dal (Split) | kg | 150 | 30 | Snack prep |

## API Endpoints

### Get Inventory for Branch
```
GET /pages/inventory
Response: JSON array of inventory items for logged-in user's branch
```

### Add Inventory Item
```
POST /pages/add-inventory-item
Parameters:
- item_name: string (required)
- item_description: string
- unit: string (pcs, kg, liter, etc)
- quantity: integer (required)
- reorder_level: integer
```

### Update Inventory Item
```
POST /pages/update-inventory-item/:id
Parameters:
- item_name: string
- quantity: integer
- reorder_level: integer
```

### Get Low Stock Items
```
GET /pages/low-stock-alerts
Response: Items where quantity <= reorder_level
```

## Workflow

```
Branch Receives Delivery
    ↓
Logistics marks as "Delivered"
    ↓
System automatically:
  - Fetches delivery items
  - Finds receiving branch
  - Adds to branch inventory
  - Updates timestamps
    ↓
✓ Branch inventory updated
✓ Quantities incremented
✓ Audit logged
```

## Integration Points

### Purchase Request → Inventory
1. Branch staff create request with items needed
2. Request goes to approval
3. Order created and shipped
4. Upon delivery, items added to inventory

### Delivery → Inventory
1. Logistics marks delivery as "Delivered"
2. System reads order_items
3. Finds branch from purchase_request
4. Adds items to inventory
5. Updates quantities

### Purchase Order → Inventory
1. Purchase order created from approved request
2. When delivered, inventory updated
3. Quantities reflect new stock

## Testing Inventory Workflow

### Step 1: Create Purchase Request
- Log in as: `branch.manager@example.com`
- Go to: Branch → Purchase Requests
- Add items needed

### Step 2: Approve Request
- Log in as: `central.admin@example.com`
- Go to: Approvals
- Select supplier
- Click Approve

### Step 3: Schedule Delivery
- Log in as: `logistics.coord@example.com`
- Go to: Deliveries
- Select order
- Set scheduled date

### Step 4: Complete Delivery
- Mark delivery as "In Transit"
- Mark delivery as "Delivered"
- Verify: Branch inventory automatically updated

### Step 5: Verify Inventory
- Log in as: `branch.manager@example.com`
- Go to: Inventory
- See new items added

## Inventory Best Practices

✅ **DO**
- Set realistic reorder levels
- Monitor low stock alerts weekly
- Update quantities when items arrive
- Use correct units (kg, pcs, liters)
- Keep descriptions accurate

❌ **DON'T**
- Leave quantities at 0
- Set reorder level too high
- Use inconsistent units
- Delete inventory (archive instead)
- Ignore low stock alerts

## Low Stock Alert Scenario

```
Item: Rice Flour
Current: 45 kg
Reorder Level: 100 kg
Status: ⚠️ ALERT

Action: Create purchase request for Rice Flour
```

## Expiry Date Management

- Items have expiry_date field
- FIFO principle: First In, First Out
- Track perishable items closely
- Implement expiry alerts (future feature)

## Statistics

- **Total Branches:** 1 (Main Branch)
- **Total Items:** 15 ingredient types
- **Total Quantity:** ~2,400+ units
- **Monitoring:** Real-time via dashboard

## Performance Metrics

| Metric | Value |
|--------|-------|
| Avg Items per Branch | 15 |
| Total Quantity | 2,400+ |
| Low Stock Items | Varies |
| Reorder Frequency | Weekly |

## Future Enhancements

- [ ] Barcode scanning
- [ ] Mobile inventory app
- [ ] Automated reorder triggers
- [ ] Stock movement history
- [ ] Expiry date alerts
- [ ] Multi-warehouse support
- [ ] Batch tracking
- [ ] Supplier linking to items

## Troubleshooting

| Problem | Solution |
|---------|----------|
| Items not updating after delivery | Check if delivery marked "Delivered" |
| Low stock alerts not showing | Verify reorder_level is set |
| Inventory showing 0 for delivered items | Check purchase order items exist |
| Duplicates in inventory | Update existing instead of creating new |

## Support

For issues with inventory management, contact:
- **Technical:** Check CRITICAL_ISSUES_AND_FIXES.md
- **Testing:** See TEST_CREDENTIALS.md
- **API:** Check DELIVERY_MODULE_GUIDE.md
