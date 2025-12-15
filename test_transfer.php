<?php

// Test script to verify transfer functionality
require_once 'app/Models/TransferModel.php';
require_once 'app/Models/BranchModel.php';

// Test database connection
try {
    $db = \Config\Database::connect();
    echo "Database connection successful!\n";
} catch (\Exception $e) {
    echo "Database connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

// Test BranchModel
try {
    $branchModel = new \App\Models\BranchModel();
    $branches = $branchModel->getAllBranches();
    echo "Branches found: " . count($branches) . "\n";
    foreach ($branches as $branch) {
        echo "- " . $branch['branch_name'] . " (ID: " . $branch['branch_id'] . ")\n";
    }
} catch (\Exception $e) {
    echo "BranchModel test failed: " . $e->getMessage() . "\n";
}

// Test TransferModel
try {
    $transferModel = new \App\Models\TransferModel();

    // Create a test transfer
    $testTransfer = [
        'from_branch_id' => 1,
        'to_branch_id' => 2,
        'item_name' => 'Test Item',
        'quantity' => 5,
        'transfer_date' => date('Y-m-d H:i:s'),
        'approved_by' => 1
    ];

    $result = $transferModel->createTransfer($testTransfer);
    echo "Transfer creation result: " . ($result ? "SUCCESS" : "FAILED") . "\n";

    // Get recent transfers
    $transfers = $transferModel->getTransfersWithBranchNames();
    echo "Recent transfers found: " . count($transfers) . "\n";
    foreach ($transfers as $transfer) {
        echo "- Transfer ID: " . $transfer['transfer_id'] . " from " .
             $transfer['from_branch_name'] . " to " . $transfer['to_branch_name'] .
             " - " . $transfer['item_name'] . " (" . $transfer['quantity'] . ")\n";
    }

} catch (\Exception $e) {
    echo "TransferModel test failed: " . $e->getMessage() . "\n";
}

echo "Test completed!\n";
