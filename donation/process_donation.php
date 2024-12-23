<?php
// process_donation.php

// Step 1: Validate the Request Method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Step 2: Sanitize Inputs
    $amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);
    $name = htmlspecialchars($_POST['name']);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $address = htmlspecialchars($_POST['address']);
    $category = htmlspecialchars($_POST['category']);
    $payment_method = htmlspecialchars($_POST['payment_method']);

    // Step 3: Validate Required Fields
    if (!$amount || !$name || !$email || !$payment_method) {
        die('Error: All required fields must be filled out.');
    }

    // Step 4: Connect to the Database
    $host = 'localhost';
    $db = 'donations_db';
    $user = 'root';
    $password = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Step 5: Insert Donation Details into the Database
        $stmt = $pdo->prepare("
            INSERT INTO donations (amount, name, email, address, category, payment_method, created_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([$amount, $name, $email, $address, $category, $payment_method]);

        // Step 6: Retrieve Payment Details for the Selected Method
        $paymentStmt = $pdo->prepare("SELECT details FROM payment_methods WHERE method = ?");
        $paymentStmt->execute([$payment_method]);
        $paymentDetails = $paymentStmt->fetchColumn();

        if (!$paymentDetails) {
            die('Error: Payment details not available for the selected method.');
        }

        // Step 7: Display Confirmation
        echo "<h1>Thank You for Your Donation!</h1>";
        echo "<p>Amount: \$$amount</p>";
        echo "<p>Payment Method: $payment_method</p>";
        echo "<p>Payment Details: $paymentDetails</p>";
    } catch (PDOException $e) {
        die('Database error: ' . $e->getMessage());
    }
} else {
    die('Invalid Request Method.');
}
?>
