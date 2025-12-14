<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Supplier Dashboard</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
	<?= link_tag('css/dashboard.css') ?>
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
				<li>Orders</li>
				<li>Invoices</li>
			</ul>
			<button class="logout">Log Out</button>
		</aside>

		<main class="content">
			<h1>Supplier Dashboard</h1>
			<div class="row">
				<section class="card"><h2>Total Active Orders</h2><div style="font-size:32px;font-weight:800;margin-top:8px">85</div><div style="color:#6b7280;font-size:12px">Orders awaiting shipment</div></section>
				<section class="card"><h2>Pending Deliveries</h2><div style="font-size:32px;font-weight:800;margin-top:8px">12</div><div style="color:#6b7280;font-size:12px">Deliveries in transit</div></section>
			</div>
			<section class="card table-card">
				<div class="table-head"><h2>Recent Purchase Orders</h2></div>
				<table class="table">
					<thead><tr><th>Order ID</th><th>Branch</th><th>Order Date</th><th>Expected Delivery</th><th>Status</th><th>Total Amount</th><th>Actions</th></tr></thead>
					<tbody>
						<tr><td>PO-2024-001</td><td>Main Warehouse</td><td>2024-07-15</td><td>2024-07-25</td><td>Pending shipment</td><td>₱1,250,000.00</td><td><a href="#">View Details</a></td></tr>
						<tr><td>PO-2024-002</td><td>Branch A</td><td>2024-07-14</td><td>2024-07-20</td><td>Shipped</td><td>₱890.00</td><td><a href="#">View Details</a></td></tr>
					</tbody>
				</table>
			</section>
		</main>
	</div>

</body>
</html>


