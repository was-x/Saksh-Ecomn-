<?php
session_start();
define('BASE_PATH', dirname(__FILE__));
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/config/Database.php';

echo "<h1>Signup Test</h1>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $firstName = $_POST['first_name'] ?? '';
    $lastName = $_POST['last_name'] ?? '';
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';
    
    echo "<h2>Form Data Received:</h2>";
    echo "<p>Email: $email</p>";
    echo "<p>First Name: $firstName</p>";
    echo "<p>Last Name: $lastName</p>";
    echo "<p>Password: " . (strlen($password) > 0 ? "✓ Provided" : "✗ Empty") . "</p>";
    
    // Validation
    if (empty($email) || empty($firstName) || empty($lastName) || empty($password) || empty($passwordConfirm)) {
        echo "<p style='color: red;'>✗ All fields are required.</p>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p style='color: red;'>✗ Invalid email address.</p>";
    } elseif (strlen($password) < 8) {
        echo "<p style='color: red;'>✗ Password must be at least 8 characters long.</p>";
    } elseif ($password !== $passwordConfirm) {
        echo "<p style='color: red;'>✗ Passwords do not match.</p>";
    } else {
        echo "<h2>Validation Passed ✓</h2>";
        
        try {
            $db = new Database();
            
            // Check existing
            $existing = $db->query("SELECT id FROM users WHERE email = ?", [$email]);
            if (!empty($existing)) {
                echo "<p style='color: red;'>✗ Email already registered.</p>";
            } else {
                echo "<p style='color: green;'>✓ Email is available</p>";
                
                // Hash password
                $hashed_password = password_hash($password, PASSWORD_BCRYPT, ['cost' => HASH_COST]);
                echo "<p>✓ Password hashed</p>";
                
                // Insert
                $insert_result = $db->insert('users', [
                    'email' => $email,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'password' => $hashed_password,
                    'role' => 'user',
                    'status' => 'active'
                ]);
                
                if ($insert_result) {
                    echo "<p style='color: green;'>✓ User inserted successfully! ID: $insert_result</p>";
                    
                    // Retrieve
                    $user = $db->query("SELECT id, email, first_name, last_name FROM users WHERE email = ?", [$email]);
                    if ($user && !empty($user)) {
                        echo "<p style='color: green;'>✓ User retrieved from database</p>";
                        echo "<pre>" . json_encode($user[0], JSON_PRETTY_PRINT) . "</pre>";
                        
                        $_SESSION['user_id'] = $user[0]['id'];
                        $_SESSION['user_email'] = $user[0]['email'];
                        $_SESSION['user_name'] = $user[0]['first_name'] . ' ' . $user[0]['last_name'];
                        
                        echo "<p style='color: green;'>✓ Session data set. Redirecting in 2 seconds...</p>";
                        header('Refresh: 2; url=public_html/products.php');
                    } else {
                        echo "<p style='color: red;'>✗ Could not retrieve user from database</p>";
                    }
                } else {
                    echo "<p style='color: red;'>✗ Insert failed</p>";
                }
            }
        } catch (Exception $e) {
            echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
        }
    }
} else {
    ?>
    <form method="POST">
        <div style="margin: 10px 0;">
            <label>Email:</label>
            <input type="email" name="email" required>
        </div>
        <div style="margin: 10px 0;">
            <label>First Name:</label>
            <input type="text" name="first_name" required>
        </div>
        <div style="margin: 10px 0;">
            <label>Last Name:</label>
            <input type="text" name="last_name" required>
        </div>
        <div style="margin: 10px 0;">
            <label>Password:</label>
            <input type="password" name="password" required>
        </div>
        <div style="margin: 10px 0;">
            <label>Confirm Password:</label>
            <input type="password" name="password_confirm" required>
        </div>
        <button type="submit">Test Signup</button>
    </form>
    <?php
}
?>
