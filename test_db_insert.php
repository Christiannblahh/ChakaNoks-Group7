<?php
// Simple test to insert a transfer record
require_once 'system/bootstrap.php';

try {
    $db = \Config\Database::connect();

    $data = [
        'from_branch_id' => 1,
        'to_branch_id' => 2,
        'item_name' => 'Chicken',
        'quantity' => 10,
        'transfer_date' => date('Y-m-d H:i:s'),
        'approved_by' => 1
    ];

    $result = $db->table('transfers')->insert($data);

    echo "Test transfer created! Result: " . ($result ? "SUCCESS" : "FAILED") . "\n";

    // Verify the insert
    $transfers = $db->table('transfers')->get()->getResultArray();
    echo "Total transfers in database: " . count($transfers) . "\n";

    foreach ($transfers as $transfer) {
        echo "- Transfer ID: " . $transfer['transfer_id'] . " from branch " .
             $transfer['from_branch_id'] . " to branch " . $transfer['to_branch_id'] .
             " - " . $transfer['item_name'] . " (" . $transfer['quantity'] . ")\n";
    }

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
