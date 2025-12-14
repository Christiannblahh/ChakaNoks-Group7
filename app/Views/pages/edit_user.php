<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <?= link_tag('css/dashboard.css') ?>
</head>
<body>
    <header class="topnav">
        <div class="brand">ChakaNoks</div>
        <nav class="navicons">
            <a href="<?= site_url('pages/users') ?>">Back to Users</a>
        </nav>
    </header>
    <main style="max-width:340px;margin:48px auto 0 auto;padding:32px 24px;background:#fff;border-radius:10px;box-shadow:0 2px 16px rgba(0,0,0,0.06);">
    <h2 style="font-size:1.3em;font-weight:600;text-align:center;margin-bottom:18px;">Edit User</h2>
    <?php if (isset($user)): ?>
    <form method="post" action="<?= site_url('pages/users/edit/' . $user['user_id']) ?>" style="display:flex;flex-direction:column;gap:18px;">
        <div>
            <label for="edit-name" style="display:block;margin-bottom:5px;font-weight:500;">Full Name</label>
            <input id="edit-name" name="name" type="text" value="<?= esc($user['fname'] . ' ' . $user['lname']) ?>" placeholder="Full name" style="width:100%;padding:8px 11px;border:1px solid #e0e0e0;border-radius:6px;background:#f8fafc;font-size:1em;">
        </div>
        <div>
            <label for="edit-email" style="display:block;margin-bottom:5px;font-weight:500;">Email</label>
            <input id="edit-email" name="email" type="email" value="<?= esc($user['email']) ?>" placeholder="Email" style="width:100%;padding:8px 11px;border:1px solid #e0e0e0;border-radius:6px;background:#f8fafc;font-size:1em;">
        </div>
        <div>
            <label for="edit-role" style="display:block;margin-bottom:5px;font-weight:500;">Role</label>
            <select id="edit-role" name="role" style="width:100%;padding:8px 11px;border:1px solid #e0e0e0;border-radius:6px;background:#f8fafc;font-size:1em;">
                <option value="">Select role</option>
                <option<?= $user['role']==='System Admin'?' selected':'' ?>>System Admin</option>
                <option<?= $user['role']==='Central Admin'?' selected':'' ?>>Central Admin</option>
                <option<?= $user['role']==='Inventory Staff'?' selected':'' ?>>Inventory Staff</option>
                <option<?= $user['role']==='Branch Manager'?' selected':'' ?>>Branch Manager</option>
                <option<?= $user['role']==='Franchise Manager'?' selected':'' ?>>Franchise Manager</option>
                <option<?= $user['role']==='Supplier'?' selected':'' ?>>Supplier</option>
                <option<?= $user['role']==='Logistics Coordinator'?' selected':'' ?>>Logistics Coordinator</option>
            </select>
        </div>
        <button type="submit" style="margin-top:8px;width:100%;background:#2563eb;color:#fff;font-weight:600;font-size:1.07em;padding:9px 0;border:none;border-radius:6px;cursor:pointer;">Save Changes</button>
    </form>
    <?php else: ?>
        <p style="text-align:center;color:#888;">User not found.</p>
    <?php endif; ?>
</main>
</body>
</html>
