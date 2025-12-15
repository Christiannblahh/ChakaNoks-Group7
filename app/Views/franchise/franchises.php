<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Franchise Branches</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
	<?= link_tag('css/dashboard.css') ?>
</head>
<body>
	<header class="topnav">
		<div class="brand">ChakaNoks - Franchise Branches</div>
		<nav class="navicons">
			<a href="<?= site_url('franchise_dashboard') ?>">Dashboard</a>
			<a href="<?= site_url('logout') ?>">Log Out</a>
		</nav>
	</header>

	<div class="layout">
		<aside class="sidebar">
			<ul>
				<li><a href="<?= site_url('franchise_dashboard') ?>">Dashboard</a></li>
				<li class="active"><a href="<?= site_url('franchise/franchises') ?>">Franchise Branches</a></li>
			</ul>
			<a class="logout" href="<?= site_url('logout') ?>">Log Out</a>
		</aside>

		<main class="content">
			<h1>Franchise Branches</h1>

			<section class="card table-card">
				<table class="table">
					<thead>
						<tr>
							<th>ID</th>
							<th>Owner</th>
							<th>Branch ID</th>
							<th>Agreement</th>
							<th>Royalty</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
						<?php if (!empty($franchises)): ?>
							<?php foreach ($franchises as $f): ?>
								<tr>
									<td><?= esc($f['franchise_id']) ?></td>
									<td><?= esc($f['owner_name']) ?></td>
									<td><?= esc($f['branch_id']) ?></td>
									<td>
										<?= esc($f['agreement_start']) ?> - <?= esc($f['agreement_end']) ?>
									</td>
									<td>
										<?= esc($f['royalty_type']) ?> (<?= esc($f['royalty_rate']) ?>)
									</td>
									<td><?= esc($f['status']) ?></td>
								</tr>
							<?php endforeach; ?>
						<?php else: ?>
							<tr>
								<td colspan="6" style="text-align:center;color:#6b7280;">No franchises recorded yet.</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</section>
		</main>
	</div>
</body>
</html>
