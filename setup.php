<?php
/**
 * ====================================================================
 * SAKSH ECOM - DATABASE SETUP (IMPROVED)
 * ====================================================================
 * Run this file once to set up the database properly
 * Access: http://localhost/Saksh%20Ecom/setup.php
 */

$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'saksh_ecom';

// Prevent re-setup after completion
$lock_file = __DIR__ . '/setup_complete.lock';

try {
    // Connect to MySQL
    $mysqli = new mysqli($db_host, $db_user, $db_pass);
    
    if ($mysqli->connect_error) {
        throw new Exception("Connection failed: " . $mysqli->connect_error);
    }

    // Create database
    $create_db = "CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    if (!$mysqli->query($create_db)) {
        throw new Exception("Database creation failed: " . $mysqli->error);
    }

    // Select database
    if (!$mysqli->select_db($db_name)) {
        throw new Exception("Database selection failed: " . $mysqli->error);
    }

    // Create USERS table
    $users_table = "
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        first_name VARCHAR(100) NOT NULL,
        last_name VARCHAR(100) NOT NULL,
        phone VARCHAR(20),
        avatar VARCHAR(255),
        role ENUM('user', 'admin', 'seller') DEFAULT 'user',
        status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
        email_verified BOOLEAN DEFAULT FALSE,
        email_verified_at DATETIME NULL,
        phone_verified BOOLEAN DEFAULT FALSE,
        phone_verified_at DATETIME NULL,
        two_factor_enabled BOOLEAN DEFAULT FALSE,
        two_factor_secret VARCHAR(255),
        last_login DATETIME NULL,
        remember_token VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        deleted_at DATETIME NULL,
        INDEX idx_email (email),
        INDEX idx_role (role),
        INDEX idx_status (status),
        INDEX idx_created_at (created_at)
    )";
    
    if (!$mysqli->query($users_table)) {
        throw new Exception("Users table creation failed: " . $mysqli->error);
    }

    // Create USER ADDRESSES table
    $addresses_table = "
    CREATE TABLE IF NOT EXISTS user_addresses (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        type ENUM('billing', 'shipping') DEFAULT 'shipping',
        first_name VARCHAR(100) NOT NULL,
        last_name VARCHAR(100) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        email VARCHAR(255),
        street_address VARCHAR(255) NOT NULL,
        apartment_number VARCHAR(50),
        city VARCHAR(100) NOT NULL,
        state VARCHAR(100) NOT NULL,
        postal_code VARCHAR(20) NOT NULL,
        country VARCHAR(100) NOT NULL,
        is_default BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        INDEX idx_user_id (user_id),
        INDEX idx_type (type)
    )";
    
    if (!$mysqli->query($addresses_table)) {
        throw new Exception("User addresses table creation failed: " . $mysqli->error);
    }

    // Create PRODUCTS table
    $products_table = "
    CREATE TABLE IF NOT EXISTS products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        slug VARCHAR(255) UNIQUE NOT NULL,
        description LONGTEXT,
        short_description VARCHAR(500),
        image VARCHAR(255),
        price DECIMAL(10, 2) NOT NULL,
        cost_price DECIMAL(10, 2),
        discount_percentage DECIMAL(5, 2) DEFAULT 0,
        final_price DECIMAL(10, 2) NOT NULL,
        stock_quantity INT DEFAULT 0,
        is_active BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_slug (slug),
        INDEX idx_is_active (is_active),
        FULLTEXT INDEX idx_search (name, description)
    )";
    
    if (!$mysqli->query($products_table)) {
        throw new Exception("Products table creation failed: " . $mysqli->error);
    }

    // Create ORDERS table
    $orders_table = "
    CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        order_number VARCHAR(50) UNIQUE NOT NULL,
        status ENUM('pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
        total_amount DECIMAL(12, 2) NOT NULL,
        subtotal DECIMAL(12, 2) NOT NULL,
        tax_amount DECIMAL(12, 2) DEFAULT 0,
        shipping_cost DECIMAL(12, 2) DEFAULT 0,
        discount_amount DECIMAL(12, 2) DEFAULT 0,
        payment_method VARCHAR(50),
        payment_status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
        shipping_address_id INT,
        billing_address_id INT,
        notes LONGTEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        delivered_at DATETIME NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        INDEX idx_user_id (user_id),
        INDEX idx_status (status),
        INDEX idx_created_at (created_at)
    )";
    
    if (!$mysqli->query($orders_table)) {
        throw new Exception("Orders table creation failed: " . $mysqli->error);
    }

    // Create ORDER ITEMS table
    $order_items_table = "
    CREATE TABLE IF NOT EXISTS order_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT NOT NULL,
        product_id INT NOT NULL,
        product_name VARCHAR(255) NOT NULL,
        quantity INT NOT NULL,
        unit_price DECIMAL(10, 2) NOT NULL,
        total_price DECIMAL(12, 2) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
        INDEX idx_order_id (order_id)
    )";
    
    if (!$mysqli->query($order_items_table)) {
        throw new Exception("Order items table creation failed: " . $mysqli->error);
    }

    // Create CATEGORIES table
    $categories_table = "
    CREATE TABLE IF NOT EXISTS categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        slug VARCHAR(255) UNIQUE NOT NULL,
        description TEXT,
        image VARCHAR(255),
        parent_id INT,
        is_active BOOLEAN DEFAULT TRUE,
        display_order INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL,
        INDEX idx_slug (slug),
        INDEX idx_parent_id (parent_id)
    )";
    
    if (!$mysqli->query($categories_table)) {
        throw new Exception("Categories table creation failed: " . $mysqli->error);
    }

    // Create REVIEWS table
    $reviews_table = "
    CREATE TABLE IF NOT EXISTS reviews (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        user_id INT NOT NULL,
        rating INT CHECK (rating >= 1 AND rating <= 5),
        comment LONGTEXT,
        is_verified BOOLEAN DEFAULT FALSE,
        is_approved BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        INDEX idx_product_id (product_id),
        INDEX idx_user_id (user_id)
    )";
    
    if (!$mysqli->query($reviews_table)) {
        throw new Exception("Reviews table creation failed: " . $mysqli->error);
    }

    // Create COUPONS table
    $coupons_table = "
    CREATE TABLE IF NOT EXISTS coupons (
        id INT AUTO_INCREMENT PRIMARY KEY,
        code VARCHAR(50) UNIQUE NOT NULL,
        description TEXT,
        discount_type ENUM('percentage', 'fixed') DEFAULT 'percentage',
        discount_value DECIMAL(12, 2) NOT NULL,
        min_order_value DECIMAL(12, 2) DEFAULT 0,
        max_usage INT DEFAULT NULL,
        current_usage INT DEFAULT 0,
        is_active BOOLEAN DEFAULT TRUE,
        valid_from DATETIME NOT NULL,
        valid_until DATETIME NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_code (code),
        INDEX idx_valid (valid_from, valid_until)
    )";
    
    if (!$mysqli->query($coupons_table)) {
        throw new Exception("Coupons table creation failed: " . $mysqli->error);
    }

    // Create SUPPORT TICKETS table
    $tickets_table = "
    CREATE TABLE IF NOT EXISTS support_tickets (
        id INT AUTO_INCREMENT PRIMARY KEY,
        ticket_number VARCHAR(50) UNIQUE NOT NULL,
        user_id INT NOT NULL,
        subject VARCHAR(255) NOT NULL,
        message LONGTEXT NOT NULL,
        status ENUM('open', 'in_progress', 'resolved', 'closed') DEFAULT 'open',
        priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
        assigned_to INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        resolved_at DATETIME NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        INDEX idx_user_id (user_id),
        INDEX idx_status (status)
    )";
    
    if (!$mysqli->query($tickets_table)) {
        throw new Exception("Support tickets table creation failed: " . $mysqli->error);
    }

    $mysqli->close();

    // Create lock file
    file_put_contents($lock_file, date('Y-m-d H:i:s'));

    // Display success message
    $success = true;

} catch (Exception $e) {
    $success = false;
    $error_msg = $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saksh Ecom - Database Setup</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            width: 100%;
        }
        .card {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        h1 {
            text-align: center;
            margin-bottom: 10px;
            color: #333;
        }
        .subtitle {
            text-align: center;
            color: #888;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .success-box {
            background: #d4edda;
            border: 2px solid #28a745;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .success-box h3 {
            color: #155724;
            margin-bottom: 10px;
        }
        .success-box p {
            color: #155724;
            margin: 5px 0;
        }
        .error-box {
            background: #f8d7da;
            border: 2px solid #dc3545;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .error-box h3 {
            color: #721c24;
            margin-bottom: 10px;
        }
        .error-box p {
            color: #721c24;
            margin: 5px 0;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            text-align: center;
            width: 100%;
            margin-top: 20px;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        .tables-list {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
        }
        .tables-list h4 {
            margin-bottom: 10px;
            color: #333;
            font-size: 14px;
        }
        .tables-list ul {
            list-style: none;
            padding: 0;
        }
        .tables-list li {
            padding: 5px 0;
            color: #666;
            font-size: 13px;
        }
        .tables-list li:before {
            content: "✓ ";
            color: #28a745;
            font-weight: bold;
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>🛒 Saksh Ecom</h1>
            <p class="subtitle">Database Setup</p>

            <?php if ($success): ?>
                <div class="success-box">
                    <h3>✓ Database Successfully Created!</h3>
                    <p><strong>Database Name:</strong> saksh_ecom</p>
                    <p><strong>Connection:</strong> localhost (root)</p>
                    <p><strong>Setup Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
                </div>

                <div class="tables-list">
                    <h4>📊 Tables Created (8 Total):</h4>
                    <ul>
                        <li>users</li>
                        <li>user_addresses</li>
                        <li>products</li>
                        <li>categories</li>
                        <li>orders</li>
                        <li>order_items</li>
                        <li>reviews</li>
                        <li>coupons</li>
                        <li>support_tickets</li>
                    </ul>
                </div>

                <p style="text-align: center; color: #666; margin: 20px 0;">
                    ✓ All columns properly created with correct data types<br>
                    ✓ Foreign key relationships established<br>
                    ✓ Indexes created for performance
                </p>

                <a href="public_html/" class="btn btn-primary">→ Go to Website</a>

            <?php else: ?>
                <div class="error-box">
                    <h3>✗ Setup Error</h3>
                    <p><strong>Error:</strong></p>
                    <p><?php echo htmlspecialchars($error_msg ?? 'Unknown error'); ?></p>
                    <p style="margin-top: 15px; font-weight: bold;">Troubleshooting:</p>
                    <ul style="margin-left: 15px; margin-top: 10px;">
                        <li style="list-style: disc;">Make sure XAMPP MySQL is running</li>
                        <li style="list-style: disc;">Check XAMPP Control Panel</li>
                        <li style="list-style: disc;">Port 3306 should be available</li>
                        <li style="list-style: disc;">Try refreshing this page</li>
                    </ul>
                </div>

                <p style="text-align: center; color: #666; margin: 20px 0;">
                    <strong>Next Steps:</strong><br>
                    1. Make sure MySQL is running in XAMPP<br>
                    2. Refresh this page (F5)<br>
                    3. Or contact support
                </p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
