<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .navbar {
            background-color: #00715D;
        }

        .navbar .nav-link {
            color: #fff !important;
            font-weight: 500;
        }

        .navbar .nav-link:hover {
            color: #FFD502 !important;
        }

        h1 {
            color: #00715D;
            font-weight: bold;
        }

        .table {
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .table thead {
            background-color: #00715D;
            color: #fff;
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .btn {
            font-weight: bold;
            text-transform: uppercase;
        }

        .btn-primary {
            background-color: #00715D;
            border: none;
        }

        .btn-primary:hover {
            background-color: #00503f;
        }

        .btn-danger {
            background-color: #FF4C4C;
            border: none;
        }

        .btn-danger:hover {
            background-color: #e53939;
        }

        .modal-header {
            background-color: #00715D;
            color: #fff;
        }

        .modal-footer {
            background-color: #f1f1f1;
        }

        footer {
            background-color: #00715D;
            color: #fff;
        }
    </style>
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

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: admin_login.php');
    exit;
}

// Fetch all payment methods
$payment_methods_query = "SELECT * FROM payment_methods";
$payment_methods_result = $conn->query($payment_methods_query);
?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Manage Payment Methods</h1>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Method</th>
                    <th>Details</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($method = $payment_methods_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $method['id'] ?></td>
                        <td><?= htmlspecialchars($method['method']) ?></td>
                        <td>
                            <?php if ($method['method'] === 'crypto'): ?>
                                <strong>Address:</strong> <?= htmlspecialchars($method['address'] ?? 'N/A') ?>
                            <?php elseif ($method['method'] === 'bank_transfer'): ?>
                                <strong>Bank Name:</strong> <?= htmlspecialchars($method['bank_name'] ?? 'N/A') ?><br>
                                <strong>Account Number:</strong> <?= htmlspecialchars($method['account_number'] ?? 'N/A') ?><br>
                                <strong>Sort Code:</strong> <?= htmlspecialchars($method['sort_code'] ?? 'N/A') ?><br>
                                <strong>Reference:</strong> <?= htmlspecialchars($method['reference'] ?? 'N/A') ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal"
                                    data-id="<?= $method['id'] ?>"
                                    data-method="<?= htmlspecialchars($method['method']) ?>"
                                    data-address="<?= htmlspecialchars($method['address'] ?? '') ?>"
                                    data-bank-name="<?= htmlspecialchars($method['bank_name'] ?? '') ?>"
                                    data-account-number="<?= htmlspecialchars($method['account_number'] ?? '') ?>"
                                    data-sort-code="<?= htmlspecialchars($method['sort_code'] ?? '') ?>"
                                    data-reference="<?= htmlspecialchars($method['reference'] ?? '') ?>">
                                Edit
                            </button>
                            <form action="delete_payment_method.php" method="POST" class="d-inline">
                                <input type="hidden" name="id" value="<?= $method['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <a href="admin_dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Payment Method</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST" action="update_payment_method.php">
                    <input type="hidden" id="id" name="id">
                    <div class="mb-3">
                        <label for="method" class="form-label">Method</label>
                        <input type="text" class="form-control" id="method" name="method" readonly>
                    </div>
                    <div id="cryptoFields" class="mb-3">
                        <label for="address" class="form-label">Crypto Address</label>
                        <input type="text" class="form-control" id="address" name="address">
                    </div>
                    <div id="bankFields" class="mb-3" style="display: none;">
                        <label for="bank_name" class="form-label">Bank Name</label>
                        <input type="text" class="form-control" id="bank_name" name="bank_name">
                        <label for="account_number" class="form-label">Account Number</label>
                        <input type="text" class="form-control" id="account_number" name="account_number">
                        <label for="sort_code" class="form-label">Sort Code</label>
                        <input type="text" class="form-control" id="sort_code" name="sort_code">
                        <label for="reference" class="form-label">Reference</label>
                        <input type="text" class="form-control" id="reference" name="reference">
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

</div>
<!--<footer class="bg-dark text-white text-center py-3 mt-4">-->
<!--    <p>Admin Panel &copy; 2024. All Rights Reserved.</p>-->
<!--</footer>-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Populate modal fields with data when the edit button is clicked
    document.querySelectorAll('[data-bs-toggle="modal"]').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.getAttribute('data-id');
            const method = button.getAttribute('data-method');
            const address = button.getAttribute('data-address');
            const bankName = button.getAttribute('data-bank-name');
            const accountNumber = button.getAttribute('data-account-number');
            const sortCode = button.getAttribute('data-sort-code');
            const reference = button.getAttribute('data-reference');

            document.getElementById('id').value = id;
            document.getElementById('method').value = method;

            if (method === 'crypto') {
                document.getElementById('cryptoFields').style.display = 'block';
                document.getElementById('bankFields').style.display = 'none';
                document.getElementById('address').value = address;
            } else if (method === 'bank_transfer') {
                document.getElementById('cryptoFields').style.display = 'none';
                document.getElementById('bankFields').style.display = 'block';
                document.getElementById('bank_name').value = bankName;
                document.getElementById('account_number').value = accountNumber;
                document.getElementById('sort_code').value = sortCode;
                document.getElementById('reference').value = reference;
            }
        });
    });
</script>
</body>
</html>
