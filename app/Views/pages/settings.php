<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Settings Dashboard</title>
	<?= link_tag('css/dashboard.css') ?>
</head>
<body>
	<header class="topnav">
		<div class="brand">ChakaNoks</div>
		<div class="search">
			<input type="text" placeholder="Search settings...">
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
				<li><a href="<?= site_url('pages/backups') ?>">Backups</a></li>
				<li class="active">Settings</li>
			</ul>
			<a class="logout" href="<?= site_url('logout') ?>">Log Out</a>
		</aside>

		<main class="content">
			<h1>Settings</h1>
			<div class="row">
				<section class="card" style="min-width:420px">
					<h2>General</h2>
					<form method="post" action="<?= site_url('pages/settings/update') ?>" style="margin-top:10px;display:grid;gap:10px;max-width:520px">
						<input name="app_name" type="text" placeholder="Application Name" class="input" style="height:36px" value="ChaKaNoks">
						<select name="timezone" class="input" style="height:36px">
							<option value="UTC">UTC</option>
							<option value="Asia/Manila">Asia/Manila</option>
							<option value="America/New_York">America/New_York</option>
							<option value="Europe/London">Europe/London</option>
						</select>
						<button class="btn" type="submit" style="width:160px">Save Settings</button>
					</form>
				</section>
				<section class="card" style="min-width:420px">
					<h2>Security</h2>
					<div style="margin-top:10px">
						<label style="display:flex;align-items:center;gap:10px">
							<input type="checkbox"> Require strong passwords
						</label>
						<label style="display:flex;align-items:center;gap:10px;margin-top:8px">
							<input type="checkbox"> Enable 2FA (coming soon)
						</label>
					</div>
				</section>
			</div>
		</main>
	</div>

</body>
</html>



