<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Franchise Supply Allocation</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
	<?= link_tag('css/dashboard.css') ?>
</head>
<body>
	<header class="topnav">
		<div class="brand">ChakaNoks - Supply Allocation</div>
		<nav class="navicons">
			<a href="<?= site_url('franchise/applications') ?>">Applications</a>
			<a href="<?= site_url('logout') ?>">Log Out</a>
		</nav>
	</header>

	<div class="layout">
		<aside class="sidebar">
			<ul>
				<li><a href="<?= site_url('franchise_dashboard') ?>">Dashboard</a></li>
				<li class="active"><a href="#">Supply Allocation</a></li>
			</ul>
			<a class="logout" href="<?= site_url('logout') ?>">Log Out</a>
		</aside>

		<main class="content">
			<h1>Supply Allocation for Application #<?= esc($application['application_id']) ?></h1>
			<p>Applicant: <strong><?= esc($application['applicant_name']) ?></strong> — <?= esc($application['business_name']) ?> (<?= esc($application['location']) ?>)</p>

			<section class="card" style="margin-bottom:16px;">
				<h2>Add Supply</h2>
				<form method="post" action="<?= site_url('franchise/applications/' . $application['application_id'] . '/supplies/add') ?>" style="display:grid;grid-template-columns:2fr 1fr 1fr auto;gap:8px;align-items:end;">
					<div>
						<label>Item Name</label>
						<input type="text" name="item_name" class="input" required>
					</div>
					<div>
						<label>Quantity</label>
						<input type="number" name="quantity" class="input" min="1" required>
					</div>
					<div>
						<label>Supply Date</label>
						<input type="date" name="supply_date" class="input" value="<?= date('Y-m-d') ?>">
					</div>
					<div>
						<button type="submit" class="btn">Save</button>
					</div>
				</form>
			</section>

			<section class="card table-card">
				<h2>Allocated Supplies</h2>
				<table class="table">
					<thead>
						<tr>
							<th>Date</th>
							<th>Item</th>
							<th>Quantity</th>
						</tr>
					</thead>
					<tbody>
						<?php if (!empty($supplies)): ?>
							<?php foreach ($supplies as $s): ?>
								<tr>
									<td><?= esc($s['supply_date']) ?></td>
									<td><?= esc($s['item_name']) ?></td>
									<td><?= esc($s['quantity']) ?></td>
								</tr>
							<?php endforeach; ?>
						<?php else: ?>
							<tr>
								<td colspan="3" style="text-align:center;color:#6b7280;">No supplies allocated yet.</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</section>
		</main>
	</div>
</body>
</html>
