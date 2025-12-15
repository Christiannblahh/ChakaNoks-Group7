# Delivery Module Implementation Guide

## Overview
The Delivery module handles the complete delivery lifecycle from scheduling to tracking and completion.

## Features

### 1. Schedule Delivery
- **Endpoint:** `POST /delivery/schedule`
- **Description:** Schedule a delivery for a purchase order
- **Parameters:**
  - `order_id`: Purchase order ID
  - `scheduled_date`: Delivery date (YYYY-MM-DD)
  - `logistics_id`: Logistics coordinator ID (optional)
- **Response:** Returns delivery ID and scheduled date

### 2. Update Delivery Status
- **Endpoint:** `POST /delivery/:id/status`
- **Description:** Update delivery status (Scheduled, In Transit, Delivered)
- **Parameters:**
  - `status`: New delivery status
- **Statuses:**
  - `Scheduled`: Order scheduled for delivery
  - `In Transit`: Order is on the way
  - `Delivered`: Order successfully delivered
- **Auto Updates:** When marked "Delivered", automatically:
  - Sets delivery timestamp
  - Updates purchase order status to "Delivered"
  - Updates branch inventory with delivered items
  - Logs audit trail

### 3. Mark Delivered
- **Endpoint:** `POST /delivery/:id/delivered`
- **Description:** Mark a delivery as completed
- **Auto Actions:**
  - Updates delivery status
  - Updates inventory
  - Records audit log
  - Updates purchase order

### 4. Get Deliveries
- **Endpoint:** `GET /delivery`
- **Description:** Get all deliveries with details
- **Response:** List of deliveries with order and supplier info

### 5. Get Delivery Details
- **Endpoint:** `GET /delivery/:id`
- **Description:** Get specific delivery information
- **Response:** Detailed delivery record with joined data

## Database Schema

```sql
CREATE TABLE deliveries (
  delivery_id INT PRIMARY KEY AUTO_INCREMENT,
  order_id INT NOT NULL,
  logistics_id INT,
  scheduled_date DATE,
  delivered_at DATETIME,
  status ENUM('Scheduled', 'In Transit', 'Delivered'),
  created_at DATETIME,
  updated_at DATETIME,
  FOREIGN KEY (order_id) REFERENCES purchase_orders(order_id),
  FOREIGN KEY (logistics_id) REFERENCES users(user_id)
);
```

## Workflow

```
Purchase Order Created
    ↓
Schedule Delivery (Scheduled)
    ↓
Mark In Transit (In Transit)
    ↓
Mark Delivered (Delivered)
    ↓
✓ Inventory Updated
✓ Order Status Updated
✓ Audit Log Created
```

## API Examples

### Schedule a Delivery
```bash
curl -X POST http://localhost/purchasing/delivery/schedule \
  -d "order_id=1&scheduled_date=2025-12-25&logistics_id=5"
```

### Update Status to In Transit
```bash
curl -X POST http://localhost/purchasing/delivery/1/status \
  -d "status=In Transit"
```

### Mark as Delivered
```bash
curl -X POST http://localhost/purchasing/delivery/1/delivered
```

## Integration with Other Modules

### Inventory Integration
When delivery is marked "Delivered":
- System fetches order items
- Finds branch from purchase request
- Adds/updates inventory quantities
- Updates timestamps

### Purchase Order Integration
- Delivery status updates order status
- Delivery timestamp links to order
- Automatic status synchronization

### Audit Logging
- All status changes logged
- User who made change recorded
- Timestamp of all actions
- IP address captured for security

## Error Handling

| Error | Cause | Solution |
|-------|-------|----------|
| 404 Not Found | Delivery doesn't exist | Verify delivery ID |
| 400 Bad Request | Invalid status | Use valid status (Scheduled/In Transit/Delivered) |
| 500 Server Error | Database error | Check logs, verify data integrity |

## Testing

### Test Delivery Workflow
1. Create purchase request as Branch Manager
2. Approve request as Central Admin
3. Schedule delivery with date
4. Update status to "In Transit"
5. Mark as "Delivered"
6. Verify inventory updated
7. Check order status changed to "Delivered"

### Test Users
- Logistics Coordinator: `logistics.coord@example.com`
- Supplier: `supplier@example.com`
- Central Admin: `central.admin@example.com`

## Best Practices

✅ **DO**
- Set realistic scheduled dates
- Update status in proper order
- Verify supplier before scheduling
- Check inventory after delivery

❌ **DON'T**
- Skip status updates
- Use wrong date formats
- Schedule to inactive suppliers
- Delete deliveries (archive instead)

## Future Enhancements

- [ ] Real-time delivery tracking with GPS
- [ ] SMS/Email notifications on status change
- [ ] Delivery proof of signature
- [ ] Route optimization
- [ ] Delivery performance analytics
