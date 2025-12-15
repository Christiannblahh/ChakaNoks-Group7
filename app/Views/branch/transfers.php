<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Branch Transfers</title>
	<?= link_tag('css/dashboard.css') ?>
</head>
<body>
	<header class="topnav">
		<div class="brand">ChakaNoks</div>
		<div class="search"><input type="text" placeholder="Search transfers..."></div>
		<nav class="navicons">
			<a href="<?= site_url('pages/notifications') ?>">Notifications</a>
			<a href="<?= site_url('pages/messages') ?>">Messages</a>
		</nav>
	</header>
	<div class="layout">
		<aside class="sidebar">
			<ul>
				<li><a href="<?= site_url('branch_dashboard') ?>">Dashboard</a></li>
				<li><a href="<?= site_url('branch/requests') ?>">Requests</a></li>
				<li class="active">Transfers</li>
				<li><a href="<?= site_url('branch/settings') ?>">Settings</a></li>
			</ul>
			<a class="logout" href="<?= site_url('logout') ?>">Log Out</a>
		</aside>
		<main class="content">
			<h1>Transfers</h1>
			<section class="card" style="min-width:420px">
				<h2>New Transfer</h2>
				<form method="post" action="<?= site_url('branch/transfers/create') ?>" style="margin-top:10px;display:grid;gap:10px;max-width:520px">
					<input name="from_branch" type="text" placeholder="From Branch" class="input" style="height:36px">
					<input name="to_branch" type="text" placeholder="To Branch" class="input" style="height:36px">
					<input name="item" type="text" placeholder="Item" class="input" style="height:36px">
					<input name="quantity" type="number" placeholder="Quantity" class="input" style="height:36px">
					<button class="btn" type="submit" style="width:180px">Send Transfer</button>
				</form>
			</section>
			<section class="card table-card">
				<div class="table-head"><h2>Recent Transfers</h2></div>
				<table class="table">
					<thead><tr><th>ID</th><th>From</th><th>To</th><th>Item</th><th>Qty</th><th>Date</th></tr></thead>
					<tbody>
						<?php if (!empty($transfers)): ?>
							<?php foreach ($transfers as $transfer): ?>
								<tr>
									<td>TR-<?= $transfer['transfer_id'] ?></td>
									<td><?= esc($transfer['from_branch_name'] ?? 'Unknown') ?></td>
									<td><?= esc($transfer['to_branch_name'] ?? 'Unknown') ?></td>
									<td><?= esc($transfer['item_name'] ?? '') ?></td>
									<td><?= esc($transfer['quantity'] ?? 0) ?></td>
									<td><?= date('Y-m-d', strtotime($transfer['transfer_date'])) ?></td>
								</tr>
							<?php endforeach; ?>
						<?php else: ?>
							<tr>
								<td colspan="6" style="text-align: center;">No transfers found.</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</section>
		</main>
	</div>
</body>
</html>
