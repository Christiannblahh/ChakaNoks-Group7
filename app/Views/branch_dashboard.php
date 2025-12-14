<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Branch Manager Dashboard</title>
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
			<a href="<?= site_url('pages/notifications') ?>">Notifications</a>
			<a href="<?= site_url('pages/messages') ?>">Messages</a>
		</nav>
	</header>

	<div class="layout">
		<aside class="sidebar">
			<ul>
				<li class="active">Dashboard</li>
				<li><a href="<?= site_url('branch/requests') ?>">Requests</a></li>
				<li><a href="<?= site_url('branch/transfers') ?>">Transfers</a></li>
			</ul>
			<a class="logout" href="<?= site_url('logout') ?>">Log Out</a>
		</aside>

		<main class="content">
			<h1>Branch Manager Dashboard</h1>
			<section class="card"><h2>Key Inventory Statistics</h2>
				<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:8px">
					<div class="card"><div style="font-size:12px;color:#6b7280">Total Stock Value</div><div style="font-size:28px;font-weight:800">₱1,234,567</div></div>
					<div class="card"><div style="font-size:12px;color:#6b7280">Items Below Reorder Point</div><div style="font-size:28px;font-weight:800">45 items</div></div>
					<div class="card"><div style="font-size:12px;color:#6b7280">Stock Turnover Rate</div><div style="font-size:28px;font-weight:800">6.2x/year</div></div>
				</div>
			</section>

			<section class="card table-card">
				<div class="table-head"><h2>Recent Purchase Requests</h2></div>
				<table class="table"><thead><tr><th>Request ID</th><th>Item</th><th>Quantity</th><th>Status</th><th>Date</th></tr></thead>
				<tbody>
				<tr><td>PR-2024-001</td><td>chicken</td><td>20</td><td>Pending</td><td>2024-07-28</td></tr>
				<tr><td>PR-2024-002</td><td>chicken</td><td>6</td><td>Approved</td><td>2024-07-27</td></tr>
				</tbody></table>
			</section>

			<section class="card table-card">
				<div class="table-head"><h2>Intra-branch Transfer Approvals</h2></div>
				<table class="table"><thead><tr><th>Request ID</th><th>From Branch</th><th>To Branch</th><th>Item</th><th>Quantity</th><th>Date</th></tr></thead>
				<tbody>
				<tr><td>TR-2024-001</td><td>Branch1</td><td>Branch4</td><td>Chicken</td><td>5</td><td>2024-07-28</td></tr>
				<tr><td>TR-2024-002</td><td>Branch2</td><td>Branch4</td><td>Chicken</td><td>2</td><td>2024-07-28</td></tr>
				</tbody></table>
			</section>
		</main>
	</div>

</body>
</html>


