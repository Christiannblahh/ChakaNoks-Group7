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
				<li><a href="<?= site_url('pages/stock_records') ?>">Stocks Records</a></li>
				<li><a href="<?= site_url('pages/reports') ?>">Reports</a></li>
			</ul>
			<button class="logout" onclick="window.location.href='<?= site_url('logout') ?>'">Log Out</button>
		</aside>

		<main class="content">
			<h1>Inventory Management</h1>

			<!-- Stock Alerts -->
			<section class="card" id="alerts-section" style="display: none;">
				<h2>⚠️ Stock Alerts</h2>
				<div id="alerts-list"></div>
			</section>

			<!-- Add New Item -->
			<section class="card">
				<h2>Add New Item</h2>
				<form id="add-item-form" style="margin-top:10px;display:grid;gap:10px;max-width:600px">
					<label for="add-item-name">Item Name</label>
					<input id="add-item-name" name="item_name" type="text" placeholder="Item Name" class="input" required>

					<label for="add-item-description">Description</label>
					<textarea id="add-item-description" name="item_description" placeholder="Description" class="input" rows="2"></textarea>

					<div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
						<div style="display:flex;flex-direction:column;">
							<label for="add-unit">Unit</label>
							<input id="add-unit" name="unit" type="text" placeholder="Unit (e.g., kg, pcs)" class="input" required>
						</div>
						<div style="display:flex;flex-direction:column;">
							<label for="add-quantity">Quantity</label>
							<input id="add-quantity" name="quantity" type="number" placeholder="Quantity" class="input" min="0" required>
						</div>
					</div>

					<div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
						<div style="display:flex;flex-direction:column;">
							<label for="add-reorder-level">Reorder Level</label>
							<input id="add-reorder-level" name="reorder_level" type="number" placeholder="Reorder Level" class="input" min="0" required>
						</div>
						<div style="display:flex;flex-direction:column;">
							<label for="add-expiry-date">Expiry Date</label>
							<input id="add-expiry-date" name="expiry_date" type="date" placeholder="Expiry Date" class="input">
						</div>
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

			// Custom popup function
			function showPopup(message, type = 'info') {
				// Remove any existing popup
				const existingPopup = document.querySelector('.custom-popup');
				if (existingPopup) {
					existingPopup.remove();
				}

				// Create popup element
				const popup = document.createElement('div');
				popup.className = 'custom-popup';
				popup.innerHTML = `
					<div class="popup-content">
						<div class="popup-message">${message}</div>
						<button class="popup-close" onclick="this.closest('.custom-popup').remove()">OK</button>
					</div>
				`;

				// Add styles based on type
				if (type === 'success') {
					popup.classList.add('popup-success');
				} else if (type === 'error') {
					popup.classList.add('popup-error');
				} else {
					popup.classList.add('popup-info');
				}

				// Add to page and show
				document.body.appendChild(popup);
				setTimeout(() => popup.classList.add('show'), 10);

				// Auto-close after 3 seconds for success messages
				if (type === 'success') {
					setTimeout(() => {
						if (popup.parentNode) {
							popup.remove();
						}
					}, 3000);
				}
			}

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
					let status = 'In Stock';
					let statusClass = 'status-good';
					const expiryDate = item.expiry_date ? new Date(item.expiry_date).toLocaleDateString() : 'N/A';
					if (item.expiry_date) {
						const now = new Date();
						const exp = new Date(item.expiry_date);
						const diffDays = Math.ceil((exp - now) / (1000 * 60 * 60 * 24));
						if (diffDays < 0) {
							status = 'Expired';
							statusClass = 'status-low';
						} else if (diffDays <= 30) {
							status = 'Near Expiration';
							statusClass = 'status-near-expiry';
						}
					}
					// Expired should always take priority
					if (item.expiry_date) {
						const now = new Date();
						const exp = new Date(item.expiry_date);
						if (exp < now) {
							status = 'Expired';
							statusClass = 'status-low';
						}
					}
					if (item.quantity <= item.reorder_level && status === 'In Stock') {
						status = 'Low Stock';
						statusClass = 'status-low';
					}
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

				if (!alerts || alerts.length === 0) {
					alertsSection.style.display = 'none';
					alertsList.innerHTML = '';
					return;
				}

				alertsSection.style.display = 'block';
				alertsList.innerHTML = alerts.map((item, idx) => {
					const expiryDate = item.expiry_date ? new Date(item.expiry_date).toLocaleDateString() : 'Unknown';
					if (item.type === 'expired') {
						return `
							<div class="alert alert-danger alert-flex">
								<div><strong>${item.item_name}</strong> has expired (expired on ${expiryDate}).</div>
								<button class="btn btn-small discard-btn discard-red" data-index="${idx}">Discard Stocks</button>
							</div>
						`;
					}
					if (item.type === 'near_expiry') {
						return `
							<div class="alert alert-warning alert-flex">
								<div><strong>${item.item_name}</strong> is near expiration (expires on ${expiryDate})</div>
								<button class="btn btn-small add-stock-btn add-yellow" data-index="${idx}">Add Stocks</button>
							</div>
						`;
					}
					return `
						<div class="alert alert-warning alert-flex">
							<div><strong>${item.item_name}</strong> is low on stock (${item.quantity} remaining, reorder at ${item.reorder_level})</div>
							<button class="btn btn-small add-stock-btn add-yellow" data-index="${idx}">Add Stocks</button>
						</div>
					`;
				}).join('');

				// Attach event listeners for the buttons
				setTimeout(() => {
					document.querySelectorAll('.add-stock-btn').forEach(btn => {
						btn.onclick = function() {
							const idx = parseInt(this.getAttribute('data-index'));
							const alert = alerts[idx];
							if (!alert) return;
							// Open edit modal for the item
							const item = inventoryData.find(i => i.item_name === alert.item_name);
							if (item) editItem(item.inventory_id);
						};
					});
					document.querySelectorAll('.discard-btn').forEach(btn => {
						btn.onclick = function() {
							const idx = parseInt(this.getAttribute('data-index'));
							const alert = alerts[idx];
							if (!alert) return;
							// Find item by name and set quantity to 0
							const item = inventoryData.find(i => i.item_name === alert.item_name);
							if (item) {
								if (confirm(`Are you sure you want to discard all stocks for ${item.item_name}? This will set the quantity to 0.`)) {
									// Set quantity to 0 via updateItem
									const formData = new FormData();
									formData.append('inventory_id', item.inventory_id);
									formData.append('item_name', item.item_name);
									formData.append('item_description', item.item_description || '');
									formData.append('unit', item.unit);
									formData.append('quantity', 0);
									formData.append('reorder_level', item.reorder_level);
									formData.append('expiry_date', '');
									updateItem(formData);
								}
							}
						};
					});
				}, 50);
			}

			function addItem(formData) {
				fetch('<?= site_url('inventory/add') ?>', {
					method: 'POST',
					body: formData
				})
				.then(response => response.json())
				.then(data => {
					if (data.success) {
						showPopup(data.message, 'success');
						document.getElementById('add-item-form').reset();
						loadInventory();
						loadAlerts();
					} else {
						showPopup(data.message, 'error');
					}
				})
				.catch(error => {
					console.error('Error adding item:', error);
					showPopup('Error adding item. Please try again.', 'error');
				});
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
					if (data.success) {
						showPopup(data.message, 'success');
						modal.style.display = 'none';
						loadInventory();
						loadAlerts();
					} else {
						showPopup(data.message, 'error');
					}
				})
				.catch(error => {
					console.error('Error updating item:', error);
					showPopup('Error updating item. Please try again.', 'error');
				});
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
					if (data.success) {
						showPopup(data.message, 'success');
						modal.style.display = 'none';
						loadInventory();
						loadAlerts();
					} else {
						showPopup(data.message, 'error');
					}
				})
				.catch(error => {
					console.error('Error deleting item:', error);
					showPopup('Error deleting item. Please try again.', 'error');
				});
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
			.status-near-expiry {
				background: #fef9c3;
				color: #b45309;
			}
			.btn-small {
				padding: 4px 8px;
				font-size: 12px;
			}

			/* Custom Popup Styles */
			.custom-popup {
				position: fixed;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
				background: rgba(0, 0, 0, 0.5);
				display: flex;
				justify-content: center;
				align-items: center;
				z-index: 9999;
				opacity: 0;
				transition: opacity 0.3s ease;
			}

			.custom-popup.show {
				opacity: 1;
			}

			.popup-content {
				background: white;
				padding: 20px;
				border-radius: 8px;
				box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
				max-width: 400px;
				width: 90%;
				text-align: center;
			}

			.popup-message {
				margin-bottom: 15px;
				font-size: 14px;
				line-height: 1.4;
			}

			.popup-close {
				background: #111;
				color: white;
				border: none;
				padding: 8px 16px;
				border-radius: 4px;
				cursor: pointer;
				font-size: 14px;
				font-weight: 600;
				transition: background-color 0.2s ease;
			}

			.popup-close:hover {
				background: #333;
			}

			.popup-success .popup-message {
				color: #065f46;
				font-weight: 600;
			}

			.popup-error .popup-message {
				color: #991b1b;
				font-weight: 600;
			}

			.popup-info .popup-message {
				color: #1e40af;
				font-weight: 600;
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
			.alert-flex {
				display: flex;
				justify-content: space-between;
				align-items: center;
				gap: 12px;
			}
			.add-yellow {
				background: #fde047;
				color: #7c5700;
				border: 1px solid #facc15;
			}
			.add-yellow:hover {
				background: #facc15;
				color: #5f3700;
			}
			.discard-red {
				background: #ef4444;
				color: #fff;
				border: 1px solid #dc2626;
			}
			.discard-red:hover {
				background: #dc2626;
				color: #fff;
			}
			.alert-danger {
				color: #721c24;
				background-color: #f8d7da;
				border-color: #f5c6cb;
			}
		</style>
	</div>
</body>
</html>
