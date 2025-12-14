<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Users Dashboard</title>
	<?= link_tag('css/dashboard.css') ?>
</head>
<body>
	<header class="topnav">
		<div class="brand">ChakaNoks</div>
		<div class="search">
			<input type="text" placeholder="Search users...">
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
				<li class="active">Users</li>
				<li><a href="<?= site_url('pages/backups') ?>">Backups</a></li>
			</ul>
			<a class="logout" href="<?= site_url('logout') ?>">Log Out</a>
		</aside>

		<main class="content">
			<h1>Users</h1>
			<div class="row">
				<section class="card" style="min-width:360px">
					<h2>Create User</h2>
					<form method="post" action="<?= site_url('pages/users/create') ?>" style="margin-top:10px;display:grid;gap:10px;max-width:420px">
						<input name="name" type="text" placeholder="Full name" class="input" style="height:36px">
						<input name="email" type="email" placeholder="Email" class="input" style="height:36px">
						<select name="role" class="input" style="height:36px">
							<option value="">Select role</option>
							<option>System Admin</option>
							<option>Central Admin</option>
							<option>Inventory Staff</option>
							<option>Branch Manager</option>
							<option>Franchise Manager</option>
							<option>Supplier</option>
							<option>Logistics Coordinator</option>
						</select>
						<button class="btn" type="submit" style="width:140px">Create</button>
					</form>
				</section>
				<section class="card table-card" style="flex:1">
					<div class="table-head">
						<h2>Recent Users</h2>
						<a class="add-link" href="#">Export CSV</a>
					</div>
					<table class="table">
						<thead>
							<tr>
								<th>Name</th>
								<th>Email</th>
								<th>Role</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
							<tr><td>name</td><td>email@example.com</td><td>System Admin</td><td>Active</td></tr>
							<tr><td>name</td><td>email@example.com</td><td>Inventory Staff</td><td>Active</td></tr>
							<tr><td>name</td><td>email@example.com</td><td>Supplier</td><td>Suspended</td></tr>
						</tbody>
					</table>
				</section>
			</div>
		</main>
	</div>

</body>
</html>



