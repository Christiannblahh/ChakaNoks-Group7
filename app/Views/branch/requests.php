<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Branch Requests</title>
	<?= link_tag('public/css/dashboard.css') ?>
</head>
<body>
	<header class="topnav">
		<div class="brand">ChakaNoks</div>
		<div class="search"><input type="text" placeholder="Search requests..."></div>
		<nav class="navicons">
			<a href="<?= site_url('pages/notifications') ?>">Notifications</a>
			<a href="<?= site_url('pages/messages') ?>">Messages</a>
		</nav>
	</header>
	<div class="layout">
		<aside class="sidebar">
			<ul>
				<li><a href="<?= site_url('branch_dashboard') ?>">Dashboard</a></li>
				<li class="active">Requests</li>
				<li><a href="<?= site_url('branch/transfers') ?>">Transfers</a></li>
				<li><a href="<?= site_url('branch/settings') ?>">Settings</a></li>
			</ul>
			<a class="logout" href="<?= site_url('logout') ?>">Log Out</a>
		</aside>
		<main class="content">
			<h1>Purchase Requests</h1>
			<section class="card" style="min-width:420px">
				<h2>New Request</h2>
				<form method="post" action="<?= site_url('branch/requests/create') ?>" style="margin-top:10px;display:grid;gap:10px;max-width:520px">
					<input name="item" type="text" placeholder="Item" class="input" style="height:36px">
					<input name="quantity" type="number" placeholder="Quantity" class="input" style="height:36px">
					<button class="btn" type="submit" style="width:160px">Submit Request</button>
				</form>
			</section>
			<section class="card table-card">
				<div class="table-head"><h2>Recent Requests</h2></div>
				<table class="table">
					<thead><tr><th>ID</th><th>Item</th><th>Qty</th><th>Status</th><th>Date</th></tr></thead>
					<tbody>
						<tr><td>PR-2024-003</td><td>chicken</td><td>12</td><td>Pending</td><td>2024-07-29</td></tr>
					</tbody>
				</table>
			</section>
		</main>
	</div>
</body>
</html>



