<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stocks Records</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <?= link_tag('css/dashboard.css') ?>
</head>
<body>
    <header class="topnav">
        <div class="brand">ChakaNoks</div>
        <div class="search">
            <input type="text" placeholder="Search stock records...">
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
                <li><a href="<?= site_url('pages/inventory') ?>">Inventory</a></li>
                <li class="active">Stocks Records</li>
                <li><a href="<?= site_url('pages/reports') ?>">Reports</a></li>
            </ul>
            <button class="logout" onclick="window.location.href='<?= site_url('logout') ?>'">Log Out</button>
        </aside>

        <main class="content">
            <h1>Stocks Records</h1>
            <section class="card">
                <h2>All Stock Activity</h2>
                <div id="stock-records-table-container">
                    <table class="table" id="stock-records-table">
                        <thead>
                            <tr>
                                <th>Date/Time</th>
                                <th>Item Name</th>
                                <th>Action</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody id="stock-records-tbody">
                        <?php
                        $records = model('App\\Models\\StockRecordModel')->orderBy('datetime', 'DESC')->findAll();
                        if (!$records): ?>
                            <tr><td colspan="4" style="text-align:center;padding:40px;">No records found.</td></tr>
                        <?php else:
                            foreach ($records as $r): ?>
                                <tr>
                                    <td><?= esc($r['datetime']) ?></td>
                                    <td><?= esc($r['item_name']) ?></td>
                                    <td><?= esc($r['action']) ?></td>
                                    <td><?= esc($r['details']) ?></td>
                                </tr>
                        <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

</body>
</html>
