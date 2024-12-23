<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connection.php';

header('Content-Type: application/json');

// Get the method parameter
$method = $_GET['method'] ?? '';
if (!$method) {
    echo json_encode(['success' => false, 'message' => 'Payment method not specified.']);
    exit;
}

// Determine the query based on the method
if ($method === 'crypto') {
    $query = "SELECT address FROM payment_methods WHERE method = 'crypto' LIMIT 1";
} elseif ($method === 'bank_transfer') {
    $query = "SELECT bank_name, account_number, sort_code, reference FROM payment_methods WHERE method = 'bank_transfer' LIMIT 1";
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid payment method.']);
    exit;
}

// Execute the query
$result = $conn->query($query);

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Database query failed: ' . $conn->error]);
    exit;
}

// Check if rows are returned
if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();

    // Return appropriate details based on the method
    if ($method === 'crypto') {
        echo json_encode(['success' => true, 'details' => $data['address']]);
    } elseif ($method === 'bank_transfer') {
        echo json_encode([
            'success' => true,
            'details' => [
                'bankName' => $data['bank_name'],
                'accountNumber' => $data['account_number'],
                'sortCode' => $data['sort_code'],
                'reference' => $data['reference']
            ]
        ]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No details found for the selected method.']);
}

$conn->close();
?>
