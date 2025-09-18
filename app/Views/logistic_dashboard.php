<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ChaKaNok's — Dashboard</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
	<?= link_tag('public/css/dashboard.css') ?>
</head>
<body>
	<header class="topnav">
		<div class="brand">ChakaNoks</div>
		<div class="search">
			<input type="text" placeholder="Search inventory, suppliers, orders...">
		</div>
		<nav class="navicons">
			<a href="#">Notifications</a>
			<a href="#">Messages</a>
		</nav>
	</header>

	<div class="layout">
		<aside class="sidebar">
			<ul>
				<li class="active">Dashboard</li>
				<li>Shipments</li>
				<li>Routes</li>
				<li>Suppliers</li>
				<li>Settings</li>
			</ul>
			<button class="logout">Log Out</button>
		</aside>

		<main class="content">
			<h1>Logistics Coordinator Dashboard</h1>
			<div class="row">
				<section class="card schedule">
					<h2>Delivery Schedule</h2>
					<div class="days">
						<div class="day">Mon 11</div>
						<div class="day">Tue 12</div>
						<div class="day">Wed 13</div>
						<div class="day">Thu 14</div>
						<div class="day">Fri 15</div>
						<div class="day">Sat 16</div>
						<div class="day">Sun 17</div>
					</div>
				</section>
				<section class="card metrics">
					<h2>Route Performance Metrics</h2>
					<ul class="metrics-list">
						<li>Average Delivery Time</li>
						<li>Optimized vs. Actual Distance</li>
						<li>Fuel Efficiency</li>
						<li>On-Time Delivery Rate</li>
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


