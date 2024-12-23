# Donation-website
A fully functional donation website with backend

# Charity Admin Panel

This is a web-based admin panel for managing a charity's donation system. The admin can:

- View and edit payment methods (crypto and bank transfer details).
- View, confirm, and delete transactions.
- Send confirmation emails to donors upon transaction approval (via PHPMailer).

## Features

### Admin Panel
1. **Admin Login**:
   - Secure login with session management.
2. **Dashboard**:
   - Overview with navigation to payment methods and transactions.
3. **Payment Methods Management**:
   - View and edit payment details.
4. **Transaction Management**:
   - View transactions.
   - Confirm transactions (sends email confirmation to donors).
   - Delete transactions.

### Responsive Design
- Built with Bootstrap for responsive and modern design.

## Installation

### Prerequisites
1. PHP 7.4 or above.
2. MySQL database.
3. Apache or Nginx web server.
4. PHPMailer library installed (`composer require phpmailer/phpmailer`).

### Setup Instructions
1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/charity-admin-panel.git
Navigate to the project directory:

bash
Copy code
cd charity-admin-panel
Configure the database:

Import the database.sql file to create necessary tables.
Update database credentials in donation/db_connection.php.
Configure email:

Update SMTP credentials in PHPMailer configuration inside admin_transactions.php.
Start your server and access the admin panel:

URL: http://yourdomain.com/admin_login.php.
Folder Structure
bash
Copy code
├── donation/                # Donation and payment backend
├── admin_dashboard.php      # Admin dashboard
├── admin_login.php          # Admin login page
├── admin_logout.php         # Admin logout functionality
├── admin_payment_methods.php # Manage payment methods
├── admin_transactions.php   # View and manage transactions
├── assets/                  # CSS, JS, and other static files
└── README.md                # Documentation

