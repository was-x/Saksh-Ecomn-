# Saksh Ecom - Troubleshooting & Development Guide

## 🆘 Troubleshooting Guide

### Common Issues & Solutions

---

## Database Connection Issues

### Problem: "Error connecting to database"

**Symptoms:**
- "No connection could be made because the target machine actively refused it"
- Blank white page
- All pages show connection error

**Solutions:**

1. **Check MySQL Service**
   ```bash
   # Windows - Check XAMPP
   Open XAMPP Control Panel
   Verify MySQL module is running (green light)
   
   # Linux/Mac
   sudo service mysql status
   sudo systemctl start mysql
   ```

2. **Verify Database Credentials**
   ```bash
   # In config/config.php, check:
   define('DB_HOST', 'localhost');    // Correct hostname
   define('DB_USER', 'root');          // Correct username
   define('DB_PASS', '');              // Correct password
   define('DB_NAME', 'saksh_ecom');   // Database exists
   ```

3. **Test Connection**
   ```bash
   # Create test_db.php
   <?php
   try {
       $pdo = new PDO('mysql:host=localhost;dbname=saksh_ecom', 'root', '');
       echo "Connected successfully!";
   } catch (PDOException $e) {
       echo "Error: " . $e->getMessage();
   }
   ?>
   ```

4. **Recreate Database**
   ```bash
   # In phpMyAdmin or command line:
   DROP DATABASE IF EXISTS saksh_ecom;
   CREATE DATABASE saksh_ecom;
   USE saksh_ecom;
   # Import schema.sql again
   ```

---

## File Upload Issues

### Problem: "File upload failed" or "Upload not working"

**Symptoms:**
- Upload button doesn't work
- No error message displayed
- Files not appearing in uploads folder

**Solutions:**

1. **Check Folder Permissions**
   ```bash
   # Windows
   Right-click public/uploads → Properties → Security → Full Control
   
   # Linux/Mac
   sudo chmod -R 777 public/uploads/
   sudo chown -R www-data:www-data public/uploads/
   ```

2. **Check PHP Configuration**
   ```bash
   # Find php.ini location
   php -r "echo php_ini_loaded_file();"
   
   # Edit php.ini and ensure:
   file_uploads = On
   upload_max_filesize = 256M
   post_max_size = 256M
   max_file_uploads = 20
   ```

3. **Check Upload Directory**
   ```bash
   # Verify directory exists
   ls -la public/uploads/
   
   # Create if missing
   mkdir -p public/uploads
   chmod 755 public/uploads
   ```

4. **Enable Error Reporting**
   ```php
   // In config/config.php, add:
   error_reporting(E_ALL);
   ini_set('display_errors', 1);
   ini_set('upload_tmp_dir', '/tmp');
   ```

---

## Session & Login Issues

### Problem: "User keeps getting logged out" or "Login not working"

**Symptoms:**
- Session expires immediately
- Login page shows, but credentials don't work
- Redirect loops between login and homepage
- Session files not being created

**Solutions:**

1. **Check Session Directory Permissions**
   ```bash
   # Find PHP session directory
   php -r "echo ini_get('session.save_path');"
   
   # Make writable
   sudo chmod 777 /var/lib/php/sessions/
   ```

2. **Verify Session Configuration**
   ```php
   // In config/config.php
   ini_set('session.cookie_lifetime', 86400);  // 24 hours
   ini_set('session.gc_maxlifetime', 86400);
   session_start();
   ```

3. **Check Browser Cookies**
   - Open DevTools (F12)
   - Check Application → Cookies
   - Verify PHPSESSID cookie exists
   - Check "SameSite" attribute if needed

4. **Test Login Flow**
   ```php
   // After login, check:
   echo $_SESSION['user_id'];      // Should show user ID
   echo $_SESSION['user_email'];   // Should show email
   ```

5. **Clear Session Files**
   ```bash
   # Remove old session files
   sudo rm -f /var/lib/php/sessions/sess_*
   
   # Restart Apache/PHP
   sudo service apache2 restart
   ```

---

## Payment Gateway Issues

### Problem: "Payment processing failed" or "Gateway not responding"

**Symptoms:**
- Checkout page shows but payment button doesn't work
- Error message about API keys
- Payment verification fails
- Webhook not receiving

**Solutions:**

1. **Verify API Keys**
   ```php
   // In config/config.php, check:
   define('RAZORPAY_KEY_ID', 'YOUR_KEY_ID');          // Not empty
   define('RAZORPAY_KEY_SECRET', 'YOUR_KEY_SECRET');  // Not empty
   ```

2. **Check Sandbox vs Live Mode**
   ```php
   // Ensure using sandbox credentials for testing:
   // Razorpay: rzp_test_* keys (not rzp_live_*)
   // Stripe: pk_test_* keys (not pk_live_*)
   // PayPal: sandbox.paypal.com (not www.paypal.com)
   ```

3. **Test API Connection**
   ```php
   // Test Razorpay API
   $ch = curl_init('https://api.razorpay.com/v1/orders');
   curl_setopt($ch, CURLOPT_USERPWD, KEY_ID . ":" . KEY_SECRET);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   $response = curl_exec($ch);
   echo $response;  // Should show response, not error
   ```

4. **Check Firewall/Network**
   ```bash
   # Test if outbound connections work
   curl -I https://api.razorpay.com/v1/orders
   
   # Should return: HTTP/1.1 401 Unauthorized
   # (401 is expected without auth, means gateway reachable)
   ```

5. **Enable Debug Logging**
   ```php
   // In api/products.php
   error_log("Payment attempt: " . json_encode($_POST));
   error_log("Payment response: " . curl_exec($ch));
   ```

---

## Frontend Display Issues

### Problem: "Styles not loading" or "Page looks broken"

**Symptoms:**
- Page shows unstyled content
- Colors and layout are off
- Buttons and forms look wrong
- CSS changes not showing

**Solutions:**

1. **Clear Browser Cache**
   ```bash
   # Chrome/Edge: Ctrl+Shift+Delete
   # Firefox: Ctrl+Shift+Delete
   # Safari: Cmd+Shift+Delete
   # Or use Incognito/Private mode
   ```

2. **Check CSS File Path**
   ```html
   <!-- In HTML head, verify path is correct: -->
   <link rel="stylesheet" href="../public/css/styles.css">
   
   <!-- Should be relative to public_html folder -->
   ```

3. **Verify CSS File Exists**
   ```bash
   ls -la public/css/styles.css
   
   # If missing, recreate it
   # Or check if file permissions are restricted (chmod 644)
   ```

4. **Check Browser Console**
   - Open DevTools (F12)
   - Check Console tab for errors
   - Check Network tab for failed CSS load
   - Look for 404 errors

5. **Force Reload CSS**
   ```bash
   # Update config to force refresh:
   // In index.php
   <link rel="stylesheet" href="../public/css/styles.css?v=<?php echo time(); ?>">
   ```

---

## Dark Mode Issues

### Problem: "Dark mode not working" or "Theme toggle not saving"

**Symptoms:**
- Toggle button doesn't work
- Dark mode doesn't apply
- Theme resets on page reload
- Styles don't change when toggling

**Solutions:**

1. **Check localStorage**
   ```javascript
   // In browser console (F12):
   localStorage.getItem('theme');  // Should show 'dark' or 'light'
   localStorage.setItem('theme', 'dark');  // Test set
   localStorage.clear();  // Reset if corrupted
   ```

2. **Verify JavaScript Loading**
   ```bash
   # Check if app.js is loaded:
   # Open DevTools → Network tab
   # Reload page and look for app.js
   # Should show 200 status and content
   ```

3. **Check CSS Variables**
   ```css
   /* In public/css/styles.css */
   :root {
       --light-bg: #ffffff;
       /* ... other variables ... */
   }
   
   [data-theme="dark"] {
       --light-bg: #1f2937;
       /* ... other variables ... */
   }
   ```

4. **Test Theme Manager**
   ```javascript
   // In browser console:
   themeManager.toggle();  // Should switch theme
   themeManager.set('dark');  // Should set to dark
   ```

5. **Reset Theme**
   ```javascript
   // In browser console:
   localStorage.removeItem('theme');
   location.reload();
   ```

---

## API Response Issues

### Problem: "API returns error" or "Response is empty"

**Symptoms:**
- API shows `{"success": false}`
- No products loading
- Cart operations fail
- Checkout errors

**Solutions:**

1. **Check Request Method**
   ```php
   // Ensure correct method:
   // GET: For retrieving data
   // POST: For creating/modifying data
   ```

2. **Verify Parameters**
   ```javascript
   // Check if required params are sent:
   // GET: ?action=list&page=1
   // POST: {product_id: 1, quantity: 1}
   ```

3. **Check API Response**
   ```javascript
   // In browser console:
   fetch('/api/products.php?action=list')
       .then(r => r.json())
       .then(d => console.log(d));
   ```

4. **Enable API Logging**
   ```php
   // In api/products.php (top)
   error_log("API Request: " . $_GET['action'] ?? $_POST['action'] ?? 'unknown');
   error_log("Parameters: " . json_encode($_GET + $_POST));
   ```

5. **Test with cURL**
   ```bash
   # Test API endpoint
   curl -X GET "http://localhost/Saksh%20Ecom/public_html/api/products.php?action=list"
   curl -X POST "http://localhost/Saksh%20Ecom/public_html/api/auth.php?action=login" \
     -d "email=test@test.com&password=password"
   ```

---

## Cart Issues

### Problem: "Items not staying in cart" or "Cart empty after reload"

**Symptoms:**
- Add to cart works but items disappear
- Cart shows different items on page reload
- Cart count is wrong
- Items can't be removed

**Solutions:**

1. **Check localStorage Quota**
   ```javascript
   // In browser console:
   try {
       localStorage.setItem('cart_test', 'test');
       localStorage.removeItem('cart_test');
       console.log('localStorage OK');
   } catch(e) {
       console.log('localStorage full or disabled');
   }
   ```

2. **Clear Cart and Retry**
   ```javascript
   // In browser console:
   localStorage.removeItem('cart');
   location.reload();
   ```

3. **Check CartManager Initialization**
   ```javascript
   // In browser console:
   console.log(window.cartManager);  // Should show CartManager object
   cartManager.getItems();  // Should show cart items
   ```

4. **Verify JavaScript Errors**
   - Open DevTools → Console
   - Look for red error messages
   - Check if app.js loaded successfully
   - Verify no syntax errors

5. **Test Cart Operations**
   ```javascript
   // In browser console:
   const cart = new CartManager();
   cart.addItem(1, 1);  // Add product ID 1
   console.log(cart.getItems());  // Should show item
   console.log(cart.getTotal());  // Should show price
   ```

---

## Performance Issues

### Problem: "Website is slow" or "Pages take too long to load"

**Symptoms:**
- Slow page load times
- Products grid takes time to render
- Admin pages are unresponsive
- Database queries timeout

**Solutions:**

1. **Check Page Load Time**
   ```bash
   # From command line:
   time curl http://localhost/Saksh%20Ecom/public_html/
   
   # From DevTools (F12 → Network)
   # Check waterfall chart for slow requests
   ```

2. **Enable Query Logging**
   ```php
   // In config/Database.php
   private $enableLogging = true;
   
   // Log queries and execution time
   error_log("Query: " . $query . " [" . $time . "ms]");
   ```

3. **Check Database Indexes**
   ```sql
   -- Verify indexes exist:
   SHOW INDEX FROM products;
   SHOW INDEX FROM orders;
   SHOW INDEX FROM users;
   
   -- Create missing indexes:
   CREATE INDEX idx_user_id ON orders(user_id);
   CREATE INDEX idx_product_id ON order_items(product_id);
   ```

4. **Optimize Database**
   ```sql
   -- Analyze tables:
   ANALYZE TABLE products;
   ANALYZE TABLE orders;
   ANALYZE TABLE downloads;
   
   -- Optimize tables:
   OPTIMIZE TABLE products;
   OPTIMIZE TABLE orders;
   ```

5. **Enable Caching**
   ```php
   // In config/config.php
   define('ENABLE_CACHE', true);
   define('CACHE_TTL', 3600);
   ```

6. **Minify Assets** (Optional)
   ```bash
   # Use online minifiers:
   # CSS: https://cssminifier.com/
   # JS: https://jsminifier.com/
   ```

---

## Email Issues

### Problem: "Emails not being sent"

**Symptoms:**
- Order confirmation emails not received
- Password reset emails not sent
- No error messages shown
- Email function runs but nothing happens

**Solutions:**

1. **Check SMTP Configuration**
   ```php
   // In config/config.php
   define('SMTP_HOST', 'smtp.gmail.com');       // Check correct host
   define('SMTP_PORT', 587);                     // Check port
   define('SMTP_USER', 'your-email@gmail.com');
   define('SMTP_PASS', 'your-app-password');    // Use app password, not main password
   define('SMTP_FROM', 'noreply@your-domain');  // Correct from address
   ```

2. **Test SMTP Connection**
   ```bash
   # Test connection with telnet:
   telnet smtp.gmail.com 587
   
   # Should connect, then close with Ctrl+]
   ```

3. **Check Email Sending Code**
   ```php
   // In api/auth.php or helpers.php
   // Email sending stub is ready for SMTP setup
   // Uncomment and configure PHPMailer usage
   ```

4. **Enable PHPMailer**
   ```php
   // First install via Composer (optional):
   composer require phpmailer/phpmailer
   
   // Or download PHPMailer and include
   require 'vendor/phpmailer/phpmailer/PHPMailer.php';
   ```

5. **Check Email Logs**
   ```bash
   # Check error logs:
   tail -f /var/log/mail.log
   
   # Check if sendmail is enabled:
   php -i | grep sendmail
   ```

6. **Gmail App Password**
   ```
   For Gmail SMTP:
   1. Enable 2-factor authentication
   2. Generate app-specific password
   3. Use app password instead of account password
   4. Use 'smtp.gmail.com' as SMTP host
   ```

---

## 🔍 Debugging Techniques

### Enable Detailed Error Reporting

```php
// Add to config/config.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/../storage/logs/error.log');

// Also enable for database
ini_set('mysqli.allow_local_infile', 1);
```

### Log Debugging Information

```php
// At start of page/API
error_log("Request: " . $_SERVER['REQUEST_METHOD'] . " " . $_SERVER['REQUEST_URI']);
error_log("User: " . ($_SESSION['user_id'] ?? 'anonymous'));
error_log("Parameters: " . json_encode($_GET + $_POST));

// After database query
error_log("Query result: " . json_encode($result));

// On error
error_log("Error occurred: " . $error_message);
```

### Browser Console Debugging

```javascript
// Check if script loaded
console.log('app.js loaded');
console.log(typeof themeManager);  // Check if class exists
console.log(typeof cartManager);

// Check API responses
fetch('/api/products.php?action=list')
    .then(r => r.json())
    .then(d => {
        console.log('API Response:', d);
        if (!d.success) console.error('API Error:', d.message);
    })
    .catch(e => console.error('Fetch Error:', e));

// Check localStorage
console.log('Stored cart:', localStorage.getItem('cart'));
console.log('Stored theme:', localStorage.getItem('theme'));
```

### Check File Permissions

```bash
# View permissions
ls -la public/uploads/
ls -la config/config.php
ls -la public/css/styles.css

# Fix permissions
chmod 755 public_html/
chmod 644 config/config.php
chmod 644 public/css/styles.css
chmod 777 public/uploads/
chmod 777 storage/logs/
```

---

## 📋 Maintenance Checklist

### Daily
- [ ] Check error logs
- [ ] Monitor payment processing
- [ ] Review new user registrations
- [ ] Check customer support tickets

### Weekly
- [ ] Database backup
- [ ] Review admin activity logs
- [ ] Check page performance
- [ ] Test critical user flows
- [ ] Monitor disk space

### Monthly
- [ ] Security audit
- [ ] Update dependencies
- [ ] Database optimization
- [ ] Performance review
- [ ] User feedback review

### Quarterly
- [ ] Full security penetration test
- [ ] Code review
- [ ] Database schema optimization
- [ ] Capacity planning
- [ ] Feature roadmap review

---

## 🎓 Development Tips

### Best Practices

1. **Always sanitize user input**
   ```php
   $input = sanitize($_POST['input'] ?? '');
   ```

2. **Use prepared statements**
   ```php
   $result = $db->query("SELECT * FROM users WHERE email = ?", [$email]);
   ```

3. **Check authentication on protected pages**
   ```php
   if (!isset($_SESSION['user_id'])) {
       header('Location: login.php');
       exit;
   }
   ```

4. **Escape output**
   ```php
   echo htmlspecialchars($user_data);
   ```

5. **Log important actions**
   ```php
   error_log("User " . $_SESSION['user_id'] . " purchased product " . $product_id);
   ```

### Adding New Features

1. **Create database table** (if needed)
   ```sql
   ALTER TABLE table_name ADD COLUMN new_field datatype;
   ```

2. **Create model method**
   ```php
   public function newFeature($param) {
       // Implementation
   }
   ```

3. **Create API endpoint**
   ```php
   // api/newfeature.php
   if ($action == 'new_action') {
       // Handle request
   }
   ```

4. **Create frontend UI**
   ```html
   <!-- Display data -->
   <form method="POST">
       <!-- Form fields -->
   </form>
   ```

5. **Add styling** if needed
   ```css
   .new-feature {
       /* Styles */
   }
   ```

6. **Test thoroughly**
   - Test on desktop
   - Test on mobile
   - Test with different users
   - Test error scenarios
   - Test with invalid data

---

## 📞 Getting Help

### Resources
- **Documentation**: README.md, SETUP_GUIDE.md
- **API Docs**: API_DOCUMENTATION.md
- **Troubleshooting**: This file
- **Error Logs**: storage/logs/error.log
- **Browser Console**: F12 → Console

### When Asking for Help
Include:
1. Error message (full text)
2. Steps to reproduce
3. Expected behavior
4. Actual behavior
5. Browser/OS information
6. Relevant code snippet
7. Error logs output

---

**Version**: 1.0.0
**Last Updated**: January 2025
**Status**: Complete and Tested ✅
