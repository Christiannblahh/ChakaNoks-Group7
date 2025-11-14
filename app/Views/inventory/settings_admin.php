<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Inventory Settings - Admin</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
	<?= link_tag('css/dashboard.css') ?>
</head>
<body>
	<header class="topnav">
		<div class="brand">ChakaNoks</div>
		<div class="search">
			<input type="text" placeholder="Search inventory settings...">
		</div>
		<nav class="navicons">
			<a href="<?= site_url('pages/notifications') ?>">Notifications</a>
			<a href="<?= site_url('pages/messages') ?>">Messages</a>
		</nav>
	</header>

	<div class="layout">
		<aside class="sidebar">
			<ul>
				<li><a href="<?= site_url('inventory_dashboard') ?>">Dashboard</a></li>
				<li class="active">Settings</li>
				<li><a href="<?= site_url('pages/inventory') ?>">Inventory</a></li>
				<li><a href="<?= site_url('pages/reports') ?>">Reports</a></li>
			</ul>
			<a class="logout" href="<?= site_url('logout') ?>">Log Out</a>
		</aside>

		<main class="content">
			<h1>⚙️ Inventory Settings (ChakaNoks SCMS)</h1>
			<div class="row">
				<section class="card">
					<h2>Access granted. You are logged in as System Administrator.</h2>
					<p>From here, you can configure inventory categories, set stock thresholds, manage branch permissions, and oversee supplier and logistics operations across all ChakaNoks branches.</p>
					<!-- Add admin-specific settings here -->
				</section>
			</div>
		</main>
	</div>
</body>
</html>
