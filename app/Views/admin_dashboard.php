<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>System Administrator Dashboard</title>
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
			<a href="#">Notifications</a>
			<a href="#">Messages</a>
		</nav>
	</header>

	<div class="layout">
		<aside class="sidebar">
			<ul>
				<li class="active">Dashboard</li>
				<li>Users</li>
				<li>Backups</li>
				<li>Settings</li>
			</ul>
			<button class="logout">Log Out</button>
		</aside>

		<main class="content">
			<h1>System Administrator Dashboard</h1>
			<div class="row">
				<section class="card" style="height:170px">
					<h2>System Status</h2>
					<div style="margin-top:8px;color:#6b7280;font-size:12px">CPU Usage</div>
					<div class="bar"></div>
					<div style="margin-top:6px;color:#6b7280;font-size:12px">Memory Usage</div>
					<div class="bar"></div>
					<div style="margin-top:6px;color:#6b7280;font-size:12px">Disk Usage</div>
					<div class="bar"></div>
				</section>
				<section class="card" style="height:170px">
					<h2>Application Performance</h2>
					<div class="fake-chart"></div>
				</section>
			</div>

			<div class="row">
				<section class="card" style="height:220px">
					<h2>User Account Controls</h2>
					<div style="display:flex;gap:24px;margin-top:10px">
						<button class="chip">Manage Roles</button>
						<button class="chip">Reset Password</button>
					</div>
					<div style="margin-top:14px;color:#6b7280;font-size:12px">Recent User Activity</div>
					<div class="activity-row">
						<span>name</span>
						<span class="pill">Position</span>
					</div>
					<div class="activity-row">
						<span>name</span>
						<span class="pill">Position</span>
					</div>
					<div class="activity-row">
						<span>name</span>
						<span class="pill">Position</span>
					</div>
				</section>
				<section class="card" style="height:220px">
					<h2>Backup Tools</h2>
					<div style="display:flex;gap:10px;margin-top:10px">
						<button class="chip">Initiate Backup</button>
						<button class="chip">View Backups</button>
						<button class="chip">Restore Data</button>
					</div>
					<div style="margin-top:14px;color:#6b7280;font-size:12px">Last Backup Status</div>
					<div style="margin-top:6px">success &nbsp; ( date, time )</div>
				</section>
			</div>

			<section class="card table-card">
				<div class="table-head">
					<h2>Security Alerts & Audit Log</h2>
				</div>
				<table class="table">
					<thead>
						<tr>
							<th>Event ID</th>
							<th>Event Description</th>
							<th>Timestamp</th>
							<th>Severity</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>sec001</td>
							<td>Unauthorized login attempt</td>
							<td>date - time</td>
							<td>High</td>
						</tr>
						<tr>
							<td>sec002</td>
							<td>Failed database connection</td>
							<td>date - time</td>
							<td>Critical</td>
						</tr>
						<tr>
							<td>sec003</td>
							<td>User role modification</td>
							<td>date - time</td>
							<td>Informational</td>
						</tr>
						<tr>
							<td>sec004</td>
							<td>Suspicious file access</td>
							<td>date - time</td>
							<td>Medium</td>
						</tr>
					</tbody>
				</table>
			</section>
		</main>
	</div>

	<style>
		.bar{height:6px;border:1px solid var(--border);border-radius:6px;background:var(--panel)}
		.fake-chart{margin-top:10px;height:110px;border:1px dashed var(--border);border-radius:8px;background:var(--panel)}
		.chip{border:1px solid var(--border);border-radius:8px;padding:6px 10px;background:#f3f4f6}
		.activity-row{display:flex;justify-content:space-between;align-items:center;margin-top:8px}
		.pill{background:#111;color:#fff;border-radius:999px;padding:2px 12px;font-size:12px}
	</style>

</body>
</html>


