<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Franchise Dashboard</title>
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
				<li>Franchise Applications</li>
				<li>Supply Allocation</li>
				<li>Inventory Management</li>
				<li>Order Management</li>
				<li>Reports</li>
				<li>Settings</li>
			</ul>
			<button class="logout">Log Out</button>
		</aside>

		<main class="content">
			<h1>Franchise Dashboard</h1>
			
			<!-- Overview Cards Row -->
			<div class="row">
				<section class="card">
					<h2>Total Applications</h2>
					<div style="font-size:32px;font-weight:800;margin-top:8px">85</div>
					<a href="#" style="color:#111;text-decoration:none;font-size:12px">View Details</a>
				</section>
				<section class="card">
					<h2>Pending Approvals</h2>
					<div style="font-size:32px;font-weight:800;margin-top:8px">85</div>
					<a href="#" style="color:#111;text-decoration:none;font-size:12px">View Details</a>
				</section>
				<section class="card">
					<h2>Allocated Supplies</h2>
					<div style="font-size:32px;font-weight:800;margin-top:8px">85</div>
					<a href="#" style="color:#111;text-decoration:none;font-size:12px">View Details</a>
				</section>
				<section class="card">
					<h2>Open Requests</h2>
					<div style="font-size:32px;font-weight:800;margin-top:8px">85</div>
					<a href="#" style="color:#111;text-decoration:none;font-size:12px">View Details</a>
				</section>
			</div>

			<!-- Franchise Applications Table -->
			<section class="card table-card">
				<div class="table-head">
					<h2>Franchise Applications</h2>
				</div>
				<table class="table">
					<thead>
						<tr>
							<th>Applicant Name</th>
							<th>Application Date</th>
							<th>Region</th>
							<th>Status</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Global Eats Inc.</td>
							<td>2024-03-10</td>
							<td>North</td>
							<td>Pending</td>
							<td>
								<button class="action-btn approve">✓</button>
								<button class="action-btn reject">✗</button>
							</td>
						</tr>
						<tr>
							<td>Global Eats Inc.</td>
							<td>2024-03-10</td>
							<td>North</td>
							<td>Approved</td>
							<td>
								<button class="action-btn approve">✓</button>
								<button class="action-btn reject">✗</button>
							</td>
						</tr>
						<tr>
							<td>Global Eats Inc.</td>
							<td>2024-03-10</td>
							<td>North</td>
							<td>Rejected</td>
							<td>
								<button class="action-btn approve">✓</button>
								<button class="action-btn reject">✗</button>
							</td>
						</tr>
						<tr>
							<td>Global Eats Inc.</td>
							<td>2024-03-10</td>
							<td>North</td>
							<td>Approved</td>
							<td>
								<button class="action-btn approve">✓</button>
								<button class="action-btn reject">✗</button>
							</td>
						</tr>
						<tr>
							<td>Global Eats Inc.</td>
							<td>2024-03-10</td>
							<td>North</td>
							<td>Approved</td>
							<td>
								<button class="action-btn approve">✓</button>
								<button class="action-btn reject">✗</button>
							</td>
						</tr>
						<tr>
							<td>Global Eats Inc.</td>
							<td>2024-03-10</td>
							<td>North</td>
							<td>Pending</td>
							<td>
								<button class="action-btn approve">✓</button>
								<button class="action-btn reject">✗</button>
							</td>
						</tr>
					</tbody>
				</table>
			</section>

			<!-- Supply Allocation Overview -->
			<section class="card">
				<h2>Supply Allocation Overview</h2>
				<div style="margin-top:12px">
					<h3 style="font-size:14px;color:#6b7280;margin:0 0 8px 0">Recent Supply Requests</h3>
					<div class="supply-request">
						<div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid var(--border)">
							<div>
								<div style="font-weight:600">Beverage Mix A (200)</div>
								<div style="font-size:12px;color:#6b7280">Franchise: Global Eats Inc. - 2024-03-12</div>
							</div>
							<div style="text-align:right">
								<span class="status pending">Pending</span>
								<a href="#" style="margin-left:8px;color:#111;text-decoration:none">View</a>
							</div>
						</div>
						<div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid var(--border)">
							<div>
								<div style="font-weight:600">Safety Equipment (50)</div>
								<div style="font-size:12px;color:#6b7280">Franchise: Global Eats Inc. - 2024-03-12</div>
							</div>
							<div style="text-align:right">
								<span class="status approved">Approved</span>
								<a href="#" style="margin-left:8px;color:#111;text-decoration:none">View</a>
							</div>
						</div>
						<div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid var(--border)">
							<div>
								<div style="font-weight:600">Kitchen Supplies (100)</div>
								<div style="font-size:12px;color:#6b7280">Franchise: Global Eats Inc. - 2024-03-12</div>
							</div>
							<div style="text-align:right">
								<span class="status fulfilled">Fulfilled</span>
								<a href="#" style="margin-left:8px;color:#111;text-decoration:none">View</a>
							</div>
						</div>
						<div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0">
							<div>
								<div style="font-weight:600">Cleaning Materials (75)</div>
								<div style="font-size:12px;color:#6b7280">Franchise: Global Eats Inc. - 2024-03-12</div>
							</div>
							<div style="text-align:right">
								<span class="status pending">Pending</span>
								<a href="#" style="margin-left:8px;color:#111;text-decoration:none">View</a>
							</div>
						</div>
					</div>
				</div>
			</section>
		</main>
	</div>

	<style>
		/* Overview cards - 4 columns */
		.row {
			display: grid;
			grid-template-columns: repeat(4, 1fr);
			gap: 16px;
			margin-bottom: 16px;
		}
		
		/* Action buttons for franchise table */
		.action-btn {
			width: 24px;
			height: 24px;
			border-radius: 50%;
			border: none;
			margin: 0 2px;
			cursor: pointer;
			font-weight: bold;
		}
		
		.action-btn.approve {
			background: #10b981;
			color: white;
		}
		
		.action-btn.reject {
			background: #ef4444;
			color: white;
		}
		
		/* Status pills */
		.status {
			padding: 4px 8px;
			border-radius: 12px;
			font-size: 11px;
			font-weight: 600;
		}
		
		.status.pending {
			background: #fef3c7;
			color: #92400e;
		}
		
		.status.approved {
			background: #d1fae5;
			color: #065f46;
		}
		
		.status.fulfilled {
			background: #dbeafe;
			color: #1e40af;
		}
		
		/* Supply request styling */
		.supply-request {
			background: var(--panel);
			border-radius: 8px;
			padding: 12px;
		}
	</style>

</body>
</html>
