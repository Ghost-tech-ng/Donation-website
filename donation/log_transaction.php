<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connection.php';

header('Content-Type: application/json');

// Validate input
if (!isset($_POST['name'], $_POST['email'], $_POST['amount'], $_POST['payment_method'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
    exit;
}

$name = $_POST['name'];
$email = $_POST['email'];
$amount = $_POST['amount'];
$payment_method = $_POST['payment_method'];

// Prepare the SQL query
$query = "INSERT INTO transaction_history (name, email, amount, payment_method, created_at) VALUES (?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($query);
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Failed to prepare statement: ' . $conn->error]);
    exit;
}

$stmt->bind_param('ssis', $name, $email, $amount, $payment_method);

// Execute the query
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Transaction logged successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to execute query: ' . $stmt->error]);
}
?>
