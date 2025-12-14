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
            <div class="row" style="display:flex;flex-direction:row;gap:24px;justify-content:center;align-items:flex-start;max-width:1100px;margin:0 auto;width:100%;">
                <section class="card" style="min-width:200px;max-width:240px;padding:12px 10px;">
    <h2 style="font-size:1.1em;margin-bottom:8px;">Create User</h2>
    <form method="post" action="<?= site_url('pages/users/create') ?>" style="margin-top:4px;display:grid;gap:6px;max-width:220px">
        <input name="name" type="text" placeholder="Full name" class="input" style="height:28px;font-size:0.95em;">
        <input name="email" type="email" placeholder="Email" class="input" style="height:28px;font-size:0.95em;">
        <input name="password" type="password" placeholder="Password" class="input" style="height:28px;font-size:0.95em;">
        <select name="role" class="input" style="height:28px;font-size:0.95em;">
            <option value="">Select role</option>
            <option>System Admin</option>
            <option>Central Admin</option>
            <option>Inventory Staff</option>
            <option>Branch Manager</option>
            <option>Franchise Manager</option>
            <option>Supplier</option>
            <option>Logistics Coordinator</option>
        </select>
        <button class="btn" type="submit" style="width:90px;height:28px;font-size:0.95em;">Create</button>
    </form>
</section>
                <section class="card table-card" style="flex:1;overflow-x:auto;min-width:0;">
    <div class="table-head">
        <h2>All Users</h2>
        <a class="add-link" href="#">Export CSV</a>
    </div>
    <div style="overflow-x:auto;width:100%">
    <table class="table" style="min-width:600px;width:100%;table-layout:auto;">
        <thead>
            <tr>
                <th style="white-space:nowrap;">Name</th>
                <th style="white-space:nowrap;">Email</th>
                <th style="white-space:nowrap;">Role</th>
                <th style="white-space:nowrap;">Status</th>
                <th style="white-space:nowrap;">Actions</th>
            </tr>
        </thead>
        <tbody>
                        <?php if (isset($users) && $users): ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= esc($user['fname'] . ' ' . $user['lname']) ?></td>
                                    <td><?= esc($user['email']) ?></td>
                                    <td><?= esc($user['role']) ?></td>
                                    <td><?= isset($user['status']) ? esc($user['status']) : 'Active' ?></td>
                                    <td>
<?php if ($user['deleted_at']): ?>
    <form method="post" action="<?= site_url('pages/users/restore/' . $user['user_id']) ?>" style="display:inline">
        <button class="btn btn-small" style="background:#059669;color:#fff;font-weight:500;border:none;padding:4px 12px;border-radius:4px;" type="submit">Restore</button>
    </form>
<?php elseif (session()->get('user_id') != $user['user_id']): ?>
    <a href="<?= site_url('pages/users/edit/' . $user['user_id']) ?>" class="btn btn-small" style="background:#2563eb;color:#fff;font-weight:500;border:none;padding:4px 12px;border-radius:4px;text-decoration:none;">Edit</a>
    <button class="btn btn-small btn-danger user-delete-btn" type="button" data-user-id="<?= $user['user_id'] ?>">Delete</button>
<?php else: ?>
    <span style="color:#888;font-size:0.98em;">(You)</span>
<?php endif; ?>
</td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5">No users found.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </section>
            </div>
        </main>
    </div>
</body>
<div id="toast-container" style="position:fixed;bottom:36px;right:36px;display:flex;flex-direction:column;gap:12px;z-index:9999;pointer-events:none;"></div>
<script>
function showToast(msg, success=true) {
    var container = document.getElementById('toast-container');
    var toast = document.createElement('div');
    toast.innerText = msg;
    toast.style.background = success ? '#059669' : '#dc2626';
    toast.style.color = '#fff';
    toast.style.padding = '12px 22px';
    toast.style.borderRadius = '8px';
    toast.style.fontSize = '1em';
    toast.style.boxShadow = '0 4px 24px rgba(0,0,0,0.12)';
    toast.style.opacity = 0;
    toast.style.transform = 'translateY(30px)';
    toast.style.transition = 'opacity .3s, transform .3s';
    toast.style.pointerEvents = 'auto';
    container.appendChild(toast);
    setTimeout(function() {
        toast.style.opacity = 1;
        toast.style.transform = 'translateY(0)';
    }, 10);
    setTimeout(function() {
        toast.style.opacity = 0;
        toast.style.transform = 'translateY(30px)';
        setTimeout(function() { container.removeChild(toast); }, 350);
    }, 2200);
}
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.user-delete-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            if (!confirm('Delete this user?')) return;
            var userId = btn.getAttribute('data-user-id');
            fetch('<?= site_url('pages/users/delete/') ?>' + userId, {
                method: 'POST',
                headers: {'X-Requested-With': 'XMLHttpRequest'},
            }).then(function(resp) {
                return resp.json();
            }).then(function(json) {
                if (!json.success) throw new Error(json.error || 'Failed');
                // Mark row as deleted (show Restore button)
                var row = btn.closest('tr');
                row.querySelectorAll('td').forEach(function(td, idx) {
                    if (idx === 4) {
                        td.innerHTML = `<form method=\"post\" action=\"<?= site_url('pages/users/restore/') ?>${userId}\" style=\"display:inline\"><button class=\"btn btn-small\" style=\"background:#059669;color:#fff;font-weight:500;border:none;padding:4px 12px;border-radius:4px;\" type=\"submit\">Restore</button></form>`;
                    }
                });
                showToast('User deleted successfully.', true);
            }).catch(function(e) {
                showToast((e && e.message) || 'Could not delete user.', false);
            });
        });
    });
});
</script>
<?php if (session()->getFlashdata('created')): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        showToast('<?= esc(session()->getFlashdata('created')) ?>', true);
    }, 100);
});
</script>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        showToast('<?= esc(session()->getFlashdata('error')) ?>', false);
    }, 100);
});
</script>
<?php endif; ?>
</html>
