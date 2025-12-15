<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Suppliers Management</title>
	<?= link_tag('css/dashboard.css') ?>
	<style>
		.supplier-card {
			border: 1px solid var(--border);
			border-radius: 8px;
			padding: 16px;
			background: white;
			margin-bottom: 12px;
		}
		.supplier-header {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 12px;
		}
		.supplier-info {
			display: grid;
			gap: 6px;
			color: #6b7280;
			font-size: 14px;
		}
		.form-grid {
			display: grid;
			grid-template-columns: 1fr 1fr;
			gap: 12px;
		}
		.form-grid.full { grid-template-columns: 1fr; }
		.input { padding: 8px; border: 1px solid var(--border); border-radius: 6px; font-size: 14px; }
		.btn { padding: 8px 16px; background: #111; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; }
		.btn:hover { background: #333; }
		.btn-secondary { background: #e5e7eb; color: #111; }
		.btn-secondary:hover { background: #d1d5db; }
		.modal {
			display: none;
			position: fixed;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background: rgba(0, 0, 0, 0.5);
			z-index: 1000;
			align-items: center;
			justify-content: center;
		}
		.modal.active { display: flex; }
		.modal-content {
			background: white;
			padding: 24px;
			border-radius: 8px;
			max-width: 600px;
			max-height: 90vh;
			overflow-y: auto;
		}
		.rating { color: #f59e0b; font-weight: 600; }
	</style>
</head>
<body>
	<header class="topnav">
		<div class="brand">ChakaNoks - Supplier Management</div>
		<nav class="navicons">
			<a href="<?= site_url('pages/notifications') ?>">Notifications</a>
			<a href="<?= site_url('pages/messages') ?>">Messages</a>
		</nav>
	</header>

	<div class="layout">
		<aside class="sidebar">
			<ul>
				<li><a href="<?= site_url('admin_dashboard') ?>">Dashboard</a></li>
				<li class="active">Suppliers</li>
				<li><a href="<?= site_url('pages/purchase_approvals') ?>">Approvals</a></li>
				<li><a href="<?= site_url('pages/reports') ?>">Reports</a></li>
			</ul>
			<a class="logout" href="<?= site_url('logout') ?>">Log Out</a>
		</aside>

		<main class="content">
			<h1>Supplier Management</h1>
			
			<button class="btn" onclick="openAddSupplierModal()" style="margin-bottom: 20px;">+ Add New Supplier</button>

			<div id="suppliersContainer" style="display: grid; gap: 12px;">
				<div style="text-align: center; color: #6b7280;">Loading suppliers...</div>
			</div>
		</main>
	</div>

	<!-- Add/Edit Supplier Modal -->
	<div id="supplierModal" class="modal">
		<div class="modal-content">
			<h2 id="modalTitle">Add New Supplier</h2>
			<form id="supplierForm" style="display: grid; gap: 12px; margin-top: 16px;">
				<input type="hidden" id="supplier_id">
				<div class="form-grid">
					<input type="text" id="supplier_name" placeholder="Supplier Name" class="input" required>
					<input type="email" id="email" placeholder="Email" class="input" required>
				</div>
				<div class="form-grid">
					<input type="text" id="contact_person" placeholder="Contact Person" class="input">
					<input type="tel" id="phone" placeholder="Phone Number" class="input">
				</div>
				<input type="text" id="address" placeholder="Street Address" class="input form-grid full">
				<div class="form-grid">
					<input type="text" id="city" placeholder="City" class="input">
					<input type="text" id="state" placeholder="State/Province" class="input">
				</div>
				<div class="form-grid">
					<input type="text" id="postal_code" placeholder="Postal Code" class="input">
					<input type="text" id="country" placeholder="Country" class="input">
				</div>
				<div>
					<select id="supplier_type" class="input" style="width: 100%;">
						<option value="Food">Food Supplier</option>
						<option value="Equipment">Equipment Supplier</option>
						<option value="Packaging">Packaging Supplier</option>
						<option value="Other">Other</option>
					</select>
				</div>
				<div style="display: flex; gap: 10px; margin-top: 12px;">
					<button type="button" class="btn" onclick="submitSupplierForm()">Save Supplier</button>
					<button type="button" class="btn btn-secondary" onclick="closeSupplierModal()">Cancel</button>
				</div>
			</form>
		</div>
	</div>

	<script>
		document.addEventListener('DOMContentLoaded', loadSuppliers);

		async function loadSuppliers() {
			try {
				const response = await fetch('<?= site_url("purchasing/suppliers") ?>');
				const suppliers = await response.json();
				const container = document.getElementById('suppliersContainer');
				container.innerHTML = '';

				if (suppliers.length === 0) {
					container.innerHTML = '<div style="text-align: center; color: #6b7280;">No suppliers found</div>';
					return;
				}

				for (const supplier of suppliers) {
					const statsResponse = await fetch(`<?= site_url("purchasing/suppliers") ?>/${supplier.supplier_id}/stats`);
					const stats = await statsResponse.json();

					const card = document.createElement('div');
					card.className = 'supplier-card';
					const ratingDisplay = supplier.rating ? parseFloat(supplier.rating).toFixed(1) : 'N/A';
					card.innerHTML = `
						<div class="supplier-header">
							<div>
								<h3>${supplier.supplier_name}</h3>
								<div class="rating">⭐ ${ratingDisplay}</div>
							</div>
							<button class="btn btn-secondary" onclick="editSupplier(${supplier.supplier_id})">Edit</button>
						</div>
						<div class="supplier-info">
							<strong>Contact:</strong> ${supplier.contact_person || 'N/A'} | ${supplier.phone || 'N/A'}<br>
							<strong>Email:</strong> <a href="mailto:${supplier.email}">${supplier.email}</a><br>
							<strong>Address:</strong> ${supplier.address || 'N/A'}, ${supplier.city || ''} ${supplier.state || ''}<br>
							<strong>Type:</strong> ${supplier.supplier_type}<br>
							<strong>Stats:</strong> ${stats.total_orders || 0} orders | ${stats.delivery_rate ? (stats.delivery_rate * 100).toFixed(0) : 0}% on-time
						</div>
					`;
					container.appendChild(card);
				}
			} catch (error) {
				console.error('Error loading suppliers:', error);
				document.getElementById('suppliersContainer').innerHTML = '<div style="color: red;">Error loading suppliers</div>';
			}
		}

		function openAddSupplierModal() {
			document.getElementById('supplier_id').value = '';
			document.getElementById('supplierForm').reset();
			document.getElementById('modalTitle').textContent = 'Add New Supplier';
			document.getElementById('supplierModal').classList.add('active');
		}

		async function editSupplier(supplierId) {
			try {
				const response = await fetch(`<?= site_url("purchasing/suppliers") ?>/${supplierId}`);
				const supplier = await response.json();
				
				document.getElementById('supplier_id').value = supplier.supplier_id;
				document.getElementById('supplier_name').value = supplier.supplier_name;
				document.getElementById('email').value = supplier.email;
				document.getElementById('contact_person').value = supplier.contact_person;
				document.getElementById('phone').value = supplier.phone;
				document.getElementById('address').value = supplier.address;
				document.getElementById('city').value = supplier.city;
				document.getElementById('state').value = supplier.state;
				document.getElementById('postal_code').value = supplier.postal_code;
				document.getElementById('country').value = supplier.country;
				document.getElementById('supplier_type').value = supplier.supplier_type;
				
				document.getElementById('modalTitle').textContent = 'Edit Supplier';
				document.getElementById('supplierModal').classList.add('active');
			} catch (error) {
				console.error('Error:', error);
				alert('Failed to load supplier details');
			}
		}

		async function submitSupplierForm() {
			const supplierId = document.getElementById('supplier_id').value;
			const supplierName = document.getElementById('supplier_name').value.trim();
			const email = document.getElementById('email').value.trim();
			
			if (!supplierName) {
				alert('Supplier name is required');
				return;
			}
			
			if (!email) {
				alert('Email is required');
				return;
			}
			
			if (!email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
				alert('Please enter a valid email address');
				return;
			}
			
			const data = {
				supplier_name: supplierName,
				email: email,
				contact_person: document.getElementById('contact_person').value.trim(),
				phone: document.getElementById('phone').value.trim(),
				address: document.getElementById('address').value.trim(),
				city: document.getElementById('city').value.trim(),
				state: document.getElementById('state').value.trim(),
				postal_code: document.getElementById('postal_code').value.trim(),
				country: document.getElementById('country').value.trim(),
				supplier_type: document.getElementById('supplier_type').value
			};

			try {
				const endpoint = supplierId 
					? `<?= site_url("purchasing/suppliers") ?>/${supplierId}/update`
					: '<?= site_url("purchasing/suppliers/create") ?>';

				const response = await fetch(endpoint, {
					method: 'POST',
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
					body: new URLSearchParams(data)
				});

				if (!response.ok) {
					const error = await response.json();
					alert('Error: ' + (error.message || error.messages || 'Failed to save supplier'));
					return;
				}
				
				const result = await response.json();
				if (result.success) {
					alert(supplierId ? 'Supplier updated successfully' : 'Supplier created successfully');
					closeSupplierModal();
					loadSuppliers();
				} else {
					alert('Error: ' + (result.message || 'Failed to save supplier'));
				}
			} catch (error) {
				console.error('Error:', error);
				alert('An error occurred');
			}
		}

		function closeSupplierModal() {
			document.getElementById('supplierModal').classList.remove('active');
		}
	</script>
</body>
</html>
