<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Access Denied</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
	<?= link_tag('css/dashboard.css') ?>
</head>
<body>
	<header class="topnav">
		<div class="brand">ChakaNoks</div>
		<div class="search">
			<input type="text" placeholder="Search...">
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
				<li><a href="<?= site_url('pages/inventory') ?>">Inventory</a></li>
				<li><a href="<?= site_url('pages/reports') ?>">Reports</a></li>
			</ul>
			<a class="logout" href="<?= site_url('logout') ?>">Log Out</a>
		</aside>

		<main class="content">
			<h1>Access Denied</h1>
			<div class="row">
				<section class="card">
					<h2>Permission Required</h2>
					<p>You do not have permission to access this page. Only System Administrators can view inventory settings.</p>
					<p><a href="<?= site_url('inventory_dashboard') ?>">Return to Dashboard</a></p>
				</section>
			</div>
		</main>
	</div>
</body>
</html>
