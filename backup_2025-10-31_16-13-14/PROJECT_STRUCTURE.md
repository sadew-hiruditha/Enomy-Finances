# 🌳 Project Folder Structure - Visual Guide

## Current Structure (As-Is)

```
📦 code/
├── 📄 index.php                        ← Login page (KEEP IN ROOT)
├── 📄 register.php                     ← Registration (KEEP IN ROOT)
├── 📄 logout.php                       ← Logout handler (KEEP IN ROOT)
│
├── 📁 assets/                          ← Static assets
│   ├── 📁 css/
│   │   └── 📄 style.css                ✅ Modern UI stylesheet
│   ├── 📁 js/                          ← JavaScript files (create as needed)
│   │   └── 📄 calculator.js            ← Investment calculator JS
│   └── 📁 images/                      ← Images (logos, icons)
│       └── 🖼️ logo.png
│
├── 📁 includes/                        ← Core PHP includes (NEW FOLDER)
│   ├── 📄 config.php                   ← Database configuration
│   └── 📄 functions.php                ← Business logic functions
│
├── 📁 database/                        ← Database scripts (NEW FOLDER)
│   ├── 📄 setup.sql                    ← Schema definition
│   ├── 📄 run_setup.php                ← Setup automation
│   ├── 📄 insert_admin_staff.sql       ← Sample admin data
│   └── 📄 generate_hash.php            ← Password hash generator
│
├── 📁 public/                          ← User-facing pages (NEW FOLDER)
│   ├── 📁 client/                      ← Client portal
│   │   ├── 📄 dashboard.php            ← Client dashboard
│   │   ├── 📄 add_mortgage.php         ← Mortgage application
│   │   ├── 📄 add_investment.php       ← Create investment
│   │   ├── 📄 currency_converter.php   ← Currency tool
│   │   └── 📄 investment_calculator.php ← Investment quotes
│   │
│   ├── 📁 staff/                       ← Staff portal
│   │   ├── 📄 dashboard.php            ← Staff dashboard
│   │   └── 📄 manage_investments.php   ← Investment management
│   │
│   └── 📁 admin/                       ← Admin portal
│       ├── 📄 dashboard.php            ← Admin dashboard
│       └── 📄 add_admin_staff.php      ← Create users
│
├── 📁 docs/                            ← Documentation (NEW FOLDER)
│   ├── 📄 SYSTEM_DOCUMENTATION.md      ← Complete system docs
│   ├── 📄 REQUIREMENTS_IMPLEMENTATION.md ← Features documentation
│   ├── 📄 INVESTMENTS_INTEGRATION.md   ← Investment guide
│   ├── 📄 MODERN_UI_README.md          ← UI documentation
│   ├── 📄 FOLDER_STRUCTURE.md          ← Folder guide
│   └── 📄 preview.html                 ← UI preview
│
├── 📄 reorganize.php                   ← Reorganization script
└── 📄 README.md                        ← Project overview

```

## Proposed Structure (Best Practice)

```
📦 enomy-finances/
│
├── 📄 index.php                        ← Entry point (redirects to login)
├── 📄 .htaccess                        ← Apache config
├── 📄 .gitignore                       ← Git ignore rules
├── 📄 README.md                        ← Project overview
├── 📄 composer.json                    ← Dependency management (optional)
│
├── 📁 app/                             ← Application core
│   ├── 📁 Controllers/                 ← Controller classes
│   │   ├── 📄 AuthController.php
│   │   ├── 📄 DashboardController.php
│   │   ├── 📄 MortgageController.php
│   │   └── 📄 InvestmentController.php
│   │
│   ├── 📁 Models/                      ← Database models
│   │   ├── 📄 User.php
│   │   ├── 📄 Client.php
│   │   ├── 📄 Mortgage.php
│   │   └── 📄 Investment.php
│   │
│   └── 📁 Views/                       ← Template files
│       ├── 📁 auth/
│       ├── 📁 client/
│       ├── 📁 staff/
│       └── 📁 admin/
│
├── 📁 config/                          ← Configuration
│   ├── 📄 database.php                 ← DB config
│   ├── 📄 app.php                      ← App settings
│   └── 📄 .env.example                 ← Environment template
│
├── 📁 public/                          ← Web root (point Apache here)
│   ├── 📄 index.php                    ← Front controller
│   │
│   ├── 📁 assets/
│   │   ├── 📁 css/
│   │   │   ├── 📄 style.css
│   │   │   ├── 📄 dashboard.css
│   │   │   └── 📄 forms.css
│   │   │
│   │   ├── 📁 js/
│   │   │   ├── 📄 main.js
│   │   │   ├── 📄 calculator.js
│   │   │   └── 📄 validation.js
│   │   │
│   │   └── 📁 images/
│   │       ├── 🖼️ logo.png
│   │       ├── 📁 icons/
│   │       └── 📁 backgrounds/
│   │
│   ├── 📁 auth/
│   │   ├── 📄 login.php
│   │   ├── 📄 register.php
│   │   └── 📄 logout.php
│   │
│   ├── 📁 client/
│   │   └── (client pages)
│   │
│   ├── 📁 staff/
│   │   └── (staff pages)
│   │
│   └── 📁 admin/
│       └── (admin pages)
│
├── 📁 database/
│   ├── 📄 schema.sql                   ← Database schema
│   ├── 📁 migrations/                  ← Database migrations
│   │   ├── 📄 001_create_users.sql
│   │   ├── 📄 002_create_mortgages.sql
│   │   └── 📄 003_create_investments.sql
│   └── 📁 seeds/                       ← Test data
│       └── 📄 admin_users.sql
│
├── 📁 storage/                         ← Application storage
│   ├── 📁 logs/                        ← Log files
│   │   └── 📄 app.log
│   ├── 📁 cache/                       ← Cache files
│   └── 📁 uploads/                     ← User uploads
│
├── 📁 vendor/                          ← Composer packages
│
├── 📁 tests/                           ← Testing
│   ├── 📁 unit/
│   └── 📁 integration/
│
└── 📁 docs/                            ← Documentation
    ├── 📄 SYSTEM_DOCUMENTATION.md
    ├── 📄 API_REFERENCE.md
    ├── 📄 DEPLOYMENT_GUIDE.md
    └── 📄 USER_MANUAL.md

```

## File Count by Category

### Current Project Files

```
📊 Statistics:
├── PHP Pages:        15 files
├── CSS Files:         1 file
├── SQL Files:         2 files
├── Documentation:     5 files
└── Total Files:      23 files
```

### By Role/Function

```
🔐 Authentication:     3 files  (index, register, logout)
👤 Client Features:    5 files  (dashboard, mortgage, investment x2, currency)
👔 Staff Features:     2 files  (dashboard, manage investments)
👨‍💼 Admin Features:    2 files  (dashboard, add users)
🗄️ Database:           3 files  (setup.sql, run_setup.php, hash generator)
🎨 Assets:             1 file   (style.css)
📚 Documentation:      5 files  (MD files)
```

## URL Structure

### Current (Flat Structure)
```
http://localhost/code/index.php
http://localhost/code/register.php
http://localhost/code/dashboard_client.php
http://localhost/code/currency_converter.php
http://localhost/code/dashboard_staff.php
http://localhost/code/dashboard_admin.php
```

### After Reorganization
```
http://localhost/code/index.php                         ← Login
http://localhost/code/register.php                      ← Register
http://localhost/code/public/client/dashboard.php       ← Client
http://localhost/code/public/client/currency_converter.php
http://localhost/code/public/staff/dashboard.php        ← Staff
http://localhost/code/public/admin/dashboard.php        ← Admin
```

### With Clean URLs (.htaccess)
```
http://localhost/code/login                             ← Login
http://localhost/code/register                          ← Register
http://localhost/code/client/dashboard                  ← Client
http://localhost/code/client/currency-converter
http://localhost/code/staff/dashboard                   ← Staff
http://localhost/code/admin/dashboard                   ← Admin
```

## Security Layers

```
📂 code/
│
├── 🔓 PUBLIC ACCESS (web accessible)
│   ├── index.php
│   ├── register.php
│   ├── assets/
│   └── public/
│
└── 🔒 PROTECTED (NOT web accessible)
    ├── includes/          ← Database config, functions
    ├── database/          ← Schema, setup scripts
    ├── storage/           ← Logs, uploads
    ├── vendor/            ← Third-party libraries
    └── config/            ← Environment config
```

## Quick Navigation Guide

| You Want To... | Navigate To |
|----------------|-------------|
| **Login to system** | `/index.php` |
| **Register new client** | `/register.php` |
| **Client dashboard** | `/public/client/dashboard.php` |
| **Convert currency** | `/public/client/currency_converter.php` |
| **Calculate investment** | `/public/client/investment_calculator.php` |
| **Apply for mortgage** | `/public/client/add_mortgage.php` |
| **Create investment** | `/public/client/add_investment.php` |
| **Staff dashboard** | `/public/staff/dashboard.php` |
| **Manage investments** | `/public/staff/manage_investments.php` |
| **Admin dashboard** | `/public/admin/dashboard.php` |
| **Create admin/staff** | `/public/admin/add_admin_staff.php` |
| **Setup database** | `/database/run_setup.php` |
| **View documentation** | `/docs/SYSTEM_DOCUMENTATION.md` |

## Path Helper Functions

Add to `includes/functions.php`:

```php
/**
 * Get the base URL of the application
 */
function base_url($path = '') {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $base = $protocol . '://' . $host . '/code/';
    return $base . ltrim($path, '/');
}

/**
 * Get asset URL
 */
function asset($path) {
    return base_url('assets/' . ltrim($path, '/'));
}

/**
 * Redirect to a page
 */
function redirect($path) {
    header('Location: ' . base_url($path));
    exit();
}
```

Usage:
```php
// In your PHP files
<link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
<script src="<?php echo asset('js/main.js'); ?>"></script>

// Redirect examples
redirect('public/client/dashboard.php');
redirect('index.php');
```

## Migration Checklist

- [ ] **Backup project** (create ZIP or use version control)
- [ ] **Run reorganize.php** (review dry run first)
- [ ] **Update require paths** in all PHP files
- [ ] **Update asset paths** in HTML/CSS
- [ ] **Test login flow**
- [ ] **Test client features**
- [ ] **Test staff features**
- [ ] **Test admin features**
- [ ] **Verify database connections**
- [ ] **Check file permissions**
- [ ] **Update documentation**
- [ ] **Test on different browsers**
- [ ] **Remove backup** (after verification)

---

**Created:** October 31, 2025  
**Version:** 1.0.0  
**For:** Enomy-Finances System
