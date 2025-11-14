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

	<div class="layout">
		<aside class="sidebar">
			<ul>
				<li class="active">Dashboard</li>
				<li><a href="<?= site_url('pages/shipments') ?>">Shipments</a></li>
				<li><a href="<?= site_url('pages/routes') ?>">Routes</a></li>
				<li><a href="<?= site_url('pages/suppliers') ?>">Suppliers</a></li>
				<li><a href="<?= site_url('pages/settings') ?>">Settings</a></li>
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
					<a class="add-link" href="#">Add New Shipment</a>
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


