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
			<section class="card" id="alerts-section" style="max-height: 320px; overflow: hidden;">
				<h2>⚠️ Stock Alerts</h2>
				<input id="alerts-search" type="text" placeholder="Search alerts..." style="margin-bottom:8px;width:100%;padding:6px 10px;">
				<div id="alerts-list" style="overflow-y:auto; max-height: 220px;">
<?php
if (isset($alerts) && count($alerts) > 0):
    foreach ($alerts as $alert):
        $expiryDate = $alert['expiry_date'] ? date('m/d/Y', strtotime($alert['expiry_date'])) : 'Unknown';
        if ($alert['type'] === 'expired') {
            $invId = null;
foreach ($inventory as $inv) {
    if ($inv['item_name'] === $alert['item_name']) {
        $invId = $inv['inventory_id'];
        break;
    }
}
echo '<div class="alert alert-danger alert-flex"><div><strong>' . esc($alert['item_name']) . '</strong> has expired (expired on ' . $expiryDate . ').</div><button class="btn btn-small discard-btn discard-red" data-inventory-id="' . $invId . '">Discard Stocks</button></div>';
        } elseif ($alert['type'] === 'near_expiry') {
            $invId = null;
foreach ($inventory as $inv) {
    if ($inv['item_name'] === $alert['item_name']) {
        $invId = $inv['inventory_id'];
        break;
    }
}
echo '<div class="alert alert-warning alert-flex"><div><strong>' . esc($alert['item_name']) . '</strong> is near expiration (expires on ' . $expiryDate . ')</div></div>';
        } else {
            $invId = null;
foreach ($inventory as $inv) {
    if ($inv['item_name'] === $alert['item_name']) {
        $invId = $inv['inventory_id'];
        break;
    }
}
echo '<div class="alert alert-warning alert-flex"><div><strong>' . esc($alert['item_name']) . '</strong> is low on stock (' . $alert['quantity'] . ' remaining, reorder at ' . $alert['reorder_level'] . ')</div><button class="btn btn-small add-stock-btn add-yellow" data-inventory-id="' . $invId . '">Add Stocks</button></div>';
        }
    endforeach;
else:
    echo '<div style="text-align:center;color:#888;padding:20px 0;">No stock alerts.</div>';
endif;
?>
</div>
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
							<select id="add-unit" name="unit" class="input" required>
	<option value="" disabled selected>Select unit</option>
	<option value="kg">kg</option>
	<option value="pcs">pcs</option>
	<option value="liter">liter</option>
	<option value="liter">can</option>
	<option value="liter">mL</option>
</select>
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
				<input id="inventory-search" type="text" placeholder="Search inventory..." style="margin-bottom:8px;width:100%;padding:6px 10px;">
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
<?php if (isset($inventory) && count($inventory) > 0): ?>
    <?php foreach ($inventory as $item): ?>
        <tr>
            <td><?= esc($item['item_name']) ?></td>
            <td><?= esc($item['item_description']) ?></td>
            <td><?= esc($item['unit']) ?></td>
            <td><?= esc($item['quantity']) ?></td>
            <td><?= esc($item['reorder_level']) ?></td>
            <td>
                <?php
                    $status = 'In Stock';
                    $statusClass = 'status-good';
                    $expiryDate = $item['expiry_date'] ? date('m/d/Y', strtotime($item['expiry_date'])) : 'N/A';
                    
                    // Check expiry status first (highest priority)
                    if ($item['expiry_date']) {
                        $now = strtotime(date('Y-m-d'));
                        $exp = strtotime($item['expiry_date']);
                        $diffDays = ceil(($exp - $now) / (60 * 60 * 24));
                        if ($diffDays <= 1) {
                            $status = 'Expired';
                            $statusClass = 'status-low';
                        } elseif ($diffDays <= 30) {
                            $status = 'Near Expiration';
                            $statusClass = 'status-near-expiry';
                        }
                    }
                    
                    // Check low stock (takes priority over near expiration)
                    if ($item['quantity'] <= $item['reorder_level'] && $status !== 'Expired') {
                        $status = 'Low Stock';
                        $statusClass = 'status-low';
                    }
                ?>
                <span class="status <?= $statusClass ?>"><?= $status ?></span>
            </td>
            <td><?= $expiryDate ?></td>
            <td><button class="btn btn-small edit-btn" data-inventory-id="<?= $item['inventory_id'] ?>">Edit</button></td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr><td colspan="8" style="text-align:center;padding:40px;">No inventory items found.</td></tr>
<?php endif; ?>
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
		// Populate inventoryData from server-side rendered data
		let inventoryData = <?php echo json_encode($inventory ?? []); ?>;
		
		// Inventory search filter
window.addEventListener('DOMContentLoaded', function() {
	const invSearch = document.getElementById('inventory-search');
	if (invSearch) {
		invSearch.addEventListener('input', function() {
			renderInventoryTable(this.value.trim().toLowerCase());
		});
	}

	// Edit buttons in inventory table
	document.querySelectorAll('.edit-btn').forEach(btn => {
		btn.addEventListener('click', function() {
			const id = this.getAttribute('data-inventory-id');
			if (id) editItem(id);
		});
	});

	// Stock Alerts: Add Stocks/Discard buttons
	document.querySelectorAll('.add-stock-btn').forEach(btn => {
		btn.addEventListener('click', function() {
			const id = this.getAttribute('data-inventory-id');
			if (id) editItem(id);
		});
	});
	
	document.querySelectorAll('.discard-btn').forEach(btn => {
		btn.addEventListener('click', function() {
			const id = this.getAttribute('data-inventory-id');
			if (id) {
				if (confirm('Are you sure you want to delete this expired item?')) {
					deleteItem(id);
				}
			}
		});
	});
});

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

		// Search functionality for server-side rendered table
		function renderInventoryTable(filterQuery = '') {
			const tbody = document.getElementById('inventory-tbody');
			const rows = tbody.getElementsByTagName('tr');
			
			for (let row of rows) {
				if (filterQuery === '') {
					row.style.display = '';
				} else {
					const text = row.textContent.toLowerCase();
					row.style.display = text.includes(filterQuery) ? '' : 'none';
				}
			}
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
				// Reload page to refresh server-side rendered content
				window.location.reload();
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
				// Reload page to refresh server-side rendered content
				window.location.reload();
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
				// Reload page to refresh server-side rendered content
				window.location.reload();
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
