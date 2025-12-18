# Saksh Ecom - API Documentation

## Overview

The Saksh Ecom API provides endpoints for user authentication, product management, shopping cart, and checkout operations. All APIs are RESTful and return JSON responses.

---

## Authentication

### User Registration

**Endpoint**: `POST /api/auth.php?action=signup`

**Request**:
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "SecurePass123",
    "confirm_password": "SecurePass123"
}
```

**Response (Success)**:
```json
{
    "success": true,
    "message": "Account created successfully. Please login.",
    "user_id": 1
}
```

**Response (Error)**:
```json
{
    "success": false,
    "errors": {
        "email": "Email already exists"
    }
}
```

**Validation**:
- `name`: Required, 3-255 characters
- `email`: Required, valid email format
- `password`: Required, minimum 8 characters
- `confirm_password`: Must match password

---

### User Login

**Endpoint**: `POST /api/auth.php?action=login`

**Request**:
```json
{
    "email": "john@example.com",
    "password": "SecurePass123",
    "remember_me": true
}
```

**Response (Success)**:
```json
{
    "success": true,
    "message": "Login successful",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "theme": "light"
    }
}
```

**Response (Error)**:
```json
{
    "success": false,
    "message": "Invalid email or password"
}
```

---

### Forgot Password

**Endpoint**: `POST /api/auth.php?action=forgot-password`

**Request**:
```json
{
    "email": "john@example.com"
}
```

**Response**:
```json
{
    "success": true,
    "message": "Password reset link sent to your email"
}
```

---

### Reset Password

**Endpoint**: `POST /api/auth.php?action=reset-password`

**Request**:
```json
{
    "token": "reset_token_from_email",
    "password": "NewSecurePass123",
    "confirm_password": "NewSecurePass123"
}
```

**Response (Success)**:
```json
{
    "success": true,
    "message": "Password reset successfully. Please login with your new password."
}
```

---

### Logout

**Endpoint**: `GET /api/logout.php`

**Response**:
```json
{
    "success": true,
    "message": "Logout successful"
}
```

---

## Products

### Get All Products

**Endpoint**: `GET /api/products.php?action=list`

**Query Parameters**:
- `page` (optional): Page number, default: 1
- `limit` (optional): Items per page, default: 12
- `sort` (optional): `popularity`, `newest`, `price_low`, `price_high`
- `category_id` (optional): Filter by category

**Response**:
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Product Name",
            "slug": "product-name",
            "description": "Short description",
            "price": 99.99,
            "category_id": 1,
            "category_name": "Category Name",
            "image": "/uploads/product-image.jpg",
            "created_at": "2025-01-01 10:00:00"
        }
    ],
    "pagination": {
        "current_page": 1,
        "total_pages": 5,
        "total_items": 48
    }
}
```

---

### Get Single Product

**Endpoint**: `GET /api/products.php?action=detail&slug=product-slug`

**Response**:
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "Product Name",
        "description": "Full description",
        "full_description": "HTML description",
        "price": 99.99,
        "category_id": 1,
        "file_size": "50MB",
        "requirements": "Windows 10+",
        "image": "/uploads/product-image.jpg",
        "screenshots": [
            "/uploads/screenshot1.jpg",
            "/uploads/screenshot2.jpg"
        ],
        "related_products": [
            {
                "id": 2,
                "name": "Related Product",
                "price": 79.99
            }
        ]
    }
}
```

---

### Search Products

**Endpoint**: `GET /api/products.php?action=search&q=search_term`

**Query Parameters**:
- `q` (required): Search query
- `category_id` (optional): Filter by category
- `min_price` (optional): Minimum price
- `max_price` (optional): Maximum price

**Response**:
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Matching Product",
            "price": 99.99
        }
    ],
    "total": 5
}
```

---

### Get Categories

**Endpoint**: `GET /api/products.php?action=categories`

**Response**:
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Web Development",
            "slug": "web-development",
            "product_count": 12
        },
        {
            "id": 2,
            "name": "Mobile Apps",
            "slug": "mobile-apps",
            "product_count": 8
        }
    ]
}
```

---

## Shopping Cart

### Add to Cart

**Endpoint**: `POST /api/products.php?action=add-to-cart`

**Request**:
```json
{
    "product_id": 1,
    "quantity": 1
}
```

**Response (Success)**:
```json
{
    "success": true,
    "message": "Product added to cart",
    "cart_count": 5,
    "total_price": 500.00
}
```

**Note**: Cart is stored in browser localStorage. Use `CartManager` class in JavaScript to manage.

---

### Get Cart Items

**Endpoint**: `GET /api/products.php?action=get-cart`

**Response**:
```json
{
    "success": true,
    "data": [
        {
            "product_id": 1,
            "name": "Product Name",
            "price": 99.99,
            "quantity": 1,
            "subtotal": 99.99
        }
    ],
    "summary": {
        "subtotal": 99.99,
        "tax": 18.00,
        "total": 117.99,
        "item_count": 1
    }
}
```

---

## Checkout & Orders

### Create Order

**Endpoint**: `POST /api/products.php?action=checkout`

**Request**:
```json
{
    "cart_items": [
        {
            "product_id": 1,
            "quantity": 1
        }
    ],
    "billing_email": "john@example.com",
    "billing_name": "John Doe",
    "payment_method": "razorpay",
    "coupon_code": "SAVE10"
}
```

**Response (Success)**:
```json
{
    "success": true,
    "message": "Order created successfully",
    "order": {
        "id": "ORD-2025-001",
        "total": 89.99,
        "currency": "INR",
        "payment_url": "https://razorpay.com/checkout/...",
        "user_id": 1
    }
}
```

**Response (Error)**:
```json
{
    "success": false,
    "message": "Coupon code invalid or expired"
}
```

---

### Verify Payment

**Endpoint**: `POST /api/products.php?action=verify-payment`

**Request**:
```json
{
    "order_id": "ORD-2025-001",
    "payment_id": "pay_xxx",
    "signature": "signature_xxx"
}
```

**Response (Success)**:
```json
{
    "success": true,
    "message": "Payment verified successfully",
    "order": {
        "id": "ORD-2025-001",
        "status": "paid",
        "download_links": [
            {
                "product_id": 1,
                "product_name": "Product Name",
                "file_url": "/api/products.php?action=download&token=..."
            }
        ]
    }
}
```

---

### Get User Orders

**Endpoint**: `GET /api/products.php?action=get-orders`

**Authentication**: Required (User must be logged in)

**Query Parameters**:
- `page` (optional): Page number

**Response**:
```json
{
    "success": true,
    "data": [
        {
            "id": "ORD-2025-001",
            "created_at": "2025-01-01 10:00:00",
            "total": 99.99,
            "status": "paid",
            "items_count": 2
        }
    ],
    "pagination": {
        "current_page": 1,
        "total_pages": 1,
        "total_items": 3
    }
}
```

---

### Get Order Details

**Endpoint**: `GET /api/products.php?action=get-order-detail&order_id=ORD-2025-001`

**Response**:
```json
{
    "success": true,
    "data": {
        "id": "ORD-2025-001",
        "created_at": "2025-01-01 10:00:00",
        "total": 99.99,
        "status": "paid",
        "payment_status": "completed",
        "items": [
            {
                "product_id": 1,
                "product_name": "Product Name",
                "price": 99.99,
                "quantity": 1
            }
        ],
        "downloads": [
            {
                "id": 1,
                "product_name": "Product Name",
                "download_count": 0,
                "download_limit": 5,
                "expires_at": "2025-02-01",
                "download_url": "/api/products.php?action=download&id=1&token=..."
            }
        ]
    }
}
```

---

### Download Product

**Endpoint**: `GET /api/products.php?action=download&id=download_id&token=token`

**Response**:
- Initiates file download
- Increments download counter
- Returns 403 if download limit exceeded
- Returns 410 if download expired

---

## Error Handling

### Standard Error Response

```json
{
    "success": false,
    "message": "Error description",
    "code": "ERROR_CODE"
}
```

### Common Error Codes

- `INVALID_INPUT` - Missing or invalid parameters
- `AUTHENTICATION_REQUIRED` - User must be logged in
- `NOT_FOUND` - Resource not found
- `UNAUTHORIZED` - User doesn't have permission
- `VALIDATION_ERROR` - Input validation failed
- `DATABASE_ERROR` - Database operation failed

---

## Rate Limiting

- No explicit rate limiting currently implemented
- Recommended: 100 requests per minute per IP in production

---

## CORS Headers

The API supports the following CORS headers:

```
Access-Control-Allow-Origin: *
Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS
Access-Control-Allow-Headers: Content-Type, Authorization
```

---

## Caching

- Product lists cached for 1 hour
- Category lists cached for 1 day
- Cart stored in browser localStorage (not cached server-side)

---

## JavaScript Usage

### Using CartManager

```javascript
// Initialize cart manager
const cart = new CartManager();

// Add to cart
cart.addItem(productId, quantity);

// Get cart items
const items = cart.getItems();

// Update quantity
cart.updateItem(productId, newQuantity);

// Remove item
cart.removeItem(productId);

// Get total
const total = cart.getTotal();
```

### Using ApiHelper

```javascript
// Initialize API helper
const api = new ApiHelper('/api/');

// GET request
api.get('products.php?action=list', { page: 1 })
    .then(data => console.log(data))
    .catch(error => console.error(error));

// POST request
api.post('auth.php?action=login', { email: 'user@example.com', password: 'pass' })
    .then(data => console.log(data))
    .catch(error => console.error(error));
```

---

## Testing

### Using cURL

```bash
# Get products
curl -X GET "http://localhost/Saksh%20Ecom/public_html/api/products.php?action=list"

# Login
curl -X POST "http://localhost/Saksh%20Ecom/public_html/api/auth.php?action=login" \
  -H "Content-Type: application/json" \
  -d '{"email":"user@test.com","password":"password123"}'

# Add to cart
curl -X POST "http://localhost/Saksh%20Ecom/public_html/api/products.php?action=add-to-cart" \
  -H "Content-Type: application/json" \
  -d '{"product_id":1,"quantity":1}'
```

### Using Postman

1. Import API endpoints into Postman
2. Set base URL: `http://localhost/Saksh%20Ecom/public_html`
3. Test each endpoint with provided examples
4. Export test results

---

## Version History

| Version | Date | Changes |
|---------|------|---------|
| 1.0.0 | Jan 2025 | Initial API release |

---

## Support

For API support:
- Check endpoint documentation above
- Review error responses for troubleshooting
- Check app logs for detailed errors
- Use contact form for issues

---

**API Version**: 1.0.0
**Last Updated**: January 2025
**Status**: Production Ready ✅
