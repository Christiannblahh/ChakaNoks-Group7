<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Inventory Staff Dashboard</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
	<?= link_tag('public/css/dashboard.css') ?>
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
				<li><a href="<?= site_url('pages/inventory') ?>">Inventory</a></li>
				<li><a href="<?= site_url('pages/reports') ?>">Reports</a></li>
				<li><a href="<?= site_url('pages/settings') ?>">Settings</a></li>
			</ul>
			<button class="logout" onclick="window.location.href='<?= site_url('logout') ?>'">Log Out</button>
		</aside>

		<main class="content">
			<h1>Inventory Staff Dashboard</h1>
			<section class="card">
				<h2>Current Stock Inventory</h2>
				<div style="display:flex;gap:10px;margin:8px 0">
					<input class="input" style="flex:1" placeholder="Search items by name or SKU..." />
					<button class="chip">Filter</button>
				</div>
				<div class="fake-table" style="height:260px"></div>
			</section>

			<div class="row">
				<section class="card" style="height:420px">
					<h2>Report Damaged Goods</h2>
					<div class="form-col">
						<input class="input" placeholder="Item Name" />
						<input class="input" placeholder="SKU" />
						<input class="input" placeholder="Quantity Affected" />
						<textarea class="textarea" placeholder="Damage Description"></textarea>
						<button class="btn">Submit Report</button>
					</div>
				</section>
				<section class="card" style="height:420px">
					<h2>Upcoming Deliveries</h2>
					<ul class="metrics-list" style="margin-top:8px">
						<li>Global Supply Co. — In Transit</li>
						<li>Precision Tools Inc. — Scheduled</li>
						<li>Chemical Solutions Ltd. — Scheduled</li>
					</ul>
				</section>
			</div>
		</main>
	</div>

	<style>
		.input{height:34px;border:1px solid var(--border);border-radius:8px;padding:0 10px}
		.textarea{height:120px;border:1px solid var(--border);border-radius:8px;padding:8px;resize:vertical}
		.btn{height:36px;border-radius:10px;border:1px solid #000;background:#111;color:#fff;font-weight:600;cursor:pointer}
		.fake-table{border:1px solid var(--border);border-radius:10px;background:var(--panel)}
	</style>

</body>
</html>


