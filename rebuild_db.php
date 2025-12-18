<?php
/**
 * ====================================================================
 * SAKSH ECOM - COMPLETE DATABASE REBUILD
 * ====================================================================
 * This will DROP and recreate the entire database from scratch
 */

$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'saksh_ecom';

echo "<h1>🛒 Saksh Ecom - Database Rebuild</h1>";
echo "<p>This will DROP and recreate the database...</p>";

try {
    // Connect to MySQL
    $mysqli = new mysqli($db_host, $db_user, $db_pass);
    
    if ($mysqli->connect_error) {
        throw new Exception("Connection failed: " . $mysqli->connect_error);
    }

    // DROP existing database
    echo "<p>Dropping existing database...</p>";
    $drop_db = "DROP DATABASE IF EXISTS `$db_name`";
    if (!$mysqli->query($drop_db)) {
        throw new Exception("Database drop failed: " . $mysqli->error);
    }
    echo "<p style='color: green;'>✓ Database dropped</p>";

    // Create fresh database
    echo "<p>Creating new database...</p>";
    $create_db = "CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    if (!$mysqli->query($create_db)) {
        throw new Exception("Database creation failed: " . $mysqli->error);
    }
    echo "<p style='color: green;'>✓ Database created</p>";

    // Select database
    if (!$mysqli->select_db($db_name)) {
        throw new Exception("Database selection failed: " . $mysqli->error);
    }

    // Create USERS table
    echo "<p>Creating users table...</p>";
    $users_table = "
    CREATE TABLE users (
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
    
    if (!$mysqli->query($users_table)) {
        throw new Exception("Users table creation failed: " . $mysqli->error);
    }
    echo "<p style='color: green;'>✓ Users table created</p>";

    // Create USER ADDRESSES table
    echo "<p>Creating user_addresses table...</p>";
    $addresses_table = "
    CREATE TABLE user_addresses (
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
    
    if (!$mysqli->query($addresses_table)) {
        throw new Exception("User addresses table creation failed: " . $mysqli->error);
    }
    echo "<p style='color: green;'>✓ User addresses table created</p>";

    // Create PRODUCTS table
    echo "<p>Creating products table...</p>";
    $products_table = "
    CREATE TABLE products (
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
        INDEX idx_is_active (is_active)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
    
    if (!$mysqli->query($products_table)) {
        throw new Exception("Products table creation failed: " . $mysqli->error);
    }
    echo "<p style='color: green;'>✓ Products table created</p>";

    // Create CATEGORIES table
    echo "<p>Creating categories table...</p>";
    $categories_table = "
    CREATE TABLE categories (
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
    
    if (!$mysqli->query($categories_table)) {
        throw new Exception("Categories table creation failed: " . $mysqli->error);
    }
    echo "<p style='color: green;'>✓ Categories table created</p>";

    // Create ORDERS table
    echo "<p>Creating orders table...</p>";
    $orders_table = "
    CREATE TABLE orders (
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
    
    if (!$mysqli->query($orders_table)) {
        throw new Exception("Orders table creation failed: " . $mysqli->error);
    }
    echo "<p style='color: green;'>✓ Orders table created</p>";

    // Create ORDER ITEMS table
    echo "<p>Creating order_items table...</p>";
    $order_items_table = "
    CREATE TABLE order_items (
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
    
    if (!$mysqli->query($order_items_table)) {
        throw new Exception("Order items table creation failed: " . $mysqli->error);
    }
    echo "<p style='color: green;'>✓ Order items table created</p>";

    // Create REVIEWS table
    echo "<p>Creating reviews table...</p>";
    $reviews_table = "
    CREATE TABLE reviews (
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
    
    if (!$mysqli->query($reviews_table)) {
        throw new Exception("Reviews table creation failed: " . $mysqli->error);
    }
    echo "<p style='color: green;'>✓ Reviews table created</p>";

    // Create COUPONS table
    echo "<p>Creating coupons table...</p>";
    $coupons_table = "
    CREATE TABLE coupons (
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
    
    if (!$mysqli->query($coupons_table)) {
        throw new Exception("Coupons table creation failed: " . $mysqli->error);
    }
    echo "<p style='color: green;'>✓ Coupons table created</p>";

    // Create SUPPORT TICKETS table
    echo "<p>Creating support_tickets table...</p>";
    $tickets_table = "
    CREATE TABLE support_tickets (
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ";
    
    if (!$mysqli->query($tickets_table)) {
        throw new Exception("Support tickets table creation failed: " . $mysqli->error);
    }
    echo "<p style='color: green;'>✓ Support tickets table created</p>";

    $mysqli->close();

    echo "<h2 style='color: green;'>✓ Database Successfully Rebuilt!</h2>";
    echo "<p>All 9 tables created with correct columns.</p>";
    echo "<p><a href='public_html/signup.php' style='background: #667eea; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 20px;'>Go to Signup →</a></p>";

} catch (Exception $e) {
    echo "<h2 style='color: red;'>✗ Error: " . $e->getMessage() . "</h2>";
    echo "<p>Please check:</p>";
    echo "<ul>";
    echo "<li>XAMPP MySQL is running</li>";
    echo "<li>Port 3306 is available</li>";
    echo "<li>Refresh page (F5) and try again</li>";
    echo "</ul>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Rebuild</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        h1, h2 {
            color: #333;
        }
        p {
            margin: 10px 0;
        }
    </style>
</head>
<body>
</body>
</html>
