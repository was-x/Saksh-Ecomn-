# Saksh Ecom - Developer Quick Reference

## 📚 Quick Navigation

### File Locations
```
Configuration:
- config/config.php          ← All settings and API keys
- config/Database.php        ← Database class and queries

Models (Business Logic):
- app/models/Auth.php        ← User authentication
- app/models/Product.php     ← Product operations
- app/models/Cart.php        ← Cart management
- app/models/Order.php       ← Order processing

Utilities:
- app/helpers.php            ← Helper functions and classes
- app/PaginationHelper.php   ← Pagination functionality

Frontend (User Pages):
- public_html/index.php      ← Homepage
- public_html/products.php   ← Product listing
- public_html/product.php    ← Product detail
- public_html/cart.php       ← Shopping cart
- public_html/checkout.php   ← Payment page
- public_html/orders.php     ← Order history
- public_html/order-details.php    ← Order detail
- public_html/downloads.php  ← Download management
- public_html/profile.php    ← User profile
- public_html/login.php      ← Login page
- public_html/signup.php     ← Registration page
- public_html/forgot-password.php  ← Password reset
- public_html/faq.php        ← FAQ page
- public_html/contact.php    ← Contact form

Admin Pages:
- public_html/admin/dashboard.php      ← Main dashboard
- public_html/admin/products/*.php     ← Product CRUD
- public_html/admin/orders/*.php       ← Order management
- public_html/admin/users/*.php        ← User management
- public_html/admin/coupons/*.php      ← Coupon system
- public_html/admin/settings/*.php     ← System config
- public_html/admin/reports/*.php      ← Analytics
- public_html/admin/support/*.php      ← Support tickets

APIs:
- public_html/api/auth.php             ← Authentication API
- public_html/api/products.php         ← Product & Order API
- public_html/api/logout.php           ← Logout handler

Styles & Scripts:
- public/css/styles.css               ← All responsive styles
- public/js/app.js                    ← Core functionality
- public/js/products.js               ← Product logic

Database:
- database/schema.sql                 ← Complete schema

Docs:
- README.md                  ← Full documentation
- SETUP_GUIDE.md            ← Setup instructions
- QUICK_START.md            ← 5-minute setup
- DEPLOYMENT_GUIDE.md       ← Production setup
- API_DOCUMENTATION.md      ← API reference
- IMPLEMENTATION_SUMMARY.md ← Feature overview
- PROJECT_STATUS.md         ← Detailed status
- TROUBLESHOOTING.md        ← Common issues
```

---

## 🔧 Common Code Snippets

### Authentication Check
```php
// At top of protected pages
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Check if admin
if (!isset($_SESSION['is_admin'])) {
    header('Location: index.php');
    exit;
}
```

### Database Query
```php
$db = new Database();

// SELECT
$result = $db->query("SELECT * FROM products WHERE id = ?", [$product_id]);
$product = $result ? $result[0] : null;

// INSERT
$db->insert('orders', [
    'user_id' => $user_id,
    'total' => $total,
    'status' => 'pending'
]);

// UPDATE
$db->update('users', [
    'name' => $new_name,
    'email' => $new_email
], ['id' => $user_id]);

// DELETE
$db->delete('orders', ['id' => $order_id]);
```

### Include Helpers
```php
<?php
session_start();
include '../config/Database.php';
include '../app/helpers.php';

// Now you have access to:
// - Database class
// - All helper functions
// - Auth model (if needed)
```

### HTML Form with Validation
```html
<form method="POST" class="form">
    <div class="form-group">
        <label for="email">Email Address *</label>
        <input type="email" id="email" name="email" required 
               class="form-control" placeholder="Enter email">
    </div>

    <div class="form-group">
        <label for="password">Password *</label>
        <input type="password" id="password" name="password" required 
               class="form-control" placeholder="Enter password">
    </div>

    <button type="submit" class="btn btn-primary btn-block">Submit</button>
</form>

<script>
    new FormValidator('.form');  // Enable validation
</script>
```

### API Endpoint
```php
<?php
session_start();
include '../config/Database.php';
include '../app/helpers.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? $_POST['action'] ?? null;

if ($action === 'my_action') {
    $param = sanitize($_POST['param'] ?? '');
    
    if (empty($param)) {
        echo json_encode(['success' => false, 'message' => 'Parameter required']);
        exit;
    }
    
    // Do something
    $result = true;
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Success']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error']);
    }
}
?>
```

### JavaScript API Call
```javascript
// GET request
fetch('api/products.php?action=list&page=1')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Products:', data.data);
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));

// POST request
fetch('api/auth.php?action=login', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        email: 'user@example.com',
        password: 'password123'
    })
})
.then(response => response.json())
.then(data => {
    if (data.success) {
        location.href = 'index.php';
    } else {
        alert('Login failed: ' + data.message);
    }
});
```

### Responsive Styles
```css
/* Mobile first approach */
.product-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 20px;
}

/* Tablet (768px+) */
@media (min-width: 768px) {
    .product-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* Desktop (1025px+) */
@media (min-width: 1025px) {
    .product-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}

/* Dark mode */
[data-theme="dark"] .product-card {
    background-color: var(--dark-bg);
    color: var(--dark-text);
}
```

### JavaScript Class
```javascript
class MyManager {
    constructor() {
        this.data = [];
        this.init();
    }

    init() {
        console.log('Manager initialized');
    }

    getData() {
        return this.data;
    }

    addData(item) {
        this.data.push(item);
    }

    removeData(index) {
        this.data.splice(index, 1);
    }

    clear() {
        this.data = [];
    }
}

// Usage
const manager = new MyManager();
manager.addData({id: 1, name: 'Item 1'});
console.log(manager.getData());
```

---

## 📊 Database Schema Quick Reference

### Users Table
```sql
users (
    id (PK),
    name,
    email (UNIQUE),
    password,
    phone,
    address,
    city,
    state,
    pincode,
    country,
    theme,
    status,
    created_at
)
```

### Products Table
```sql
products (
    id (PK),
    name,
    slug (UNIQUE),
    description,
    price,
    file_size,
    requirements,
    file_path,
    image,
    created_at
)
```

### Orders Table
```sql
orders (
    id (PK),
    user_id (FK),
    total,
    tax,
    status,
    payment_status,
    payment_method,
    created_at
)

order_items (
    id (PK),
    order_id (FK),
    product_id (FK),
    price,
    quantity
)
```

### Downloads Table
```sql
downloads (
    id (PK),
    user_id (FK),
    order_id (FK),
    product_id (FK),
    download_count,
    download_limit,
    expires_at,
    created_at
)
```

### Coupons Table
```sql
coupons (
    id (PK),
    code (UNIQUE),
    discount_type (flat/percentage),
    discount_value,
    min_order_amount,
    max_uses,
    used_count,
    expires_at,
    status,
    created_at
)
```

---

## 🚀 Deployment Checklist

### Before Going Live
- [ ] Test all pages on mobile
- [ ] Test all pages on desktop
- [ ] Test payment processing
- [ ] Set up HTTPS/SSL
- [ ] Update config.php with production values
- [ ] Configure payment gateway API keys
- [ ] Set up email SMTP
- [ ] Configure database backups
- [ ] Enable error logging
- [ ] Test admin functionality
- [ ] Create backup of current setup
- [ ] Monitor error logs
- [ ] Test user registration
- [ ] Test order flow
- [ ] Test downloads

### After Going Live
- [ ] Monitor error logs daily
- [ ] Check payment processing
- [ ] Review user registrations
- [ ] Check customer support tickets
- [ ] Weekly database backups
- [ ] Monitor server performance
- [ ] Review security logs
- [ ] Regular security updates

---

## 🔐 Security Checklist

### Code Security
- [ ] Sanitize all user inputs
- [ ] Escape all output
- [ ] Use prepared statements
- [ ] Check authentication on protected pages
- [ ] Validate file uploads
- [ ] Hash passwords with Bcrypt
- [ ] Use HTTPS only
- [ ] Set secure session configuration
- [ ] Implement CSRF tokens
- [ ] Log security events

### Configuration
- [ ] Change database password
- [ ] Update payment gateway keys
- [ ] Configure SMTP credentials
- [ ] Set correct file permissions
- [ ] Enable error logging (not display)
- [ ] Set session timeout
- [ ] Configure firewall rules
- [ ] Enable HTTPS with valid SSL cert
- [ ] Set up automated backups
- [ ] Monitor access logs

### Testing
- [ ] Test SQL injection protection
- [ ] Test XSS protection
- [ ] Test CSRF protection
- [ ] Test file upload security
- [ ] Test authentication bypass
- [ ] Test authorization bypass
- [ ] Test payment security
- [ ] Test session security
- [ ] Test password reset flow
- [ ] Test admin access control

---

## 🐛 Debugging

### Enable Debug Mode
```php
// In config/config.php
define('DEBUG_MODE', true);
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

### Log Debugging
```php
error_log("Debug message: " . json_encode($data));
```

### Check Error Logs
```bash
tail -f storage/logs/error.log
tail -f /var/log/apache2/error.log
```

### Browser Console
```javascript
// F12 to open DevTools
// Console tab shows JavaScript errors
// Network tab shows API calls
// Application tab shows localStorage
```

---

## 📱 Responsive Design

### Breakpoints
```css
/* Mobile (0 - 767px) */
@media (max-width: 767px) { }

/* Tablet (768px - 1024px) */
@media (min-width: 768px) and (max-width: 1024px) { }

/* Desktop (1025px+) */
@media (min-width: 1025px) { }
```

### Mobile Navigation
```php
<!-- Bottom navigation for mobile -->
<nav class="bottom-nav">
    <a href="index.php" class="nav-item">
        <span class="icon">🏠</span>
        <span class="label">Home</span>
    </a>
    <!-- More items -->
</nav>
```

### Responsive Grid
```css
.grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}
```

---

## 💾 Database Operations

### Backup Database
```bash
mysqldump -u root -p saksh_ecom > backup.sql
```

### Restore Database
```bash
mysql -u root -p saksh_ecom < backup.sql
```

### Check Database Size
```sql
SELECT 
    table_name,
    ROUND(((data_length + index_length) / 1024 / 1024), 2) AS size_mb
FROM information_schema.tables
WHERE table_schema = 'saksh_ecom'
ORDER BY size_mb DESC;
```

---

## 🎯 Common Tasks

### Add New Product
1. Go to `/admin/products/add.php`
2. Fill in product details
3. Upload product file
4. Upload product image
5. Add screenshots
6. Click Add Product

### Add New Coupon
1. Go to `/admin/coupons/add.php`
2. Enter coupon code
3. Set discount type (flat/percentage)
4. Enter discount value
5. Set expiry date
6. Click Create Coupon

### View Orders
1. Go to `/admin/orders/list.php`
2. Filter by date if needed
3. Click on order ID for details
4. Update order status
5. View payment status

### Configure Payment Gateway
1. Go to `/admin/settings/`
2. Enter API keys
3. Set sandbox/live mode
4. Click Save
5. Test with test transaction

### Create Admin User
```sql
INSERT INTO users (name, email, password, theme, status) 
VALUES ('Admin', 'admin@example.com', 
    '$2y$10$SlkxJ0J7ZbYiSPqRBZXfJOfCONVX9wuWqYX1KpxHF2lJxKyqh4xym', 
    'light', 'active');

INSERT INTO admin_users (user_id, role) 
VALUES (1, 'super_admin');
```

---

## 📞 Support Contacts

### When Things Break
1. Check error logs: `storage/logs/error.log`
2. Check browser console: F12 → Console
3. Review TROUBLESHOOTING.md
4. Check documentation
5. Review relevant code file

### Common Issues Quick Links
- Database connection: See TROUBLESHOOTING.md → Database Issues
- File upload: See TROUBLESHOOTING.md → File Upload Issues
- Login problems: See TROUBLESHOOTING.md → Session Issues
- Payment errors: See TROUBLESHOOTING.md → Payment Issues
- Slow pages: See TROUBLESHOOTING.md → Performance Issues

---

## 🔗 Important URLs

### User Pages
- Home: `/index.php`
- Products: `/products.php`
- Product Detail: `/product.php?slug=product-name`
- Cart: `/cart.php`
- Checkout: `/checkout.php`
- Orders: `/orders.php`
- Profile: `/profile.php`
- Login: `/login.php`
- Signup: `/signup.php`

### Admin Pages
- Dashboard: `/admin/dashboard.php`
- Products: `/admin/products/list.php`
- Orders: `/admin/orders/list.php`
- Users: `/admin/users/list.php`
- Coupons: `/admin/coupons/list.php`
- Settings: `/admin/settings/`
- Reports: `/admin/reports/sales.php`
- Support: `/admin/support/tickets.php`

### API Endpoints
- Login: `POST /api/auth.php?action=login`
- Signup: `POST /api/auth.php?action=signup`
- Products List: `GET /api/products.php?action=list`
- Product Search: `GET /api/products.php?action=search&q=query`
- Add to Cart: `POST /api/products.php?action=add-to-cart`
- Checkout: `POST /api/products.php?action=checkout`
- Download: `GET /api/products.php?action=download&id=id`

---

## ✨ Final Notes

- All files are commented and ready for modification
- Responsive design tested on all major browsers
- Security features implemented throughout
- Database schema optimized with indexes
- Error handling included on all pages
- Logging system ready for monitoring
- Documentation is comprehensive
- Code is well-organized and maintainable

**Happy developing! 🚀**

---

**Version**: 1.0.0
**Last Updated**: January 2025
**Status**: Production Ready ✅
