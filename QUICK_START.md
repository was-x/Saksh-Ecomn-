# Saksh Ecom - Quick Start Guide

## 🚀 5-Minute Setup

### Step 1: Start XAMPP
```
Open XAMPP Control Panel
Click "Start" for Apache and MySQL
```

### Step 2: Import Database
```
1. Open http://localhost/phpmyadmin
2. Click "Import" tab
3. Select: database/schema.sql
4. Click "Go"
```

### Step 3: Access Application
```
Frontend: http://localhost/Saksh%20Ecom/public_html/
Admin: http://localhost/Saksh%20Ecom/public_html/admin/dashboard.php
```

### Step 4: Create Admin Account
```sql
-- Run in phpMyAdmin SQL tab
INSERT INTO users (name, email, password, theme, status) 
VALUES ('Admin User', 'admin@test.com', '$2y$10$SlkxJ0J7ZbYiSPqRBZXfJOfCONVX9wuWqYX1KpxHF2lJxKyqh4xym', 'light', 'active');

INSERT INTO admin_users (user_id, role) 
VALUES (1, 'super_admin');
```

**Default Credentials:**
- Email: `admin@test.com`
- Password: `password123` (Bcrypt hashed)

---

## 📱 Test User Account

Create a regular user via signup page:
- Email: `user@test.com`
- Password: `password123`
- Name: `Test User`

---

## 🎯 What to Try First

### 1. Browse Products (No Login Required)
```
http://localhost/Saksh%20Ecom/public_html/
```
- View featured products
- Toggle dark/light mode
- See responsive design on mobile

### 2. Create an Account
```
Click "Sign Up" button
Fill in: Name, Email, Password
```

### 3. Search & Filter Products
```
Products → Search or use filters
Filter by: Category, Price, Popularity
```

### 4. Add to Cart
```
View any product detail
Click "Add to Cart"
Check cart badge in navbar
```

### 5. Admin Panel
```
Login as: admin@test.com / password123
Go to: /admin/dashboard.php
```

---

## 📁 Important Files

### Configuration
- **config/config.php** - Database, payment gateway settings
- **config/Database.php** - Database connection class

### Models
- **app/models/Auth.php** - User authentication
- **app/models/Product.php** - Product management
- **app/models/Cart.php** - Shopping cart
- **app/models/Order.php** - Orders & payments

### Frontend Pages
- **public_html/index.php** - Homepage
- **public_html/products.php** - Product listing
- **public_html/product.php** - Product detail
- **public_html/cart.php** - Shopping cart
- **public_html/checkout.php** - Payment
- **public_html/orders.php** - Order history
- **public_html/profile.php** - User profile

### Admin Pages
- **public_html/admin/dashboard.php** - Dashboard
- **public_html/admin/products/** - Product management
- **public_html/admin/orders/** - Order management
- **public_html/admin/coupons/** - Coupon management
- **public_html/admin/settings/** - System settings

### Styles & Scripts
- **public/css/styles.css** - All responsive styles
- **public/js/app.js** - Core functionality
- **public/js/products.js** - Product & checkout logic

### Database
- **database/schema.sql** - 15 MySQL tables

---

## 🔧 Configuration Checklist

### Before Going Live

- [ ] Update `config/config.php`:
  - [ ] Database credentials
  - [ ] Site URL
  - [ ] Payment gateway API keys
  - [ ] Email settings

- [ ] Create admin account (see Step 4 above)

- [ ] Test payment gateway:
  - [ ] Use sandbox credentials first
  - [ ] Test checkout process
  - [ ] Verify payment verification

- [ ] Test email notifications:
  - [ ] Configure SMTP
  - [ ] Send test order confirmation

- [ ] Security:
  - [ ] Set strong admin password
  - [ ] Enable HTTPS in production
  - [ ] Set file permissions
  - [ ] Regular database backups

---

## 🎨 Customization

### Change Colors
Edit `public/css/styles.css`:
```css
:root {
    --primary-color: #6366f1;        /* Main brand color */
    --secondary-color: #ec4899;      /* Secondary color */
    --success-color: #10b981;        /* Success state */
    /* ... more colors ... */
}
```

### Change Site Name
Edit `config/config.php`:
```php
define('SITE_NAME', 'My Store Name');
```

### Change Logo
In `public_html/admin/settings/index.php`:
- Upload site logo image
- Saves to `public/uploads/logo.png`

### Dark Mode Colors
The dark mode colors are automatically inverted:
```css
[data-theme="dark"] {
    --light-bg: #1f2937;           /* Dark background */
    --light-text-primary: #f3f4f6;  /* Light text */
    /* ... */
}
```

---

## 🧪 Testing Scenarios

### Scenario 1: User Registration → Purchase
1. Sign up as new user
2. Browse products
3. Add product to cart
4. Checkout (test payment)
5. View order in profile

### Scenario 2: Admin Management
1. Login as admin
2. Add new product with digital file
3. View dashboard stats
4. Create coupon code
5. View orders

### Scenario 3: Mobile Experience
1. Open on mobile device/emulator
2. Test bottom navigation tabs
3. Test product filtering
4. Test checkout on mobile

### Scenario 4: Dark Mode
1. Toggle theme button in navbar
2. Check all pages display correctly
3. Verify persistence after refresh

---

## 📊 Database Structure

### User Tables
- **users** - Customer accounts
- **admin_users** - Admin roles (super_admin, editor)

### Product Tables
- **categories** - Product categories
- **products** - Digital products
- **product_screenshots** - Product gallery images

### Order Tables
- **orders** - Customer orders
- **order_items** - Items in each order
- **downloads** - Download tracking with limits

### Discount Tables
- **coupons** - Discount codes

### Support Tables
- **support_tickets** - Customer support tickets
- **support_replies** - Ticket replies
- **faq** - FAQ entries

### System Tables
- **settings** - Configuration settings
- **activity_logs** - Admin action logs
- **password_resets** - Password reset tokens

---

## 🔒 Security Features

✅ **Implemented:**
- Bcrypt password hashing
- Prepared SQL statements (SQL injection prevention)
- HTML escaping (XSS prevention)
- Session security
- File upload validation
- Role-based access control

⚠️ **Best Practices:**
- Use HTTPS in production
- Keep passwords secure
- Regular security updates
- Monitor logs for suspicious activity
- Regular database backups
- Use strong admin passwords

---

## 🆘 Troubleshooting

### Issue: "Database connection failed"
**Solution:**
1. Check XAMPP MySQL is running
2. Verify config/config.php credentials:
   ```php
   DB_HOST: localhost
   DB_NAME: saksh_ecom
   DB_USER: root
   DB_PASS: (usually empty)
   ```
3. Ensure database was imported

### Issue: "File upload not working"
**Solution:**
1. Check `public/uploads/` folder permissions
2. Run: `chmod -R 777 public/uploads/`
3. Check PHP `upload_max_filesize` in php.ini

### Issue: "Cart not persisting"
**Solution:**
1. Check browser localStorage is enabled
2. Verify cookies are not blocked
3. Test in different browser

### Issue: "Admin login fails"
**Solution:**
1. Verify admin account exists in database
2. Check password hash is correct
3. Clear browser cache and cookies
4. Try creating new admin user via phpMyAdmin

### Issue: "Payment gateway error"
**Solution:**
1. Check API keys in admin settings
2. Verify API keys are correct
3. Check sandbox/live mode setting
4. Check network firewall allows requests

---

## 📚 Resource Files

- **README.md** - Full documentation
- **SETUP_GUIDE.md** - Detailed setup instructions
- **IMPLEMENTATION_SUMMARY.md** - Feature completeness
- **app/helpers.php** - Utility functions

---

## 🚀 Deployment Checklist

Before deploying to production:

- [ ] Test all features locally
- [ ] Update environment variables
- [ ] Use production API keys
- [ ] Enable HTTPS/SSL
- [ ] Set up database backups
- [ ] Configure email service
- [ ] Test payment processing
- [ ] Set up domain name
- [ ] Configure caching
- [ ] Monitor error logs
- [ ] Regular security audits

---

## 📞 Support

For issues or questions:
- Check FAQ.php
- Use Contact form
- Review error logs in storage/logs/

---

**Version**: 1.0.0
**Last Updated**: December 2025
**Status**: Production Ready ✅
