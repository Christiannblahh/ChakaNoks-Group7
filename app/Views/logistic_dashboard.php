<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ChaKaNok's — Dashboard</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
	<?= link_tag('css/dashboard.css') ?>
	<script>
		function showSchedule(day) {
			alert('Showing delivery schedule for ' + day);
		}

		function showMetric(metric) {
			const metricNames = {
				'avg-time': 'Average Delivery Time',
				'distance': 'Optimized vs. Actual Distance',
				'fuel': 'Fuel Efficiency',
				'on-time': 'On-Time Delivery Rate'
			};
			alert('Showing details for: ' + metricNames[metric]);
		}

		function openAddShipmentModal() {
			document.getElementById('addShipmentModal').style.display = 'flex';
		}

		function closeAddShipmentModal() {
			document.getElementById('addShipmentModal').style.display = 'none';
			document.getElementById('addShipmentForm').reset();
			document.getElementById('formMessage').innerHTML = '';
			document.getElementById('orderError').innerHTML = '';
			document.getElementById('dateError').innerHTML = '';
		}

		document.getElementById('addShipmentForm').addEventListener('submit', async function(e) {
			e.preventDefault();
			
			// Clear previous errors
			document.getElementById('orderError').innerHTML = '';
			document.getElementById('dateError').innerHTML = '';
			document.getElementById('formMessage').innerHTML = '';

			const orderId = document.getElementById('orderId').value.trim();
			const scheduledDate = document.getElementById('scheduledDate').value;
			const status = document.getElementById('status').value;

			// Validation
			if (!orderId || orderId <= 0) {
				document.getElementById('orderError').innerHTML = 'Please enter a valid purchase order ID';
				return;
			}

			if (!scheduledDate) {
				document.getElementById('dateError').innerHTML = 'Please select a scheduled delivery date';
				return;
			}

			const formMessage = document.getElementById('formMessage');
			formMessage.innerHTML = '<span style="color: #f59e0b;">Creating shipment...</span>';

			try {
				const response = await fetch('<?= site_url('delivery/create') ?>', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded',
					},
					body: new URLSearchParams({
						order_id: orderId,
						scheduled_date: scheduledDate.replace('T', ' ') + ':00',
						status: status
					})
				});

				const data = await response.json();

				if (response.ok) {
					formMessage.innerHTML = '<span style="color: #10b981;">✓ Shipment created successfully! Refreshing...</span>';
					setTimeout(() => {
						location.reload();
					}, 1500);
				} else {
					formMessage.innerHTML = '<span style="color: #ef4444;">✗ Error: ' + (data.messages || data.message || 'Failed to create shipment') + '</span>';
				}
			} catch (error) {
				formMessage.innerHTML = '<span style="color: #ef4444;">✗ Error: ' + error.message + '</span>';
			}
		});
	</script>
</head>
<body>
	<header class="topnav">
		<div class="brand">ChakaNoks</div>
		<div class="search">
			<input type="text" placeholder="Search inventory, suppliers, orders...">
		</div>
		<nav class="navicons">
			<a href="<?= site_url('pages/notifications') ?>">Notifications</a>
			<a href="<?= site_url('pages/messages') ?>">Messages</a>
		</nav>
	</header>

	<!-- Add Shipment Modal -->
	<div id="addShipmentModal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
		<div class="modal-content" style="background: white; padding: 30px; border-radius: 8px; width: 90%; max-width: 500px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
			<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
				<h2 style="margin: 0;">Add New Shipment</h2>
				<button onclick="closeAddShipmentModal()" style="background: none; border: none; font-size: 24px; cursor: pointer;">&times;</button>
			</div>
			<form id="addShipmentForm">
				<div style="margin-bottom: 15px;">
					<label style="display: block; margin-bottom: 5px; font-weight: 600;">Purchase Order ID *</label>
					<input type="number" id="orderId" name="order_id" placeholder="Enter purchase order ID" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
					<small id="orderError" style="color: red;"></small>
				</div>
				<div style="margin-bottom: 15px;">
					<label style="display: block; margin-bottom: 5px; font-weight: 600;">Scheduled Delivery Date *</label>
					<input type="datetime-local" id="scheduledDate" name="scheduled_date" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
					<small id="dateError" style="color: red;"></small>
				</div>
				<div style="margin-bottom: 20px;">
					<label style="display: block; margin-bottom: 5px; font-weight: 600;">Initial Status</label>
					<select id="status" name="status" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
						<option value="Scheduled">Scheduled</option>
						<option value="In Transit">In Transit</option>
					</select>
				</div>
				<div style="display: flex; gap: 10px;">
					<button type="submit" style="flex: 1; padding: 10px; background: #111; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: 600;">Create Shipment</button>
					<button type="button" onclick="closeAddShipmentModal()" style="flex: 1; padding: 10px; background: #e5e7eb; color: #111; border: none; border-radius: 4px; cursor: pointer; font-weight: 600;">Cancel</button>
				</div>
				<div id="formMessage" style="margin-top: 10px; text-align: center;"></div>
			</form>
		</div>
	</div>

	<div class="layout">
		<aside class="sidebar">
			<ul>
				<li class="active">Dashboard</li>
				<li><a href="<?= site_url('pages/shipments') ?>">Shipments</a></li>
				<li><a href="<?= site_url('pages/routes') ?>">Routes</a></li>
				<li><a href="<?= site_url('pages/suppliers') ?>">Suppliers</a></li>
				<li><a href="<?= site_url('settings/logistics') ?>">Settings</a></li>
			</ul>
			<a class="logout" href="<?= site_url('logout') ?>">Log Out</a>
		</aside>

		<main class="content">
			<h1>Logistics Coordinator Dashboard</h1>
			<div class="row">
				<section class="card schedule">
					<h2>Delivery Schedule</h2>
					<div class="days">
						<div class="day clickable" onclick="showSchedule('Mon')">Mon 11</div>
						<div class="day clickable" onclick="showSchedule('Tue')">Tue 12</div>
						<div class="day clickable" onclick="showSchedule('Wed')">Wed 13</div>
						<div class="day clickable" onclick="showSchedule('Thu')">Thu 14</div>
						<div class="day clickable" onclick="showSchedule('Fri')">Fri 15</div>
						<div class="day clickable" onclick="showSchedule('Sat')">Sat 16</div>
						<div class="day clickable" onclick="showSchedule('Sun')">Sun 17</div>
					</div>
				</section>
				<section class="card metrics">
					<h2>Route Performance Metrics</h2>
					<ul class="metrics-list">
						<li class="metric-item clickable" onclick="showMetric('avg-time')">Average Delivery Time</li>
						<li class="metric-item clickable" onclick="showMetric('distance')">Optimized vs. Actual Distance</li>
						<li class="metric-item clickable" onclick="showMetric('fuel')">Fuel Efficiency</li>
						<li class="metric-item clickable" onclick="showMetric('on-time')">On-Time Delivery Rate</li>
					</ul>
				</section>
			</div>

			<section class="card table-card">
				<div class="table-head">
					<h2>Shipment Status Updates</h2>
					<a class="add-link" href="javascript:void(0);" onclick="openAddShipmentModal()">Add New Shipment</a>
				</div>
				<table class="table">
					<thead>
						<tr>
							<th>Shipment ID</th>
							<th>Route</th>
							<th>Status</th>
							<th>Date</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>SHP-001</td>
							<td>NYC-BOS-PHI</td>
							<td>In Transit</td>
							<td>2024-07-22 17:00</td>
							<td><a href="#">View Details</a> &nbsp; <a href="#">Update Status</a></td>
						</tr>
						<tr>
							<td>SHP-002</td>
							<td>NYC-BOS-PHI</td>
							<td>Delivered</td>
							<td>2024-07-22 17:00</td>
							<td><a href="#">View Details</a> &nbsp; <a href="#">Update Status</a></td>
						</tr>
						<tr>
							<td>SHP-003</td>
							<td>NYC-BOS-PHI</td>
							<td>Pending</td>
							<td>2024-07-22 17:00</td>
							<td><a href="#">View Details</a> &nbsp; <a href="#">Update Status</a></td>
						</tr>
						<tr>
							<td>SHP-004</td>
							<td>NYC-BOS-PHI</td>
							<td>In Transit</td>
							<td>2024-07-22 17:00</td>
							<td><a href="#">View Details</a> &nbsp; <a href="#">Update Status</a></td>
						</tr>
					</tbody>
				</table>
			</section>
		</main>
	</div>

</body>
</html>


