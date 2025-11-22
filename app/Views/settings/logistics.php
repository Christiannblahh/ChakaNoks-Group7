<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Logistics Settings</title>
	<?= link_tag('css/dashboard.css') ?>
</head>
<body>
	<header class="topnav">
		<div class="brand">ChakaNoks</div>
		<div class="search">
			<input type="text" placeholder="Search logistics settings...">
		</div>
		<nav class="navicons">
			<a href="<?= site_url('pages/notifications') ?>">Notifications</a>
			<a href="<?= site_url('pages/messages') ?>">Messages</a>
		</nav>
	</header>

	<div class="layout">
		<aside class="sidebar">
			<ul>
				<li><a href="<?= site_url('logistic_dashboard') ?>">Dashboard</a></li>
				<li><a href="<?= site_url('pages/shipments') ?>">Shipments</a></li>
				<li><a href="<?= site_url('pages/routes') ?>">Routes</a></li>
				<li><a href="<?= site_url('pages/suppliers') ?>">Suppliers</a></li>
				<li class="active">Settings</li>
			</ul>
			<a class="logout" href="<?= site_url('logout') ?>">Log Out</a>
		</aside>

		<main class="content">
			<h1>Logistics Settings</h1>
			<div class="row">
				<section class="card">
					<h2>Route Optimization</h2>
					<p>Configure route optimization preferences for deliveries.</p>
					<!-- Add logistics-specific settings here -->
				</section>
				<section class="card">
					<h2>Delivery Preferences</h2>
					<p>Set delivery time windows and vehicle assignments.</p>
					<!-- Add more settings -->
				</section>
			</div>
		</main>
	</div>
</body>
</html>
