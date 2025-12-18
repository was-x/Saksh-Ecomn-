# 🚀 Quick Start - Animations Examples

## Copy-Paste Ready Code Snippets

### 1. HOMEPAGE HERO WITH ANIMATIONS

```html
<!-- Hero Section with Animations -->
<section style="margin-top: 70px; background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(236, 72, 153, 0.1) 100%); position: relative; overflow: hidden;">
    <!-- Floating background elements -->
    <div style="position: absolute; top: -50%; right: -10%; width: 500px; height: 500px; background: radial-gradient(circle, rgba(99, 102, 241, 0.2) 0%, transparent 70%); border-radius: 50%; animation: float 6s ease-in-out infinite;"></div>
    
    <!-- Content -->
    <div style="position: relative; z-index: 2; animation: fadeInUp 0.8s ease-out; padding: 3rem 1.5rem; text-align: center;">
        <h1 style="background: linear-gradient(135deg, #6366f1 0%, #ec4899 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-size: 3rem; margin-bottom: 1rem;">
            Welcome to Saksh Ecom
        </h1>
        <p style="font-size: 1.2rem; color: #6b7280; margin-bottom: 2rem;">
            Discover quality products at unbeatable prices
        </p>
        <div style="display: flex; gap: 1rem; justify-content: center;">
            <a href="products.php" class="btn btn-primary" style="animation: bounceIn 0.8s ease-out 0.2s backwards;">Shop Now</a>
            <a href="about.php" class="btn btn-secondary" style="animation: bounceIn 0.8s ease-out 0.3s backwards;">Learn More</a>
        </div>
    </div>
</section>
```

**Add to your page head:**
```html
<link rel="stylesheet" href="../public/css/animations.css">
```

---

### 2. ANIMATED PRODUCT CARD

```html
<div class="product-card" style="animation: fadeInUp 0.6s ease-out 0.1s backwards;">
    <div class="product-image" style="position: relative; overflow: hidden; border-radius: 12px 12px 0 0; height: 200px;">
        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem; animation: float 3s ease-in-out infinite;">
            📦
        </div>
    </div>
    
    <div class="product-info" style="padding: 1.5rem; animation: fadeInUp 0.6s ease-out 0.2s backwards;">
        <h3 style="color: var(--light-text-primary);">Premium Product</h3>
        <div class="price" style="font-size: 1.5rem; font-weight: 700; color: #6366f1; margin: 0.5rem 0;">₹2,999</div>
        <p style="color: var(--light-text-secondary);">High quality product with excellent features</p>
        <button class="btn btn-primary btn-block" style="transition: all 0.3s ease;">
            🛒 Add to Cart
        </button>
    </div>
</div>
```

---

### 3. ANIMATED FORM WITH VALIDATION

```html
<form class="form" style="max-width: 500px;">
    <div class="form-group" style="animation: fadeInUp 0.5s ease-out backwards; animation-delay: 0s;">
        <label for="name">Full Name</label>
        <input 
            type="text" 
            id="name" 
            name="name" 
            class="form-control" 
            required 
            style="transition: all 0.3s ease;"
        >
    </div>
    
    <div class="form-group" style="animation: fadeInUp 0.5s ease-out backwards; animation-delay: 0.1s;">
        <label for="email">Email Address</label>
        <input 
            type="email" 
            id="email" 
            name="email" 
            class="form-control" 
            required 
            style="transition: all 0.3s ease;"
        >
    </div>
    
    <div class="form-group" style="animation: fadeInUp 0.5s ease-out backwards; animation-delay: 0.2s;">
        <label for="message">Message</label>
        <textarea 
            id="message" 
            name="message" 
            class="form-control" 
            rows="5" 
            required 
            style="transition: all 0.3s ease;"
        ></textarea>
    </div>
    
    <button type="submit" class="btn btn-primary btn-block">Send Message</button>
</form>

<script src="../public/js/interactions.js"></script>
<script>
    // Auto-initialize form validation
    new FormValidator('.form');
</script>
```

---

### 4. ANIMATED BUTTON WITH RIPPLE

```html
<button 
    class="btn btn-primary" 
    style="position: relative; overflow: hidden;"
>
    Click Me
</button>

<style>
    .btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.5);
        transform: translate(-50%, -50%);
        transition: width 0.25s ease, height 0.25s ease;
    }

    .btn:hover::before {
        width: 300px;
        height: 300px;
    }
</style>
```

---

### 5. NOTIFICATION/TOAST MESSAGE

```html
<!-- Include animations CSS -->
<link rel="stylesheet" href="../public/css/animations.css">

<!-- Include interactions JS -->
<script src="../public/js/interactions.js"></script>

<!-- Use in JavaScript -->
<script>
    // Success message
    showToast('Order placed successfully!', 'success');
    
    // Error message
    showToast('Something went wrong!', 'error');
    
    // Warning message
    showToast('Please fill all fields', 'warning');
    
    // Info message
    showToast('Check your email for confirmation', 'info');
</script>
```

---

### 6. NAVBAR WITH ANIMATIONS

```html
<nav class="navbar" style="animation: slideInDown 0.5s ease-out;">
    <div class="navbar-container">
        <!-- Logo with gradient animation -->
        <div class="navbar-logo">
            <a href="index.php" style="
                font-size: 1.5rem;
                font-weight: 700;
                background: linear-gradient(135deg, #6366f1 0%, #ec4899 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                transition: all 0.3s ease;
            ">
                Saksh Ecom
            </a>
        </div>
        
        <!-- Nav links with underline animation -->
        <div class="navbar-menu">
            <a href="index.php" class="nav-link" style="
                position: relative;
                color: var(--light-text-secondary);
                transition: color 0.3s ease;
            ">
                Home
                <span style="
                    position: absolute;
                    bottom: -5px;
                    left: 0;
                    width: 0;
                    height: 2px;
                    background: linear-gradient(90deg, #6366f1 0%, #ec4899 100%);
                    transition: width 0.25s ease;
                "></span>
            </a>
            
            <a href="products.php" class="nav-link">Products</a>
            
            <a href="profile.php?logout=1" class="nav-link logout-link" 
                style="
                    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
                    color: white;
                    padding: 0.5rem 1rem;
                    border-radius: 6px;
                    transition: all 0.3s ease;
                " 
                onclick="return confirm('Logout?')"
            >
                Logout
            </a>
        </div>
    </div>
</nav>
```

---

### 7. CARD WITH HOVER ANIMATION

```html
<div class="card" style="
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    transition: all 0.3s ease;
    cursor: pointer;
">
    <div class="card-header" style="
        padding: 1.5rem;
        border-bottom: 1px solid var(--light-border);
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.05) 0%, rgba(236, 72, 153, 0.05) 100%);
        transition: all 0.3s ease;
    ">
        <h3 style="margin: 0;">Card Title</h3>
    </div>
    
    <div class="card-body" style="
        padding: 1.5rem;
        animation: fadeInUp 0.6s ease-out;
    ">
        <p>Card content goes here</p>
    </div>
</div>

<style>
    .card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
    }
    
    .card:hover .card-header {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(236, 72, 153, 0.1) 100%);
    }
</style>
```

---

### 8. DROPDOWN WITH ANIMATION

```html
<div class="dropdown">
    <button class="dropdown-toggle" style="
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        cursor: pointer;
    ">
        Menu ▼
    </button>
    
    <div class="dropdown-content" style="
        display: none;
        position: absolute;
        background-color: var(--light-bg);
        min-width: 200px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        padding: 0.5rem 0;
        z-index: 1;
        border-radius: 8px;
        overflow: hidden;
        animation: slideDown 0.3s ease-out;
    ">
        <a href="#" style="
            color: var(--light-text-primary);
            padding: 0.75rem 1.5rem;
            text-decoration: none;
            display: block;
            transition: all 0.2s ease;
        ">
            Option 1
        </a>
        
        <a href="#" style="
            color: var(--light-text-primary);
            padding: 0.75rem 1.5rem;
            text-decoration: none;
            display: block;
            transition: all 0.2s ease;
        ">
            Option 2
        </a>
    </div>
</div>

<script>
    document.querySelector('.dropdown-toggle').addEventListener('click', function() {
        const content = this.nextElementSibling;
        content.style.display = content.style.display === 'none' ? 'block' : 'none';
    });
</script>
```

---

### 9. ANIMATED MODAL

```html
<!-- Modal HTML -->
<div id="myModal" class="modal" style="
    display: none;
    position: fixed;
    z-index: 100;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    animation: fadeInDown 0.3s ease-out;
">
    <div class="modal-content" style="
        background-color: var(--light-bg);
        margin: 50px auto;
        padding: 2rem;
        border-radius: 12px;
        width: 90%;
        max-width: 600px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        animation: scaleInCenter 0.4s ease-out;
    ">
        <div class="modal-header" style="
            border-bottom: 1px solid var(--light-border);
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
        ">
            <h2 style="margin: 0;">Modal Title</h2>
            <span class="modal-close" style="
                color: var(--light-text-secondary);
                float: right;
                font-size: 1.5rem;
                font-weight: bold;
                cursor: pointer;
                transition: all 0.2s ease;
            " onclick="closeModal('myModal')">
                &times;
            </span>
        </div>
        
        <div class="modal-body">
            <p>Modal content goes here</p>
        </div>
        
        <div style="margin-top: 1.5rem; text-align: right;">
            <button onclick="closeModal('myModal')" class="btn btn-secondary">
                Close
            </button>
        </div>
    </div>
</div>

<!-- Open Modal Button -->
<button onclick="showModal('myModal')" class="btn btn-primary">
    Open Modal
</button>

<!-- Include JavaScript -->
<script src="../public/js/interactions.js"></script>
```

---

### 10. LOADING STATE ANIMATION

```html
<button id="submitBtn" class="btn btn-primary">
    Submit
</button>

<script>
    document.getElementById('submitBtn').addEventListener('click', function() {
        // Show loading
        showLoading(this);
        
        // Simulate API call
        setTimeout(() => {
            // Hide loading
            hideLoading(this);
            
            // Show success message
            showToast('Success!', 'success');
        }, 2000);
    });
</script>

<style>
    .loader {
        border: 4px solid var(--light-bg-secondary);
        border-top: 4px solid var(--primary-color);
        border-radius: 50%;
        width: 20px;
        height: 20px;
        animation: spinLoader 1s linear infinite;
        display: inline-block;
    }
</style>
```

---

## 📋 QUICK CHECKLIST

Before using animations:

- ✅ Include `animations.css` in `<head>`
- ✅ Include `interactions.js` before `</body>`
- ✅ Use animation classes on elements
- ✅ Test on mobile devices
- ✅ Check dark mode support
- ✅ Verify performance (60 FPS)

---

## 🎯 NEXT STEPS

1. **Copy these examples** to your pages
2. **Customize colors** to match your theme
3. **Adjust timings** if needed
4. **Test on all devices**
5. **Share your beautiful website!**

---

**Happy Animating! 🎉**
