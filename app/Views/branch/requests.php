<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Branch Requests</title>
	<?= link_tag('css/dashboard.css') ?>
	<style>
		.form-section { background: white; border: 1px solid var(--border); border-radius: 8px; padding: 16px; margin-bottom: 20px; }
		.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px; }
		.form-row.full { grid-template-columns: 1fr; }
		.input, .textarea { padding: 8px; border: 1px solid var(--border); border-radius: 6px; font-size: 14px; }
		.textarea { resize: vertical; min-height: 80px; }
		.btn { padding: 8px 16px; background: #111; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; }
		.btn:hover { background: #333; }
		.request-card { border: 1px solid var(--border); border-radius: 8px; padding: 16px; background: white; margin-bottom: 12px; }
		.request-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
		.status-badge { display: inline-block; padding: 4px 12px; border-radius: 999px; font-size: 12px; font-weight: 600; }
		.status-pending { background: #fef3c7; color: #92400e; }
		.status-approved { background: #d1fae5; color: #065f46; }
		.status-denied { background: #fee2e2; color: #7f1d1d; }
		.status-ordered { background: #dbeafe; color: #0c4a6e; }
		.request-items { background: #f9fafb; border: 1px solid var(--border); border-radius: 6px; padding: 12px; margin: 12px 0; }
		.item-row { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr auto; gap: 8px; align-items: center; padding: 8px 0; border-bottom: 1px solid var(--border); }
		.item-row:last-child { border-bottom: none; }
		.add-item-btn { padding: 6px 12px; background: #e5e7eb; border: 1px solid var(--border); border-radius: 6px; cursor: pointer; }
		.add-item-btn:hover { background: #d1d5db; }
		.remove-btn { padding: 4px 8px; background: #fee2e2; color: #7f1d1d; border: none; border-radius: 4px; cursor: pointer; }
	</style>
</head>
<body>
	<header class="topnav">
		<div class="brand">ChakaNoks</div>
		<div class="search"><input type="text" placeholder="Search requests..."></div>
		<nav class="navicons">
			<a href="<?= site_url('pages/notifications') ?>">Notifications</a>
			<a href="<?= site_url('pages/messages') ?>">Messages</a>
		</nav>
	</header>
	<div class="layout">
		<aside class="sidebar">
			<ul>
				<li><a href="<?= site_url('branch_dashboard') ?>">Dashboard</a></li>
				<li class="active">Requests</li>
				<li><a href="<?= site_url('branch/transfers') ?>">Transfers</a></li>
				<li><a href="<?= site_url('branch/settings') ?>">Settings</a></li>
			</ul>
			<a class="logout" href="<?= site_url('logout') ?>">Log Out</a>
		</aside>
		<main class="content">
			<h1>Purchase Requests</h1>

			<div class="form-section">
				<h2>Create New Request</h2>
				<form id="requestForm" style="margin-top: 16px;">
					<div class="form-row full">
						<textarea id="notes" class="textarea" placeholder="Notes or special instructions for this request"></textarea>
					</div>

					<div style="background: #f9fafb; border: 1px solid var(--border); border-radius: 6px; padding: 12px; margin: 12px 0;">
						<h3 style="margin-top: 0;">Items</h3>
						<div id="itemsContainer" style="display: grid; gap: 12px;">
							<div class="item-row" style="border-bottom: none; padding: 0;">
								<strong>Item Name</strong>
								<strong>Qty</strong>
								<strong>Unit</strong>
								<strong>Est. Cost</strong>
								<strong></strong>
							</div>
							<div class="item-row" id="item-0" style="padding: 12px 0;">
								<input type="text" class="input item-name" placeholder="Item name" style="margin: 0;">
								<input type="number" class="input item-qty" placeholder="Qty" min="1" style="margin: 0;">
								<input type="text" class="input item-unit" placeholder="pcs/kg/box" value="pcs" style="margin: 0;">
								<input type="number" class="input item-cost" placeholder="Cost" min="0" step="0.01" style="margin: 0;">
								<button type="button" class="remove-btn" onclick="removeItem(0)">Remove</button>
							</div>
						</div>
						<button type="button" class="add-item-btn" onclick="addItem()">+ Add Another Item</button>
					</div>

					<button type="button" class="btn" onclick="submitRequest()" style="width: 200px;">Submit Request</button>
				</form>
			</div>

			<h2>Your Requests</h2>
			<div id="requestsContainer" style="display: grid; gap: 12px;">
				<div style="text-align: center; color: #6b7280;">Loading requests...</div>
			</div>
		</main>
	</div>

	<script>
		let itemCounter = 1;
		let branchId = <?= session()->get('branch_id') ?? 1 ?>;

		document.addEventListener('DOMContentLoaded', loadRequests);

		function addItem() {
			const container = document.getElementById('itemsContainer');
			const itemDiv = document.createElement('div');
			itemDiv.className = 'item-row';
			itemDiv.id = `item-${itemCounter}`;
			itemDiv.innerHTML = `
				<input type="text" class="input item-name" placeholder="Item name" style="margin: 0;">
				<input type="number" class="input item-qty" placeholder="Qty" min="1" value="1" style="margin: 0;">
				<input type="text" class="input item-unit" placeholder="pcs/kg/box" value="pcs" style="margin: 0;">
				<input type="number" class="input item-cost" placeholder="Cost" min="0" step="0.01" style="margin: 0;">
				<button type="button" class="remove-btn" onclick="removeItem(${itemCounter})">Remove</button>
			`;
			container.appendChild(itemDiv);
			itemCounter++;
		}

		function removeItem(id) {
			const item = document.getElementById(`item-${id}`);
			if (item) {
				item.remove();
			}
		}

		async function submitRequest() {
			const items = [];
			const itemElements = document.querySelectorAll('[id^="item-"]');
			
			itemElements.forEach(el => {
				const name = el.querySelector('.item-name').value;
				const qty = el.querySelector('.item-qty').value;
				const unit = el.querySelector('.item-unit').value;
				const cost = el.querySelector('.item-cost').value;
				
				if (name && qty && cost) {
					items.push({
						item_name: name,
						quantity: parseInt(qty),
						unit: unit || 'pcs',
						estimated_cost: parseFloat(cost)
					});
				}
			});

			if (items.length === 0) {
				alert('Please add at least one item');
				return;
			}

			try {
				const formData = new FormData();
				formData.append('items', JSON.stringify(items));
				formData.append('notes', document.getElementById('notes').value);

				const response = await fetch('<?= site_url("purchasing/requests/create") ?>', {
					method: 'POST',
					body: formData
				});

				const result = await response.json();
				if (result.success) {
					alert(`Purchase Request #${result.request_id} created successfully!`);
					document.getElementById('requestForm').reset();
					itemCounter = 1;
					document.getElementById('itemsContainer').innerHTML = `
						<div class="item-row" style="border-bottom: none; padding: 0;">
							<strong>Item Name</strong>
							<strong>Qty</strong>
							<strong>Unit</strong>
							<strong>Est. Cost</strong>
							<strong></strong>
						</div>
						<div class="item-row" id="item-0" style="padding: 12px 0;">
							<input type="text" class="input item-name" placeholder="Item name" style="margin: 0;">
							<input type="number" class="input item-qty" placeholder="Qty" min="1" style="margin: 0;">
							<input type="text" class="input item-unit" placeholder="pcs/kg/box" value="pcs" style="margin: 0;">
							<input type="number" class="input item-cost" placeholder="Cost" min="0" step="0.01" style="margin: 0;">
							<button type="button" class="remove-btn" onclick="removeItem(0)">Remove</button>
						</div>
					`;
					loadRequests();
				} else {
					alert('Error: ' + result.message);
				}
			} catch (error) {
				console.error('Error:', error);
				alert('An error occurred while submitting the request');
			}
		}

		async function loadRequests() {
			try {
				const response = await fetch(`<?= site_url("purchasing/requests/branch") ?>/${branchId}`);
				const requests = await response.json();
				const container = document.getElementById('requestsContainer');
				container.innerHTML = '';

				if (requests.length === 0) {
					container.innerHTML = '<div style="text-align: center; color: #6b7280;">No requests yet</div>';
					return;
				}

				for (const req of requests) {
					const itemsResponse = await fetch(`<?= site_url("purchasing/requests") ?>/${req.request_id}`);
					const reqDetails = await itemsResponse.json();

					const statusClass = `status-${req.status.toLowerCase()}`;
					const card = document.createElement('div');
					card.className = 'request-card';
					card.innerHTML = `
						<div class="request-header">
							<div>
								<h3>Request #${req.request_id}</h3>
								<small style="color: #6b7280;">${new Date(req.request_date).toLocaleDateString()}</small>
							</div>
							<span class="status-badge ${statusClass}">${req.status}</span>
						</div>

						${reqDetails.items ? `
							<div class="request-items">
								${reqDetails.items.map(item => `
									<div style="padding: 8px 0; border-bottom: 1px solid var(--border);">
										<strong>${item.item_name}</strong>
										<small style="display: block; color: #6b7280;">Qty: ${item.quantity} ${item.unit} | Est. Cost: $${parseFloat(item.estimated_cost).toFixed(2)}</small>
									</div>
								`).join('')}
							</div>
						` : ''}

						${req.notes ? `<p style="color: #6b7280; margin: 8px 0;"><strong>Notes:</strong> ${req.notes}</p>` : ''}
					`;
					container.appendChild(card);
				}
			} catch (error) {
				console.error('Error loading requests:', error);
				document.getElementById('requestsContainer').innerHTML = '<div style="color: red;">Error loading requests</div>';
			}
		}
	</script>
</body>
</html>
