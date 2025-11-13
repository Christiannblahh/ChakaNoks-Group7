<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Inventory Management</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
	<?= link_tag('public/css/dashboard.css') ?>
</head>
<body>
	<header class="topnav">
		<div class="brand">ChakaNoks</div>
		<div class="search">
			<input type="text" placeholder="Search inventory...">
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
				<li class="active">Inventory</li>
				<li><a href="<?= site_url('pages/reports') ?>">Reports</a></li>
				<li><a href="<?= site_url('pages/settings') ?>">Settings</a></li>
			</ul>
			<button class="logout" onclick="window.location.href='<?= site_url('logout') ?>'">Log Out</button>
		</aside>

		<main class="content">
			<h1>Inventory Management</h1>
			<section class="card">
				<h2>Stock Levels</h2>
				<div class="fake-table" style="height:400px"></div>
			</section>
		</main>
	</div>
</body>
</html>
