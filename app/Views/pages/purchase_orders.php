<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Purchase Orders</title>
	<?= link_tag('css/dashboard.css') ?>
	<style>
		.order-card {
			border: 1px solid var(--border);
			border-radius: 8px;
			padding: 16px;
			background: white;
			margin-bottom: 12px;
		}
		.order-header {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 12px;
		}
		.status-badge {
			display: inline-block;
			padding: 4px 12px;
			border-radius: 999px;
			font-size: 12px;
			font-weight: 600;
		}
		.status-pending { background: #fef3c7; color: #92400e; }
		.status-shipped { background: #dbeafe; color: #0c4a6e; }
		.status-delivered { background: #d1fae5; color: #065f46; }
		.order-meta {
			display: grid;
			gap: 8px;
			color: #6b7280;
			font-size: 14px;
		}
		.order-items {
			background: #f9fafb;
			border: 1px solid var(--border);
			border-radius: 6px;
			padding: 12px;
			margin: 12px 0;
		}
		.order-item {
			display: grid;
			gap: 4px;
			padding: 8px 0;
			border-bottom: 1px solid var(--border);
		}
		.order-item:last-child { border-bottom: none; }
		.order-actions {
			display: flex;
			gap: 10px;
			margin-top: 12px;
		}
		.btn { padding: 8px 16px; background: #111; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; }
		.btn:hover { background: #333; }
		.btn-secondary { background: #e5e7eb; color: #111; }
		.btn-secondary:hover { background: #d1d5db; }
		.filter-bar {
			display: flex;
			gap: 10px;
			margin-bottom: 20px;
		}
		.filter-bar select {
			padding: 8px;
			border: 1px solid var(--border);
			border-radius: 6px;
		}
	</style>
</head>
<body>
	<header class="topnav">
		<div class="brand">ChakaNoks - Purchase Orders</div>
		<nav class="navicons">
			<a href="<?= site_url('pages/notifications') ?>">Notifications</a>
			<a href="<?= site_url('pages/messages') ?>">Messages</a>
		</nav>
	</header>

	<div class="layout">
		<aside class="sidebar">
			<ul>
				<li><a href="<?= site_url('admin_dashboard') ?>">Dashboard</a></li>
				<li class="active">Purchase Orders</li>
				<li><a href="<?= site_url('pages/purchase_approvals') ?>">Approvals</a></li>
				<li><a href="<?= site_url('pages/shipments') ?>">Shipments</a></li>
				<li><a href="<?= site_url('pages/suppliers') ?>">Suppliers</a></li>
			</ul>
			<a class="logout" href="<?= site_url('logout') ?>">Log Out</a>
		</aside>

		<main class="content">
			<h1>Purchase Orders</h1>

			<div class="filter-bar">
				<select id="statusFilter" onchange="loadOrders()">
					<option value="">All Orders</option>
					<option value="Pending">Pending</option>
					<option value="Shipped">Shipped</option>
					<option value="Delivered">Delivered</option>
					<option value="Cancelled">Cancelled</option>
				</select>
			</div>

			<div id="ordersContainer" style="display: grid; gap: 12px;">
				<div style="text-align: center; color: #6b7280;">Loading orders...</div>
			</div>
		</main>
	</div>

	<script>
		document.addEventListener('DOMContentLoaded', loadOrders);

		async function loadOrders() {
			const status = document.getElementById('statusFilter').value;
			const endpoint = status 
				? `<?= site_url("purchasing/orders") ?>?status=${status}`
				: '<?= site_url("purchasing/orders") ?>';

			try {
				const response = await fetch(endpoint);
				const orders = await response.json();
				const container = document.getElementById('ordersContainer');
				container.innerHTML = '';

				if (orders.length === 0) {
					container.innerHTML = '<div style="text-align: center; color: #6b7280;">No orders found</div>';
					return;
				}

				for (const order of orders) {
					// Get order items
					const itemsResponse = await fetch(`<?= site_url("purchasing/orders") ?>/${order.order_id}`);
					const orderDetails = await itemsResponse.json();

					const statusClass = `status-${order.status.toLowerCase()}`;
					const card = document.createElement('div');
					card.className = 'order-card';
					card.innerHTML = `
						<div class="order-header">
							<div>
								<h3>Order #${order.order_id}</h3>
								<small style="color: #6b7280;">from Request #${order.request_id}</small>
							</div>
							<span class="status-badge ${statusClass}">${order.status}</span>
						</div>

						<div class="order-meta">
							<strong>Supplier:</strong> ${order.supplier_name}<br>
							<strong>Order Date:</strong> ${new Date(order.order_date).toLocaleDateString()}<br>
							<strong>Expected Delivery:</strong> ${order.expected_delivery ? new Date(order.expected_delivery).toLocaleDateString() : 'Not set'}<br>
							<strong>Total Amount:</strong> $${parseFloat(order.total_amount || 0).toFixed(2)}
						</div>

						<div class="order-items">
							${orderDetails.items ? orderDetails.items.map(item => `
								<div class="order-item">
									<strong>${item.item_name}</strong>
									<small>Qty: ${item.quantity} × $${parseFloat(item.unit_price).toFixed(2)} = $${parseFloat(item.total_price).toFixed(2)}</small>
								</div>
							`).join('') : '<p>No items</p>'}
						</div>

						<div class="order-actions">
							${order.status === 'Pending' ? `<button class="btn" onclick="updateStatus(${order.order_id}, 'Shipped')">Mark as Shipped</button>` : ''}
							${order.status === 'Shipped' ? `<button class="btn" onclick="updateStatus(${order.order_id}, 'Delivered')">Mark as Delivered</button>` : ''}
							${order.status !== 'Delivered' && order.status !== 'Cancelled' ? `<button class="btn btn-secondary" onclick="updateStatus(${order.order_id}, 'Cancelled')">Cancel</button>` : ''}
							<button class="btn btn-secondary" onclick="viewDetails(${order.order_id})">View Details</button>
						</div>
					`;
					container.appendChild(card);
				}
			} catch (error) {
				console.error('Error loading orders:', error);
				document.getElementById('ordersContainer').innerHTML = '<div style="color: red;">Error loading orders</div>';
			}
		}

		async function updateStatus(orderId, newStatus) {
			if (!confirm(`Update order status to ${newStatus}?`)) return;

			try {
				const response = await fetch(`<?= site_url("purchasing/orders") ?>/${orderId}/status`, {
					method: 'POST',
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
					body: `status=${newStatus}`
				});

				const result = await response.json();
				if (result.success) {
					alert('Order status updated');
					loadOrders();
				} else {
					alert('Error updating order');
				}
			} catch (error) {
				console.error('Error:', error);
				alert('An error occurred');
			}
		}

		function viewDetails(orderId) {
			alert(`View details for order #${orderId} - Detailed view to be implemented`);
		}
	</script>
</body>
</html>
