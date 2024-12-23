<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="admin_dashboard.php">Admin Panel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="admin_payment_methods.php">Payment Methods</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_transactions.php">Transactions</a></li>
                <li class="nav-item"><a class="nav-link text-danger" href="admin_logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-4">
<?php
session_start();
include 'donation/db_connection.php';

// Include PHPMailer files
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: admin_login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm_transaction'])) {
    $transaction_id = (int) $_POST['transaction_id'];

    // Fetch transaction details
    $query = "SELECT * FROM transaction_history WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $transaction_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $transaction = $result->fetch_assoc();

        // Mark the transaction as confirmed
        $update_query = "UPDATE transaction_history SET confirmed = 1 WHERE id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param('i', $transaction_id);
        $update_stmt->execute();

        // Send email to the user
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.hostinger.com';
            $mail->SMTPAuth = true;
            $mail->Username = '';
            $mail->Password = '';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('', '');
            $mail->addAddress($transaction['email']);

            $mail->isHTML(true);
            $mail->Subject = 'Thank You for Your Donation!';
            $mail->Body = "
                <h1>Thank You!</h1>
                <p>Dear {$transaction['name']},</p>
                <p>We sincerely appreciate your generous donation of <strong>\${$transaction['amount']}</strong> via <strong>{$transaction['payment_method']}</strong>.</p>
                <p>Your support means the world to us!</p>
                <p>Warm regards,</p>
                <p>The Team</p>
            ";

            $mail->send();

            $_SESSION['success_message'] = 'Transaction confirmed and email sent to the user!';
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    $stmt->close();
}

    if (isset($_POST['delete_transaction'])) {
        $transaction_id = (int) $_POST['transaction_id'];

        // Delete transaction
        $delete_query = "DELETE FROM transaction_history WHERE id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param('i', $transaction_id);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = 'Transaction deleted successfully!';
        } else {
            $_SESSION['error_message'] = 'Failed to delete the transaction.';
        }

        $stmt->close();
    }

    $conn->close();
    header('Location: admin_transactions.php');
    exit;
}

$transactions_query = "SELECT * FROM transaction_history";
$transactions_result = $conn->query($transactions_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Transactions</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success_message'] ?></div>
        <?php unset($_SESSION['success_message']); ?>
    <?php elseif (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error_message'] ?></div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <h1 class="text-center">Transaction History</h1>
<div class="table-responsive">
    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Amount</th>
                <th>Payment Method</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($transaction = $transactions_result->fetch_assoc()): ?>
            <tr>
                <td><?= $transaction['id'] ?></td>
                <td><?= htmlspecialchars($transaction['name']) ?></td>
                <td><?= htmlspecialchars($transaction['email']) ?></td>
                <td>$<?= number_format($transaction['amount'], 2) ?></td>
                <td><?= htmlspecialchars($transaction['payment_method']) ?></td>
                <td><?= htmlspecialchars($transaction['created_at']) ?></td>
                <td>
                    <?php if ($transaction['confirmed'] == 0): ?>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="transaction_id" value="<?= $transaction['id'] ?>">
                            <button type="submit" name="confirm_transaction" class="btn btn-success btn-sm">Confirm</button>
                        </form>
                    <?php else: ?>
                        <span class="badge bg-success">Confirmed</span>
                    <?php endif; ?>
                    <form method="POST" class="d-inline">
                        <input type="hidden" name="transaction_id" value="<?= $transaction['id'] ?>">
                        <button type="submit" name="delete_transaction" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
    <a href="admin_dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
