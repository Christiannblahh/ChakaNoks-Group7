<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Inventory Management</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
	<?= link_tag('css/dashboard.css') ?>
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

			<!-- Stock Alerts -->
			<section class="card" id="alerts-section" style="display: none;">
				<h2>⚠️ Low Stock Alerts</h2>
				<div id="alerts-list"></div>
			</section>

			<!-- Add New Item -->
			<section class="card">
				<h2>Add New Item</h2>
				<form id="add-item-form" style="margin-top:10px;display:grid;gap:10px;max-width:600px">
					<input name="item_name" type="text" placeholder="Item Name" class="input" required>
					<textarea name="item_description" placeholder="Description" class="input" rows="2"></textarea>
					<div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
						<input name="unit" type="text" placeholder="Unit (e.g., kg, pcs)" class="input" required>
						<input name="quantity" type="number" placeholder="Quantity" class="input" min="0" required>
					</div>
					<div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
						<input name="reorder_level" type="number" placeholder="Reorder Level" class="input" min="0" required>
						<input name="expiry_date" type="date" placeholder="Expiry Date" class="input">
					</div>
					<button class="btn" type="submit">Add Item</button>
				</form>
			</section>

			<!-- Inventory Table -->
			<section class="card table-card">
				<div class="table-head">
					<h2>Current Stock Levels</h2>
					<button class="btn" id="refresh-btn">Refresh</button>
				</div>
				<table class="table" id="inventory-table">
					<thead>
						<tr>
							<th>Item Name</th>
							<th>Description</th>
							<th>Unit</th>
							<th>Quantity</th>
							<th>Reorder Level</th>
							<th>Status</th>
							<th>Expiry Date</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody id="inventory-tbody">
						<tr>
							<td colspan="8" style="text-align:center;padding:40px;">Loading inventory...</td>
						</tr>
					</tbody>
				</table>
			</section>
		</main>

		<!-- Edit Modal -->
		<div id="edit-modal" class="modal" style="display:none;">
			<div class="modal-content">
				<span class="close">&times;</span>
				<h2>Edit Item</h2>
				<form id="edit-item-form" style="margin-top:10px;display:grid;gap:10px;max-width:600px">
					<input name="inventory_id" type="hidden" id="edit-inventory-id">
					<input name="item_name" type="text" placeholder="Item Name" class="input" id="edit-item-name" required>
					<textarea name="item_description" placeholder="Description" class="input" id="edit-item-description" rows="2"></textarea>
					<div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
						<input name="unit" type="text" placeholder="Unit" class="input" id="edit-unit" required>
						<input name="quantity" type="number" placeholder="Quantity" class="input" id="edit-quantity" min="0" required>
					</div>
					<div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
						<input name="reorder_level" type="number" placeholder="Reorder Level" class="input" id="edit-reorder-level" min="0" required>
						<input name="expiry_date" type="date" placeholder="Expiry Date" class="input" id="edit-expiry-date">
					</div>
					<div style="display:flex;gap:10px;">
						<button class="btn" type="submit">Update Item</button>
						<button class="btn" type="button" id="delete-btn" style="background:#dc3545;">Delete Item</button>
					</div>
				</form>
			</div>
		</div>

		<script>
			let inventoryData = [];

			// Load inventory on page load
			document.addEventListener('DOMContentLoaded', function() {
				loadInventory();
				loadAlerts();
				setInterval(loadAlerts, 30000); // Check alerts every 30 seconds
			});

			// Refresh button
			document.getElementById('refresh-btn').addEventListener('click', loadInventory);

			// Add item form
			document.getElementById('add-item-form').addEventListener('submit', function(e) {
				e.preventDefault();
				addItem(new FormData(this));
			});

			// Edit modal
			const modal = document.getElementById('edit-modal');
			const closeBtn = document.getElementsByClassName('close')[0];

			closeBtn.onclick = function() {
				modal.style.display = 'none';
			}

			window.onclick = function(event) {
				if (event.target == modal) {
					modal.style.display = 'none';
				}
			}

			// Edit form
			document.getElementById('edit-item-form').addEventListener('submit', function(e) {
				e.preventDefault();
				updateItem(new FormData(this));
			});

			// Delete button
			document.getElementById('delete-btn').addEventListener('click', function() {
				if (confirm('Are you sure you want to delete this item?')) {
					deleteItem(document.getElementById('edit-inventory-id').value);
				}
			});

			function loadInventory() {
				fetch('<?= site_url('inventory/get') ?>')
					.then(response => response.json())
					.then(data => {
						inventoryData = data;
						renderInventoryTable();
					})
					.catch(error => console.error('Error loading inventory:', error));
			}

			function loadAlerts() {
				fetch('<?= site_url('inventory/low-stock') ?>')
					.then(response => response.json())
					.then(data => {
						renderAlerts(data);
					})
					.catch(error => console.error('Error loading alerts:', error));
			}

			function renderInventoryTable() {
				const tbody = document.getElementById('inventory-tbody');
				if (inventoryData.length === 0) {
					tbody.innerHTML = '<tr><td colspan="8" style="text-align:center;padding:40px;">No inventory items found.</td></tr>';
					return;
				}

				tbody.innerHTML = inventoryData.map(item => {
					const status = item.quantity <= item.reorder_level ? 'Low Stock' : 'In Stock';
					const statusClass = item.quantity <= item.reorder_level ? 'status-low' : 'status-good';
					const expiryDate = item.expiry_date ? new Date(item.expiry_date).toLocaleDateString() : 'N/A';

					return `
						<tr>
							<td>${item.item_name}</td>
							<td>${item.item_description || ''}</td>
							<td>${item.unit}</td>
							<td>${item.quantity}</td>
							<td>${item.reorder_level}</td>
							<td><span class="status ${statusClass}">${status}</span></td>
							<td>${expiryDate}</td>
							<td><button class="btn btn-small" onclick="editItem(${item.inventory_id})">Edit</button></td>
						</tr>
					`;
				}).join('');
			}

			function renderAlerts(alerts) {
				const alertsSection = document.getElementById('alerts-section');
				const alertsList = document.getElementById('alerts-list');

				if (alerts.length === 0) {
					alertsSection.style.display = 'none';
					return;
				}

				alertsSection.style.display = 'block';
				alertsList.innerHTML = alerts.map(item => `
					<div class="alert alert-warning">
						<strong>${item.item_name}</strong> is low on stock (${item.quantity} remaining, reorder at ${item.reorder_level})
					</div>
				`).join('');
			}

			function addItem(formData) {
				fetch('<?= site_url('inventory/add') ?>', {
					method: 'POST',
					body: formData
				})
				.then(response => response.json())
				.then(data => {
					alert(data.message);
					if (data.success) {
						document.getElementById('add-item-form').reset();
						loadInventory();
						loadAlerts();
					}
				})
				.catch(error => console.error('Error adding item:', error));
			}

			function editItem(inventoryId) {
				const item = inventoryData.find(i => i.inventory_id == inventoryId);
				if (!item) return;

				document.getElementById('edit-inventory-id').value = item.inventory_id;
				document.getElementById('edit-item-name').value = item.item_name;
				document.getElementById('edit-item-description').value = item.item_description || '';
				document.getElementById('edit-unit').value = item.unit;
				document.getElementById('edit-quantity').value = item.quantity;
				document.getElementById('edit-reorder-level').value = item.reorder_level;
				document.getElementById('edit-expiry-date').value = item.expiry_date ? item.expiry_date.split(' ')[0] : '';

				modal.style.display = 'block';
			}

			function updateItem(formData) {
				fetch('<?= site_url('inventory/update') ?>', {
					method: 'POST',
					body: formData
				})
				.then(response => response.json())
				.then(data => {
					alert(data.message);
					if (data.success) {
						modal.style.display = 'none';
						loadInventory();
						loadAlerts();
					}
				})
				.catch(error => console.error('Error updating item:', error));
			}

			function deleteItem(inventoryId) {
				const formData = new FormData();
				formData.append('inventory_id', inventoryId);

				fetch('<?= site_url('inventory/delete') ?>', {
					method: 'POST',
					body: formData
				})
				.then(response => response.json())
				.then(data => {
					alert(data.message);
					if (data.success) {
						modal.style.display = 'none';
						loadInventory();
						loadAlerts();
					}
				})
				.catch(error => console.error('Error deleting item:', error));
			}
		</script>

		<style>
			.status {
				padding: 4px 8px;
				border-radius: 4px;
				font-size: 12px;
				font-weight: bold;
			}
			.status-good {
				background: #d4edda;
				color: #155724;
			}
			.status-low {
				background: #f8d7da;
				color: #721c24;
			}
			.btn-small {
				padding: 4px 8px;
				font-size: 12px;
			}
			.modal {
				display: none;
				position: fixed;
				z-index: 1;
				left: 0;
				top: 0;
				width: 100%;
				height: 100%;
				background-color: rgba(0,0,0,0.4);
			}
			.modal-content {
				background-color: #fefefe;
				margin: 15% auto;
				padding: 20px;
				border: 1px solid #888;
				width: 80%;
				max-width: 600px;
			}
			.close {
				color: #aaa;
				float: right;
				font-size: 28px;
				font-weight: bold;
				cursor: pointer;
			}
			.close:hover {
				color: black;
			}
			.alert {
				padding: 10px;
				margin: 10px 0;
				border: 1px solid transparent;
				border-radius: 4px;
			}
			.alert-warning {
				color: #856404;
				background-color: #fff3cd;
				border-color: #ffeaa7;
			}
		</style>
	</div>
</body>
</html>
