<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .dashboard-title {
            color: #333;
            font-weight: bold;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card-icon {
            font-size: 2.5rem;
            color: #fff;
        }
        .card-header {
            border-radius: 12px 12px 0 0;
            color: #fff;
            font-weight: bold;
        }
        .card-body {
            text-align: center;
        }
        .card-footer {
            background-color: transparent;
            border-top: none;
        }
        .logout-card {
            background-color: #dc3545;
        }
        .logout-card .card-icon {
            color: #fff;
        }
        .payment-card {
            background-color: #00715D;
        }
        .transactions-card {
            background-color: #FFD502;
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

<div class="container mt-5">
    <h1 class="text-center mb-4 dashboard-title">Welcome to the Admin Dashboard</h1>
    <div class="row gy-4">
        <!-- Payment Methods Card -->
        <div class="col-lg-4 col-md-6">
            <div class="card payment-card">
                <div class="card-header text-center">
                    <i class="bi bi-wallet2 card-icon"></i>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Manage Payment Methods</h5>
                    <p class="card-text">Add, edit, or delete payment options available to donors.</p>
                </div>
                <div class="card-footer text-center">
                    <a href="admin_payment_methods.php" class="btn btn-light w-100">Go to Payment Methods</a>
                </div>
            </div>
        </div>

        <!-- Transactions Card -->
        <div class="col-lg-4 col-md-6">
            <div class="card transactions-card">
                <div class="card-header text-center">
                    <i class="bi bi-receipt card-icon"></i>
                </div>
                <div class="card-body">
                    <h5 class="card-title">View Transactions</h5>
                    <p class="card-text">Track and manage all donations and financial records.</p>
                </div>
                <div class="card-footer text-center">
                    <a href="admin_transactions.php" class="btn btn-dark w-100">View Transactions</a>
                </div>
            </div>
        </div>

        <!-- Logout Card -->
        <div class="col-lg-4 col-md-6">
            <div class="card logout-card">
                <div class="card-header text-center">
                    <i class="bi bi-box-arrow-right card-icon"></i>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Logout</h5>
                    <p class="card-text">End your session and securely log out of the admin panel.</p>
                </div>
                <div class="card-footer text-center">
                    <a href="admin_logout.php" class="btn btn-light w-100">Logout</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!--<footer class="bg-dark text-white text-center py-3 mt-4">-->
<!--    <p>Admin Panel &copy; 2024. All Rights Reserved.</p>-->
<!--</footer>-->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>
</body>
</html>
