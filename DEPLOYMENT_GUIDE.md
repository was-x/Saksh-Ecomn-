# Saksh Ecom - Deployment Guide

## Pre-Deployment Checklist

### 1. Security Audit

- [ ] Change default admin password
- [ ] Review and update `config/config.php`:
  - [ ] Database credentials
  - [ ] Payment gateway API keys
  - [ ] Email SMTP settings
  - [ ] Site URL (use HTTPS)
  - [ ] Session timeout
  - [ ] File upload limits
  - [ ] Max login attempts

- [ ] Verify file permissions:
  ```bash
  chmod 755 public_html
  chmod 755 public/uploads
  chmod 755 storage/logs
  chmod 600 config/config.php
  ```

- [ ] Enable HTTPS/SSL certificate
- [ ] Set up firewall rules
- [ ] Configure backup strategy
- [ ] Review error logging configuration

### 2. Database Optimization

- [ ] Import `database/schema.sql`
- [ ] Verify all tables created successfully
- [ ] Create database backups
- [ ] Set up automated backup schedule
- [ ] Test database recovery procedure

### 3. Performance Setup

- [ ] Enable caching:
  ```bash
  # In config/config.php
  define('ENABLE_CACHE', true);
  define('CACHE_DRIVER', 'file'); // or redis
  ```

- [ ] Enable gzip compression in `.htaccess`
- [ ] Set up CDN for static assets
- [ ] Enable browser caching
- [ ] Minimize CSS/JS files
- [ ] Optimize image sizes

### 4. Email Configuration

- [ ] Configure SMTP settings in `config/config.php`
- [ ] Set up email templates
- [ ] Test email sending:
  ```php
  $mailer = new PHPMailer();
  // Send test email
  ```
- [ ] Configure email for:
  - [ ] Order confirmations
  - [ ] Payment notifications
  - [ ] Password resets
  - [ ] Support tickets
  - [ ] User registrations

### 5. Payment Gateway Setup

- [ ] **Razorpay**:
  - [ ] Sign up for account
  - [ ] Get API Key ID and API Key Secret
  - [ ] Update `config/config.php`
  - [ ] Test with sandbox credentials
  - [ ] Switch to live keys when ready

- [ ] **Stripe**:
  - [ ] Sign up for account
  - [ ] Get Publishable Key and Secret Key
  - [ ] Update `config/config.php`
  - [ ] Test checkout flow
  - [ ] Configure webhooks

- [ ] **PayPal**:
  - [ ] Sign up for account
  - [ ] Get Client ID and Secret
  - [ ] Update `config/config.php`
  - [ ] Test checkout flow

### 6. Testing

- [ ] **Functionality Testing**:
  ```
  [ ] User registration
  [ ] User login/logout
  [ ] Product browsing
  [ ] Search functionality
  [ ] Category filtering
  [ ] Add to cart
  [ ] Cart management
  [ ] Checkout process
  [ ] Payment processing
  [ ] Order confirmation
  [ ] Download management
  [ ] User profile
  [ ] Admin dashboard
  [ ] Product management
  [ ] Order management
  [ ] Coupon system
  [ ] Dark/Light mode
  ```

- [ ] **Responsive Testing**:
  ```
  [ ] Mobile (320px - 767px)
  [ ] Tablet (768px - 1024px)
  [ ] Desktop (1025px+)
  [ ] Touch interactions
  [ ] Orientation changes
  [ ] Bottom navigation (mobile)
  [ ] Top navigation (desktop)
  ```

- [ ] **Browser Testing**:
  ```
  [ ] Chrome/Chromium
  [ ] Firefox
  [ ] Safari
  [ ] Edge
  [ ] Mobile browsers
  ```

- [ ] **Payment Testing**:
  ```
  [ ] Test successful payment
  [ ] Test failed payment
  [ ] Test payment cancellation
  [ ] Verify order creation
  [ ] Verify download links
  [ ] Test coupon application
  ```

### 7. Admin Setup

- [ ] Create admin account via database
- [ ] Configure admin dashboard
- [ ] Add test products
- [ ] Test admin features:
  ```
  [ ] Add product
  [ ] Edit product
  [ ] Delete product
  [ ] View orders
  [ ] Update order status
  [ ] Create coupon
  [ ] View reports
  [ ] Manage users
  [ ] Configure settings
  ```

---

## Deployment Steps

### Step 1: Prepare Server

```bash
# SSH into server
ssh user@your-domain.com

# Create project directory
mkdir -p /var/www/saksh-ecom
cd /var/www/saksh-ecom

# Set permissions
sudo chown -R www-data:www-data /var/www/saksh-ecom
sudo chmod -R 755 /var/www/saksh-ecom
sudo chmod -R 777 public/uploads
sudo chmod -R 777 storage/logs
```

### Step 2: Upload Files

```bash
# Using Git (recommended)
git clone https://your-repo.git .
git checkout production

# Or using FTP/SFTP
# Upload all files from Saksh Ecom folder
```

### Step 3: Install Dependencies

```bash
# If using Composer for additional packages
composer install --no-dev

# Install npm packages if needed
npm install --production
```

### Step 4: Configure Environment

```bash
# Copy config template
cp config/config.php.example config/config.php

# Edit configuration with production values
nano config/config.php
```

**Update these values:**
```php
define('DB_HOST', 'your-db-host');
define('DB_NAME', 'saksh_ecom_prod');
define('DB_USER', 'your-db-user');
define('DB_PASS', 'your-strong-password');

define('SITE_URL', 'https://your-domain.com');
define('ADMIN_EMAIL', 'admin@your-domain.com');

// Payment gateways
define('RAZORPAY_KEY_ID', 'your-razorpay-key');
define('RAZORPAY_KEY_SECRET', 'your-razorpay-secret');

// Email
define('SMTP_HOST', 'your-smtp-host');
define('SMTP_USER', 'your-smtp-user');
define('SMTP_PASS', 'your-smtp-password');
```

### Step 5: Setup Database

```bash
# Connect to MySQL
mysql -u your-db-user -p

# Create database
CREATE DATABASE saksh_ecom_prod;
USE saksh_ecom_prod;

# Import schema
SOURCE database/schema.sql;

# Create admin user
INSERT INTO users (name, email, password, theme, status) 
VALUES ('Admin', 'admin@your-domain.com', '$2y$10$...', 'light', 'active');

INSERT INTO admin_users (user_id, role) 
VALUES (1, 'super_admin');
```

### Step 6: Configure Web Server

**For Apache:**
```apache
<VirtualHost *:443>
    ServerName your-domain.com
    ServerAlias www.your-domain.com
    
    DocumentRoot /var/www/saksh-ecom/public_html
    
    <Directory /var/www/saksh-ecom/public_html>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    # SSL Configuration
    SSLEngine on
    SSLCertificateFile /path/to/ssl/certificate.crt
    SSLCertificateKeyFile /path/to/ssl/private.key
    
    # Compression
    <IfModule mod_deflate.c>
        AddOutputFilterByType DEFLATE text/html text/plain text/css text/javascript application/javascript
    </IfModule>
    
    # Cache
    <IfModule mod_expires.c>
        ExpiresActive On
        ExpiresByType image/jpeg "access plus 1 year"
        ExpiresByType text/css "access plus 1 month"
        ExpiresByType application/javascript "access plus 1 month"
    </IfModule>
</VirtualHost>

# Redirect HTTP to HTTPS
<VirtualHost *:80>
    ServerName your-domain.com
    ServerAlias www.your-domain.com
    Redirect permanent / https://your-domain.com/
</VirtualHost>
```

**For Nginx:**
```nginx
server {
    listen 443 ssl http2;
    server_name your-domain.com www.your-domain.com;
    root /var/www/saksh-ecom/public_html;
    
    ssl_certificate /path/to/ssl/certificate.crt;
    ssl_certificate_key /path/to/ssl/private.key;
    
    index index.php;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    # Gzip compression
    gzip on;
    gzip_types text/plain text/css text/javascript application/javascript;
    
    # Cache static files
    location ~* \.(jpg|jpeg|png|gif|ico|css|js)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}

# Redirect HTTP to HTTPS
server {
    listen 80;
    server_name your-domain.com www.your-domain.com;
    return 301 https://$server_name$request_uri;
}
```

### Step 7: Set Up SSL/HTTPS

```bash
# Using Let's Encrypt with Certbot
sudo apt-get install certbot python3-certbot-apache

sudo certbot certonly --apache -d your-domain.com -d www.your-domain.com

# Auto-renewal setup
sudo systemctl enable certbot.timer
```

### Step 8: Setup Cron Jobs

```bash
# Edit crontab
crontab -e

# Add backup job (daily at 2 AM)
0 2 * * * /var/www/saksh-ecom/scripts/backup.sh

# Add cache cleanup job (daily at 3 AM)
0 3 * * * /var/www/saksh-ecom/scripts/cleanup.sh

# Add expired downloads cleanup (weekly)
0 4 * * 0 /var/www/saksh-ecom/scripts/cleanup-downloads.sh
```

### Step 9: Verify Deployment

```bash
# Check file permissions
ls -la /var/www/saksh-ecom/

# Check database connection
php -r "include 'config/Database.php'; $db = new Database(); echo 'DB OK';"

# Check web server
curl https://your-domain.com/

# Check logs
tail -f /var/log/apache2/error.log
# or
tail -f /var/log/nginx/error.log
```

### Step 10: Go Live

- [ ] Test all features one more time
- [ ] Verify SSL certificate is valid
- [ ] Check page load speed
- [ ] Verify email sending
- [ ] Test payment processing
- [ ] Monitor error logs
- [ ] Set up monitoring alerts

---

## Post-Deployment

### Monitoring

```bash
# Monitor server resources
htop

# Monitor disk usage
df -h

# Check log files
tail -f /var/log/apache2/access.log
tail -f storage/logs/app.log

# Monitor database
mysqladmin -u root -p status
```

### Maintenance Tasks

- [ ] Daily: Check error logs
- [ ] Weekly: Review admin dashboard
- [ ] Weekly: Backup database
- [ ] Monthly: Update dependencies
- [ ] Monthly: Security audit
- [ ] Quarterly: Performance review
- [ ] Quarterly: Penetration testing

### Scaling Considerations

- Enable database read replicas
- Use caching layer (Redis)
- CDN for static assets
- Separate media server
- Load balancing
- Database optimization
- Query caching

---

## Disaster Recovery

### Backup Strategy

```bash
# Daily database backup
mysqldump -u user -p saksh_ecom_prod > backup-$(date +%Y%m%d).sql

# Weekly full backup
tar -czf backup-$(date +%Y%m%d).tar.gz /var/www/saksh-ecom/

# Cloud backup (AWS S3)
aws s3 sync /var/www/saksh-ecom/ s3://your-bucket/saksh-ecom/
```

### Recovery Procedure

1. Stop web server
2. Restore from backup
3. Verify file integrity
4. Test functionality
5. Restore from most recent backup
6. Clear cache
7. Restart services

---

## Troubleshooting

### Issue: Database connection fails
```bash
# Check MySQL is running
sudo service mysql status

# Check credentials
mysql -u root -p saksh_ecom_prod

# Check permissions
mysql -u saksh_user -p -h localhost saksh_ecom_prod
```

### Issue: File upload not working
```bash
# Check permissions
sudo chown -R www-data:www-data public/uploads
sudo chmod -R 755 public/uploads

# Check upload limit in php.ini
upload_max_filesize = 256M
post_max_size = 256M
```

### Issue: Slow page load
```bash
# Enable caching in config.php
define('ENABLE_CACHE', true);

# Check database queries
# Add query logging in Database.php

# Use CDN for static assets
# Enable gzip compression
```

---

## Performance Optimization

### Caching Strategy

```php
// In config/config.php
define('CACHE_ENABLED', true);
define('CACHE_TTL', 3600); // 1 hour

// In PHP code
$cache_key = 'products_page_1';
if ($cached = cache_get($cache_key)) {
    return $cached;
}

$data = get_products();
cache_set($cache_key, $data, CACHE_TTL);
return $data;
```

### Database Optimization

```sql
-- Add indexes
CREATE INDEX idx_product_category ON products(category_id);
CREATE INDEX idx_order_user ON orders(user_id);
CREATE INDEX idx_download_order ON downloads(order_id);

-- Analyze tables
ANALYZE TABLE products;
ANALYZE TABLE orders;
ANALYZE TABLE downloads;

-- Optimize tables
OPTIMIZE TABLE products;
OPTIMIZE TABLE orders;
OPTIMIZE TABLE downloads;
```

---

## Support & Maintenance

For ongoing support:
- Regular security updates
- Performance monitoring
- User support
- Feature requests
- Bug fixes
- Database optimization
- Backup management

---

**Version**: 1.0.0
**Last Updated**: January 2025
**Status**: Production Ready ✅
