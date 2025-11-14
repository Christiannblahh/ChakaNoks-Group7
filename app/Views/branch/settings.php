<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Branch Settings</title>
	<?= link_tag('css/dashboard.css') ?>
</head>
<body>
	<header class="topnav">
		<div class="brand">ChakaNoks</div>
		<div class="search"><input type="text" placeholder="Search settings..."></div>
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
				<li><a href="<?= site_url('branch/transfers') ?>">Transfers</a></li>
				<li class="active">Settings</li>
			</ul>
			<a class="logout" href="<?= site_url('logout') ?>">Log Out</a>
		</aside>
		<main class="content">
			<h1>Branch Settings</h1>
			<section class="card" style="min-width:420px">
				<h2>Preferences</h2>
				<form method="post" action="#" style="margin-top:10px;display:grid;gap:10px;max-width:520px">
					<label style="display:flex;align-items:center;gap:10px"><input type="checkbox"> Email notifications</label>
					<label style="display:flex;align-items:center;gap:10px"><input type="checkbox"> Auto-approve low-value requests</label>
					<button class="btn" type="button" style="width:160px">Save</button>
				</form>
			</section>
		</main>
	</div>
</body>
</html>



