<?php
define('BASE_PATH', dirname(__FILE__));
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/config/Database.php';

echo "<h1>Database Connection Test</h1>";

try {
    $db = new Database();
    echo "<p style='color: green;'>✓ Database connected successfully</p>";
    
    // Test query
    $result = $db->query("SELECT * FROM users LIMIT 1");
    echo "<p>✓ Users table accessible - Total users: " . count($result) . "</p>";
    
    // Test insert
    echo "<h2>Testing Insert</h2>";
    
    $test_email = 'test_' . time() . '@example.com';
    $test_data = [
        'email' => $test_email,
        'first_name' => 'Test',
        'last_name' => 'User',
        'password' => password_hash('TestPassword123', PASSWORD_BCRYPT),
        'role' => 'user',
        'status' => 'active'
    ];
    
    echo "<p>Attempting to insert user with email: <strong>$test_email</strong></p>";
    
    $insert_result = $db->insert('users', $test_data);
    
    if ($insert_result) {
        echo "<p style='color: green;'>✓ Insert successful! User ID: $insert_result</p>";
        
        // Verify
        $verify = $db->query("SELECT * FROM users WHERE email = ?", [$test_email]);
        if (!empty($verify)) {
            echo "<p style='color: green;'>✓ User verified in database</p>";
            echo "<pre>" . json_encode($verify[0], JSON_PRETTY_PRINT) . "</pre>";
        }
    } else {
        echo "<p style='color: red;'>✗ Insert failed</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>
