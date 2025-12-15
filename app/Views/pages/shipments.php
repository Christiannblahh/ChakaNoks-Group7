<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Shipments & Delivery Tracking</title>
	<?= link_tag('css/dashboard.css') ?>
	<style>
		.shipment-card {
			border: 1px solid var(--border);
			border-radius: 8px;
			padding: 16px;
			background: white;
			margin-bottom: 12px;
		}
		.shipment-header {
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
		.status-scheduled { background: #e0e7ff; color: #3730a3; }
		.status-in-transit { background: #fef3c7; color: #92400e; }
		.status-delivered { background: #d1fae5; color: #065f46; }
		.shipment-info {
			display: grid;
			gap: 8px;
			color: #6b7280;
			font-size: 14px;
			margin: 12px 0;
		}
		.shipment-actions {
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
		.stats-grid {
			display: grid;
			grid-template-columns: repeat(4, 1fr);
			gap: 12px;
			margin-bottom: 20px;
		}
		.stat-card {
			background: white;
			border: 1px solid var(--border);
			border-radius: 8px;
			padding: 16px;
			text-align: center;
		}
		.stat-number { font-size: 24px; font-weight: 700; color: #111; }
		.stat-label { font-size: 12px; color: #6b7280; margin-top: 4px; }
	</style>
</head>
<body>
	<header class="topnav">
		<div class="brand">ChakaNoks - Delivery Tracking</div>
		<nav class="navicons">
			<a href="<?= site_url('pages/notifications') ?>">Notifications</a>
			<a href="<?= site_url('pages/messages') ?>">Messages</a>
		</nav>
	</header>

	<div class="layout">
		<aside class="sidebar">
			<ul>
				<li><a href="<?= site_url('logistic_dashboard') ?>">Dashboard</a></li>
				<li class="active">Shipments</li>
				<li><a href="<?= site_url('pages/routes') ?>">Routes</a></li>
				<li><a href="<?= site_url('pages/purchase_orders') ?>">Purchase Orders</a></li>
			</ul>
			<a class="logout" href="<?= site_url('logout') ?>">Log Out</a>
		</aside>

		<main class="content">
			<h1>Delivery Tracking</h1>

			<div class="stats-grid">
				<div class="stat-card">
					<div class="stat-number" id="totalScheduled">0</div>
					<div class="stat-label">Scheduled</div>
				</div>
				<div class="stat-card">
					<div class="stat-number" id="inTransit">0</div>
					<div class="stat-label">In Transit</div>
				</div>
				<div class="stat-card">
					<div class="stat-number" id="deliveredToday">0</div>
					<div class="stat-label">Delivered Today</div>
				</div>
				<div class="stat-card">
					<div class="stat-number" id="overdue">0</div>
					<div class="stat-label">Overdue</div>
				</div>
			</div>

			<div class="filter-bar">
				<select id="statusFilter" onchange="loadShipments()">
					<option value="">All Shipments</option>
					<option value="Scheduled">Scheduled</option>
					<option value="In Transit">In Transit</option>
					<option value="Delivered">Delivered</option>
				</select>
			</div>

			<div id="shipmentsContainer" style="display: grid; gap: 12px;">
				<div style="text-align: center; color: #6b7280;">Loading shipments...</div>
			</div>
		</main>
	</div>

	<script>
		document.addEventListener('DOMContentLoaded', () => {
			loadStats();
			loadShipments();
		});

		async function loadStats() {
			try {
				const response = await fetch('<?= site_url("delivery/stats") ?>');
				const stats = await response.json();
				
				document.getElementById('totalScheduled').textContent = stats.total_scheduled || 0;
				document.getElementById('inTransit').textContent = stats.in_transit || 0;
				document.getElementById('deliveredToday').textContent = stats.delivered_today || 0;
				document.getElementById('overdue').textContent = stats.overdue || 0;
			} catch (error) {
				console.error('Error loading stats:', error);
			}
		}

		async function loadShipments() {
			const status = document.getElementById('statusFilter').value;
			const endpoint = status 
				? `<?= site_url("delivery/status") ?>/${status}`
				: '<?= site_url("delivery") ?>';

			try {
				const response = await fetch(endpoint);
				const shipments = await response.json();
				const container = document.getElementById('shipmentsContainer');
				container.innerHTML = '';

				if (shipments.length === 0) {
					container.innerHTML = '<div style="text-align: center; color: #6b7280;">No shipments found</div>';
					return;
				}

				for (const shipment of shipments) {
					const statusClass = `status-${shipment.status.toLowerCase().replace(' ', '-')}`;
					const card = document.createElement('div');
					card.className = 'shipment-card';
					card.innerHTML = `
						<div class="shipment-header">
							<div>
								<h3>Delivery #${shipment.delivery_id}</h3>
								<small style="color: #6b7280;">Order #${shipment.order_id}</small>
							</div>
							<span class="status-badge ${statusClass}">${shipment.status}</span>
						</div>

						<div class="shipment-info">
							<strong>Supplier:</strong> ${shipment.supplier_name || 'N/A'}<br>
							<strong>Scheduled Date:</strong> ${new Date(shipment.scheduled_date).toLocaleDateString()}<br>
							${shipment.delivered_at ? `<strong>Delivered:</strong> ${new Date(shipment.delivered_at).toLocaleDateString()} at ${new Date(shipment.delivered_at).toLocaleTimeString()}<br>` : ''}
							<strong>Logistics Manager:</strong> ${shipment.email || 'Unassigned'}
						</div>

						<div class="shipment-actions">
							${shipment.status === 'Scheduled' ? `<button class="btn" onclick="updateDeliveryStatus(${shipment.delivery_id}, 'In Transit')">Mark In Transit</button>` : ''}
							${shipment.status === 'In Transit' ? `<button class="btn" onclick="updateDeliveryStatus(${shipment.delivery_id}, 'Delivered')">Mark Delivered</button>` : ''}
							<button class="btn btn-secondary" onclick="viewDeliveryDetails(${shipment.delivery_id})">View Details</button>
						</div>
					`;
					container.appendChild(card);
				}
			} catch (error) {
				console.error('Error loading shipments:', error);
				document.getElementById('shipmentsContainer').innerHTML = '<div style="color: red;">Error loading shipments</div>';
			}
		}

		async function updateDeliveryStatus(deliveryId, newStatus) {
			try {
				const response = await fetch(`<?= site_url("delivery") ?>/${deliveryId}/status`, {
					method: 'POST',
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
					body: `status=${newStatus}`
				});

				const result = await response.json();
				if (result.success) {
					alert(`Delivery status updated to ${newStatus}`);
					loadStats();
					loadShipments();
				} else {
					alert('Error updating delivery status');
				}
			} catch (error) {
				console.error('Error:', error);
				alert('An error occurred');
			}
		}

		function viewDeliveryDetails(deliveryId) {
			alert(`View details for delivery #${deliveryId} - Detailed view to be implemented`);
		}
	</script>
</body>
</html>
