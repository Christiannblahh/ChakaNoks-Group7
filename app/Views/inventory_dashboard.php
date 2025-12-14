<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Inventory Staff Dashboard</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
	<?= link_tag('css/dashboard.css') ?>
	<script>
		// Real-time inventory tracking functions
		function filterInventory() {
			const searchTerm = document.getElementById('searchInput').value.toLowerCase();
			const rows = document.querySelectorAll('.inventory-row');

			rows.forEach(row => {
				const itemName = row.cells[0].textContent.toLowerCase();
				const sku = row.cells[1].textContent.toLowerCase();
				const matches = itemName.includes(searchTerm) || sku.includes(searchTerm);
				row.style.display = matches ? '' : 'none';
			});
		}

		function applyFilters() {
			alert('Advanced filters applied: Showing items with stock levels below threshold');
			// In a real implementation, this would filter by stock status
		}

		function refreshInventory() {
			document.getElementById('inventoryBody').style.opacity = '0.5';
			setTimeout(() => {
				document.getElementById('inventoryBody').style.opacity = '1';
				alert('Inventory data refreshed from database');
			}, 1000);
		}

		function updateStock(sku) {
			const newStock = prompt(`Enter new stock quantity for ${sku}:`);
			if (newStock !== null && !isNaN(newStock)) {
				// Find the row with matching SKU and update the stock quantity
				const rows = document.querySelectorAll('.inventory-row');
				rows.forEach(row => {
					const skuCell = row.cells[1]; // SKU is in the second column (index 1)
					if (skuCell.textContent === sku) {
						const stockCell = row.cells[2]; // Stock quantity is in the third column (index 2)
						const statusCell = row.cells[3]; // Status is in the fourth column (index 3)

						// Update the stock quantity
						stockCell.textContent = newStock;

						// Update the status based on new stock level
						const stockNum = parseInt(newStock);
						if (stockNum > 50) {
							statusCell.className = 'status-good';
							statusCell.textContent = 'In Stock';
						} else if (stockNum > 0) {
							statusCell.className = 'status-low';
							statusCell.textContent = 'Low Stock';
						} else {
							statusCell.className = 'status-out';
							statusCell.textContent = 'Out of Stock';
						}

						alert(`Stock updated for ${sku}: ${newStock} units`);
						// In a real implementation, this would update the database
					}
				});
			}
		}

		function setAlert(sku) {
			const threshold = prompt(`Set stock alert threshold for ${sku}:`);
			if (threshold !== null && !isNaN(threshold)) {
				alert(`Alert set for ${sku} when stock falls below ${threshold} units`);
			}
		}

		function submitDamageReport() {
			const item = document.getElementById('damageItem').value;
			const sku = document.getElementById('damageSku').value;
			const qty = document.getElementById('damageQty').value;
			const desc = document.getElementById('damageDesc').value;

			if (!item || !sku || !qty || !desc) {
				alert('Please fill in all fields');
				return;
			}

			alert(`Damage report submitted:\nItem: ${item}\nSKU: ${sku}\nQuantity: ${qty}\nDescription: ${desc}`);
			// Clear form
			document.getElementById('damageItem').value = '';
			document.getElementById('damageSku').value = '';
			document.getElementById('damageQty').value = '';
			document.getElementById('damageDesc').value = '';
		}

		function trackDelivery(supplier) {
			const statusDiv = document.getElementById('deliveryStatus');
			statusDiv.textContent = `Tracking ${supplier} delivery...`;

			setTimeout(() => {
				const statuses = ['In Transit', 'Out for Delivery', 'Delivered'];
				const randomStatus = statuses[Math.floor(Math.random() * statuses.length)];
				statusDiv.textContent = `${supplier}: ${randomStatus}`;
			}, 1500);
		}



		// Simulate real-time updates
		setInterval(() => {
			const rows = document.querySelectorAll('.inventory-row');
			rows.forEach(row => {
				const currentStock = parseInt(row.cells[2].textContent);
				const statusCell = row.cells[3];

				if (currentStock > 50) {
					statusCell.className = 'status-good';
					statusCell.textContent = 'In Stock';
				} else if (currentStock > 0) {
					statusCell.className = 'status-low';
					statusCell.textContent = 'Low Stock';
				} else {
					statusCell.className = 'status-out';
					statusCell.textContent = 'Out of Stock';
				}
			});
		}, 5000); // Update every 5 seconds
	</script>
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
				<li><a href="<?= site_url('pages/stock_records') ?>">Stocks Records</a></li>
				<li><a href="<?= site_url('pages/reports') ?>">Reports</a></li>
				<li><a href="<?= site_url('inventory/settings') ?>">Settings</a></li>
			</ul>
			<button class="logout" onclick="window.location.href='<?= site_url('logout') ?>'">Log Out</button>
		</aside>

		<main class="content">
			<h1>Inventory Staff Dashboard</h1>
			<section class="card">
				<h2>Current Stock Inventory</h2>
				<div style="display:flex;gap:10px;margin:8px 0">
					<input class="input" id="searchInput" style="flex:1" placeholder="Search items by name or SKU..." onkeyup="filterInventory()" />
					<button class="chip" onclick="applyFilters()">Filter</button>
					<button class="chip" onclick="refreshInventory()">Refresh</button>
				</div>
				<div id="inventoryTable" class="fake-table" style="height:260px">
					<table class="inventory-table">
						<thead>
							<tr>
								<th>Item Name</th>
								<th>SKU</th>
								<th>Current Stock</th>
								<th>Status</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody id="inventoryBody">
							<tr class="inventory-row" data-stock="150">
								<td>Grilled Chicken Breast</td>
								<td>CHK-001</td>
								<td>150</td>
								<td class="status-good">In Stock</td>
								<td>
									<button class="action-btn update-btn" onclick="updateStock('CHK-001')">Update</button>
									<button class="action-btn alert-btn" onclick="setAlert('CHK-001')">Alert</button>
								</td>
							</tr>
							<tr class="inventory-row" data-stock="25">
								<td>Chicken Wings</td>
								<td>CHK-002</td>
								<td>25</td>
								<td class="status-low">Low Stock</td>
								<td>
									<button class="action-btn update-btn" onclick="updateStock('CHK-002')">Update</button>
									<button class="action-btn alert-btn" onclick="setAlert('CHK-002')">Alert</button>
								</td>
							</tr>
							<tr class="inventory-row" data-stock="0">
								<td>Chicken Nuggets</td>
								<td>CHK-003</td>
								<td>0</td>
								<td class="status-out">Out of Stock</td>
								<td>
									<button class="action-btn update-btn" onclick="updateStock('CHK-003')">Update</button>
									<button class="action-btn alert-btn" onclick="setAlert('CHK-003')">Alert</button>
								</td>
							</tr>
							<tr class="inventory-row" data-stock="80">
								<td>Chicken Tenders</td>
								<td>CHK-004</td>
								<td>80</td>
								<td class="status-good">In Stock</td>
								<td>
									<button class="action-btn update-btn" onclick="updateStock('CHK-004')">Update</button>
									<button class="action-btn alert-btn" onclick="setAlert('CHK-004')">Alert</button>
								</td>
							</tr>
							<tr class="inventory-row" data-stock="45">
								<td>Chicken Strips</td>
								<td>CHK-005</td>
								<td>45</td>
								<td class="status-low">Low Stock</td>
								<td>
									<button class="action-btn update-btn" onclick="updateStock('CHK-005')">Update</button>
									<button class="action-btn alert-btn" onclick="setAlert('CHK-005')">Alert</button>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</section>

			<div class="row">
				<section class="card" style="height:420px">
					<h2>Report Damaged Goods</h2>
					<div class="form-col">
						<input class="input" id="damageItem" placeholder="Item Name" />
						<input class="input" id="damageSku" placeholder="SKU" />
						<input class="input" id="damageQty" type="number" placeholder="Quantity Affected" />
						<textarea class="textarea" id="damageDesc" placeholder="Damage Description"></textarea>
						<button class="btn" onclick="submitDamageReport()">Submit Report</button>
					</div>
				</section>
				<section class="card" style="height:420px">
					<h2>Upcoming Deliveries</h2>
					<ul class="metrics-list" style="margin-top:8px">
						<li class="delivery-item" onclick="trackDelivery('Global Supply Co.')">Global Supply Co. — In Transit</li>
						<li class="delivery-item" onclick="trackDelivery('Precision Tools Inc.')">Precision Tools Inc. — Scheduled</li>
						<li class="delivery-item" onclick="trackDelivery('Chemical Solutions Ltd.')">Chemical Solutions Ltd. — Scheduled</li>
					</ul>
					<div id="deliveryStatus" style="margin-top:10px;font-size:12px;color:var(--muted)"></div>
				</section>
			</div>
		</main>
	</div>

	<style>
		.input{height:34px;border:1px solid var(--border);border-radius:8px;padding:0 10px}
		.textarea{height:120px;border:1px solid var(--border);border-radius:8px;padding:8px;resize:vertical}
		.btn{height:36px;border-radius:10px;border:1px solid #000;background:#111;color:#fff;font-weight:600;cursor:pointer}
		.fake-table{border:1px solid var(--border);border-radius:10px;background:var(--panel)}

		/* Inventory table styles */
		.inventory-table{width:100%;border-collapse:collapse}
		.inventory-table th{text-align:left;padding:8px;font-size:12px;color:var(--muted);border-bottom:1px solid var(--border)}
		.inventory-table td{padding:8px;border-bottom:1px solid var(--border)}
		.inventory-row:hover{background:#f9fafb}

		/* Status indicators */
		.status-good{color:#059669;font-weight:600}
		.status-low{color:#d97706;font-weight:600}
		.status-out{color:#dc2626;font-weight:600}

		/* Action buttons */
		.action-btn{padding:4px 8px;border-radius:4px;font-size:11px;font-weight:600;cursor:pointer;margin-right:4px;border:1px solid var(--border)}
		.update-btn{background:#3b82f6;color:#fff;border-color:#3b82f6}
		.update-btn:hover{background:#2563eb}
		.alert-btn{background:#f59e0b;color:#fff;border-color:#f59e0b}
		.alert-btn:hover{background:#d97706}

		/* Delivery items */
		.delivery-item{cursor:pointer;padding:4px 0}
		.delivery-item:hover{background:#f3f4f6;color:var(--text)}

		/* Chip buttons */
		.chip{padding:6px 12px;border:1px solid var(--border);border-radius:16px;background:#f3f4f6;font-size:12px;font-weight:600;cursor:pointer}
		.chip:hover{background:#e5e7eb}


	</style>

</body>
</html>


