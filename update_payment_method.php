<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'donation/db_connection.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: admin_login.php');
    exit;
}

// Check if the required POST data is provided
if (!isset($_POST['id'], $_POST['method'])) {
    $_SESSION['error_message'] = 'Invalid request. Missing required data.';
    header('Location: admin_payment.php');
    exit;
}

$id = intval($_POST['id']);
$method = $_POST['method'];
$success = false;

// Process based on the method type
if ($method === 'crypto') {
    $address = $_POST['address'] ?? '';
    $stmt = $conn->prepare("UPDATE payment_methods SET address = ?, bank_name = NULL, account_number = NULL, sort_code = NULL, reference = NULL WHERE id = ?");
    $stmt->bind_param('si', $address, $id);
    $success = $stmt->execute();
} elseif ($method === 'bank_transfer') {
    $bankName = $_POST['bank_name'] ?? '';
    $accountNumber = $_POST['account_number'] ?? '';
    $sortCode = $_POST['sort_code'] ?? '';
    $reference = $_POST['reference'] ?? '';

    $stmt = $conn->prepare("UPDATE payment_methods SET address = NULL, bank_name = ?, account_number = ?, sort_code = ?, reference = ? WHERE id = ?");
    $stmt->bind_param('ssssi', $bankName, $accountNumber, $sortCode, $reference, $id);
    $success = $stmt->execute();
} else {
    $_SESSION['error_message'] = 'Unsupported payment method.';
    header('Location: admin_payment.php');
    exit;
}

// Handle result of the update
if ($success) {
    $_SESSION['success_message'] = 'Payment method updated successfully.';
} else {
    $_SESSION['error_message'] = 'Failed to update payment method. Please try again.';
}

// Redirect back to the payment methods page
header('Location: admin_payment_methods.php');
exit;
?>
