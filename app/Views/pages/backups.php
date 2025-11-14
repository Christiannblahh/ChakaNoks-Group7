<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Backups Dashboard</title>
	<?= link_tag('css/dashboard.css') ?>
</head>
<body>
	<header class="topnav">
		<div class="brand">ChakaNoks</div>
		<div class="search">
			<input type="text" placeholder="Search backups...">
		</div>
		<nav class="navicons">
			<a href="<?= site_url('pages/notifications') ?>">Notifications</a>
			<a href="<?= site_url('pages/messages') ?>">Messages</a>
		</nav>
	</header>

	<div class="layout">
		<aside class="sidebar">
			<ul>
				<li><a href="<?= site_url('admin_dashboard') ?>">Dashboard</a></li>
				<li><a href="<?= site_url('pages/users') ?>">Users</a></li>
				<li class="active">Backups</li>
				<li><a href="<?= site_url('pages/settings') ?>">Settings</a></li>
			</ul>
			<a class="logout" href="<?= site_url('logout') ?>">Log Out</a>
		</aside>

		<main class="content">
			<h1>Backups</h1>
			<div class="row">
				<section class="card" style="min-width:360px">
					<h2>Initiate Backup</h2>
					<form method="post" action="<?= site_url('pages/backups/initiate') ?>" style="margin-top:10px;display:flex;gap:12px;align-items:center">
						<button class="btn" type="submit" style="width:180px">Start Backup</button>
					</form>
				</section>
				<section class="card" style="min-width:360px">
					<h2>Restore Backup</h2>
					<form method="post" action="<?= site_url('pages/backups/restore') ?>" style="margin-top:10px;display:flex;gap:12px;align-items:center">
						<input name="backup_id" type="text" placeholder="Backup ID" class="input" style="height:36px">
						<button class="btn" type="submit" style="width:140px">Restore</button>
					</form>
				</section>
			</div>

			<section class="card table-card">
				<div class="table-head">
					<h2>Recent Backups</h2>
					<a class="add-link" href="#">Refresh</a>
				</div>
				<table class="table">
					<thead>
						<tr>
							<th>ID</th>
							<th>Date</th>
							<th>Size</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
						<tr><td>101</td><td>2025-09-24 10:00</td><td>120 MB</td><td>Success</td></tr>
						<tr><td>100</td><td>2025-09-23 10:00</td><td>118 MB</td><td>Success</td></tr>
						<tr><td>099</td><td>2025-09-22 10:00</td><td>117 MB</td><td>Success</td></tr>
					</tbody>
				</table>
			</section>
		</main>
	</div>

</body>
</html>



