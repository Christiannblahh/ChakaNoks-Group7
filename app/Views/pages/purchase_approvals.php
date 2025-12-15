<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Purchase Requests Approval</title>
	<?= link_tag('css/dashboard.css') ?>
	<style>
		.approval-container { display: grid; gap: 20px; margin-top: 20px; }
		.approval-card { 
			border: 1px solid var(--border); 
			border-radius: 8px; 
			padding: 16px; 
			background: white;
		}
		.approval-header { 
			display: grid; 
			gap: 8px;
			margin-bottom: 16px;
		}
		.approval-meta {
			display: flex;
			gap: 20px;
			font-size: 14px;
			color: #6b7280;
		}
		.approval-items {
			background: #f9fafb;
			border: 1px solid var(--border);
			border-radius: 6px;
			padding: 12px;
			margin: 12px 0;
		}
		.approval-item {
			display: grid;
			gap: 4px;
			padding: 8px 0;
			border-bottom: 1px solid var(--border);
		}
		.approval-item:last-child { border-bottom: none; }
		.approval-actions {
			display: flex;
			gap: 10px;
			margin-top: 16px;
		}
		.select-supplier {
			width: 100%;
			padding: 8px;
			border: 1px solid var(--border);
			border-radius: 6px;
			font-size: 14px;
		}
		.status-badge {
			display: inline-block;
			padding: 4px 12px;
			border-radius: 999px;
			font-size: 12px;
			font-weight: 600;
		}
		.status-pending { background: #fef3c7; color: #92400e; }
		.status-approved { background: #d1fae5; color: #065f46; }
		.status-denied { background: #fee2e2; color: #7f1d1d; }
	</style>
</head>
<body>
	<header class="topnav">
		<div class="brand">ChakaNoks - Purchase Approvals</div>
		<nav class="navicons">
			<a href="<?= site_url('pages/notifications') ?>">Notifications</a>
			<a href="<?= site_url('pages/messages') ?>">Messages</a>
		</nav>
	</header>

	<div class="layout">
		<aside class="sidebar">
			<ul>
				<li><a href="<?= site_url('admin_dashboard') ?>">Dashboard</a></li>
				<li class="active">Purchase Requests</li>
				<li><a href="<?= site_url('pages/suppliers') ?>">Suppliers</a></li>
				<li><a href="<?= site_url('pages/reports') ?>">Reports</a></li>
				<li><a href="<?= site_url('pages/settings') ?>">Settings</a></li>
			</ul>
			<a class="logout" href="<?= site_url('logout') ?>">Log Out</a>
		</aside>

		<main class="content">
			<h1>Purchase Request Approvals</h1>
			
			<div class="approval-container" id="requestsContainer">
				<div style="text-align: center; color: #6b7280;">Loading pending requests...</div>
			</div>
		</main>
	</div>

	<script>
		document.addEventListener('DOMContentLoaded', async () => {
			try {
				const response = await fetch('<?= site_url("purchasing/requests/pending") ?>');
				const requests = await response.json();
				
				const container = document.getElementById('requestsContainer');
				container.innerHTML = '';

				if (requests.length === 0) {
					container.innerHTML = '<div style="text-align: center; color: #6b7280;">No pending requests</div>';
					return;
				}

				for (const req of requests) {
					const itemsResponse = await fetch(`<?= site_url("purchasing/requests") ?>/${req.request_id}`);
					const reqDetails = await itemsResponse.json();
					
					const suppliersResponse = await fetch('<?= site_url("purchasing/suppliers") ?>');
					const suppliers = await suppliersResponse.json();

					const card = document.createElement('div');
					card.className = 'approval-card';
					card.innerHTML = `
						<div class="approval-header">
							<div style="display: flex; justify-content: space-between; align-items: center;">
								<h3>Request #${req.request_id}</h3>
								<span class="status-badge status-pending">Pending</span>
							</div>
							<div class="approval-meta">
								<span>Branch ID: ${req.branch_id}</span>
								<span>Date: ${new Date(req.request_date).toLocaleDateString()}</span>
								<span>Requested by: ${req.requested_by}</span>
							</div>
						</div>

						<div class="approval-items">
							${reqDetails.items ? reqDetails.items.map(item => `
								<div class="approval-item">
									<strong>${item.item_name}</strong>
									<small>Qty: ${item.quantity} ${item.unit} | Est. Cost: $${parseFloat(item.estimated_cost).toFixed(2)}</small>
									<small>${item.description || ''}</small>
								</div>
							`).join('') : '<p>No items</p>'}
						</div>

						<div style="margin: 12px 0;">
							<label for="supplier_${req.request_id}" style="display: block; margin-bottom: 6px; font-weight: 600;">Select Supplier:</label>
							<select id="supplier_${req.request_id}" class="select-supplier">
								<option value="">-- Choose a Supplier --</option>
								${suppliers.map(s => `<option value="${s.supplier_id}">${s.supplier_name} (${s.email})</option>`).join('')}
							</select>
						</div>

						<div style="margin: 12px 0;">
							<label for="delivery_${req.request_id}" style="display: block; margin-bottom: 6px; font-weight: 600;">Expected Delivery Date:</label>
							<input type="date" id="delivery_${req.request_id}" style="width: 100%; padding: 8px; border: 1px solid var(--border); border-radius: 6px;" value="${new Date(new Date().getTime() + 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0]}">
						</div>

						<div class="approval-actions">
							<button class="btn" onclick="approveRequest(${req.request_id})">Approve & Create Order</button>
							<button class="btn" style="background: #fee2e2; color: #7f1d1d;" onclick="denyRequest(${req.request_id})">Deny Request</button>
						</div>
					`;
					container.appendChild(card);
				}
			} catch (error) {
				console.error('Error loading requests:', error);
				document.getElementById('requestsContainer').innerHTML = '<div style="color: red;">Error loading requests</div>';
			}
		});

		async function approveRequest(requestId) {
			const supplierId = document.getElementById(`supplier_${requestId}`).value;
			const expectedDelivery = document.getElementById(`delivery_${requestId}`).value;

			if (!supplierId) {
				alert('Please select a supplier');
				return;
			}

			if (!expectedDelivery) {
				alert('Please set an expected delivery date');
				return;
			}

			try {
				const response = await fetch(`<?= site_url("purchasing/requests") ?>/${requestId}/approve`, {
					method: 'POST',
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
					body: `supplier_id=${supplierId}&expected_delivery=${expectedDelivery}`
				});

				const result = await response.json();
				if (result.success) {
					alert(`Request approved! Purchase Order #${result.order_id} created.`);
					location.reload();
				} else {
					alert('Error: ' + (result.message || 'Failed to approve'));
				}
			} catch (error) {
				console.error('Error:', error);
				alert('An error occurred');
			}
		}

		async function denyRequest(requestId) {
			const reason = prompt('Reason for denial:');
			if (!reason) return;

			try {
				const response = await fetch(`<?= site_url("purchasing/requests") ?>/${requestId}/deny`, {
					method: 'POST',
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
					body: `reason=${encodeURIComponent(reason)}`
				});

				const result = await response.json();
				if (result.success) {
					alert('Request denied');
					location.reload();
				} else {
					alert('Error denying request');
				}
			} catch (error) {
				console.error('Error:', error);
				alert('An error occurred');
			}
		}
	</script>
</body>
</html>
