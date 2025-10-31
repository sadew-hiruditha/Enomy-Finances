# 📁 Enomy-Finances Project Folder Structure

## Current Folder Organization

```
code/
│
├── 📁 assets/                          # Static assets (CSS, JS, Images)
│   ├── 📁 css/
│   │   └── style.css                   # Main stylesheet (modern UI)
│   ├── 📁 js/
│   │   └── (JavaScript files)          # Custom scripts
│   └── 📁 images/
│       └── (Image files)               # Logos, icons, graphics
│
├── 📁 includes/                        # Core PHP includes
│   ├── config.php                      # Database configuration
│   └── functions.php                   # Core business logic functions
│
├── 📁 database/                        # Database scripts
│   ├── setup.sql                       # Main database schema
│   ├── run_setup.php                   # Database setup script
│   └── insert_admin_staff.sql          # Sample data (if needed)
│
├── 📁 docs/                            # Documentation
│   ├── SYSTEM_DOCUMENTATION.md         # Complete system documentation
│   ├── REQUIREMENTS_IMPLEMENTATION.md  # Requirements & features
│   ├── INVESTMENTS_INTEGRATION.md      # Investment features guide
│   ├── MODERN_UI_README.md             # UI design documentation
│   └── FOLDER_STRUCTURE.md             # This file
│
├── 📁 public/                          # Publicly accessible files
│   │
│   ├── 📁 client/                      # Client portal pages
│   │   ├── dashboard.php               # Client dashboard
│   │   ├── add_mortgage.php            # Mortgage application
│   │   ├── add_investment.php          # Investment creation
│   │   ├── currency_converter.php      # Currency conversion tool
│   │   └── investment_calculator.php   # Investment calculator
│   │
│   ├── 📁 staff/                       # Staff portal pages
│   │   ├── dashboard.php               # Staff dashboard
│   │   └── manage_investments.php      # Investment management
│   │
│   └── 📁 admin/                       # Admin portal pages
│       ├── dashboard.php               # Admin dashboard
│       └── add_admin_staff.php         # User creation (admin/staff)
│
├── 📄 index.php                        # Login page (entry point)
├── 📄 register.php                     # Client registration
├── 📄 logout.php                       # Logout handler
├── 📄 .htaccess                        # Apache configuration (if needed)
└── 📄 README.md                        # Project overview

```

## Recommended Structure (Best Practices)

For larger applications, consider this structure:

```
code/
│
├── 📁 app/                             # Application core
│   ├── 📁 Controllers/                 # Controller classes
│   ├── 📁 Models/                      # Database models
│   ├── 📁 Views/                       # Template files
│   └── 📁 Helpers/                     # Helper functions
│
├── 📁 config/                          # Configuration files
│   ├── database.php                    # Database config
│   ├── app.php                         # Application config
│   └── constants.php                   # Constants
│
├── 📁 public/                          # Public web root
│   ├── 📁 assets/                      # Static assets
│   │   ├── css/
│   │   ├── js/
│   │   └── images/
│   └── index.php                       # Front controller
│
├── 📁 storage/                         # Application storage
│   ├── 📁 logs/                        # Log files
│   ├── 📁 cache/                       # Cache files
│   └── 📁 uploads/                     # User uploads
│
├── 📁 database/                        # Database files
│   ├── 📁 migrations/                  # Database migrations
│   ├── 📁 seeds/                       # Database seeders
│   └── schema.sql                      # Schema file
│
├── 📁 tests/                           # Testing files
│   ├── 📁 unit/                        # Unit tests
│   └── 📁 integration/                 # Integration tests
│
├── 📁 vendor/                          # Composer dependencies
│
└── 📁 docs/                            # Documentation
```

## File Organization by Function

### 🔐 Authentication & Authorization
```
Root Level:
├── index.php                    # Login page
├── register.php                 # Registration page
├── logout.php                   # Logout handler

Includes:
└── functions.php                # Auth functions (is_logged_in, require_login)
```

### 👤 Client Features
```
public/client/:
├── dashboard.php                # Main dashboard
├── add_mortgage.php             # Mortgage application
├── add_investment.php           # Investment creation
├── currency_converter.php       # Currency conversion
└── investment_calculator.php    # Investment quotes
```

### 👔 Staff Features
```
public/staff/:
├── dashboard.php                # Staff dashboard
└── manage_investments.php       # Investment management
```

### 👨‍💼 Admin Features
```
public/admin/:
├── dashboard.php                # Admin dashboard
└── add_admin_staff.php          # User creation
```

### 🗄️ Database
```
database/:
├── setup.sql                    # Full schema
├── run_setup.php                # Setup automation
└── migrations/                  # Future migrations
```

### 🎨 Assets
```
assets/:
├── css/
│   ├── style.css                # Main styles
│   ├── dashboard.css            # Dashboard specific
│   └── forms.css                # Form styles
├── js/
│   ├── main.js                  # Global JavaScript
│   ├── calculator.js            # Calculator logic
│   └── validation.js            # Form validation
└── images/
    ├── logo.png
    ├── icons/
    └── backgrounds/
```

### 📚 Documentation
```
docs/:
├── SYSTEM_DOCUMENTATION.md      # Complete system docs
├── API_REFERENCE.md             # API documentation
├── DEPLOYMENT_GUIDE.md          # Deployment instructions
└── USER_MANUAL.md               # User guide
```

## Migration Guide

To reorganize the current structure, use the following PowerShell commands:

### Step 1: Move Core Includes
```powershell
Move-Item config.php includes/
Move-Item functions.php includes/
```

### Step 2: Move Database Files
```powershell
Move-Item setup.sql database/
Move-Item run_setup.php database/
Move-Item insert_admin_staff.sql database/ -ErrorAction SilentlyContinue
```

### Step 3: Move Client Pages
```powershell
Move-Item dashboard_client.php public/client/dashboard.php
Move-Item add_mortgage.php public/client/
Move-Item add_investment.php public/client/
Move-Item currency_converter.php public/client/
Move-Item investment_calculator.php public/client/
```

### Step 4: Move Staff Pages
```powershell
Move-Item dashboard_staff.php public/staff/dashboard.php
Move-Item manage_investments.php public/staff/
```

### Step 5: Move Admin Pages
```powershell
Move-Item dashboard_admin.php public/admin/dashboard.php
Move-Item add_admin_staff.php public/admin/
```

### Step 6: Move Documentation
```powershell
Move-Item SYSTEM_DOCUMENTATION.md docs/
Move-Item REQUIREMENTS_IMPLEMENTATION.md docs/
Move-Item INVESTMENTS_INTEGRATION.md docs/
Move-Item MODERN_UI_README.md docs/
Move-Item FOLDER_STRUCTURE.md docs/
```

### Step 7: Update File Paths
After moving files, update all `require_once` statements:

**Before:**
```php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/config.php';
```

**After (for files in public/client/):**
```php
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/config.php';
```

## Path Reference Chart

| File Location | Path to includes/ | Path to assets/ |
|---------------|-------------------|-----------------|
| Root (index.php) | `./includes/` | `./assets/` |
| public/client/*.php | `../../includes/` | `../../assets/` |
| public/staff/*.php | `../../includes/` | `../../assets/` |
| public/admin/*.php | `../../includes/` | `../../assets/` |

## .htaccess Configuration

For clean URLs and security, add `.htaccess` in root:

```apache
# Prevent directory browsing
Options -Indexes

# Deny access to sensitive files
<FilesMatch "^(config|functions|\.env)\.php$">
    Order allow,deny
    Deny from all
</FilesMatch>

# Enable URL rewriting
RewriteEngine On

# Force HTTPS (optional, for production)
# RewriteCond %{HTTPS} off
# RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Custom error pages
ErrorDocument 404 /code/404.php
ErrorDocument 500 /code/500.php
```

## Security Considerations

### Protected Directories
These folders should NOT be web accessible:
- `/includes/` - Contains sensitive configuration
- `/database/` - Contains schema and setup scripts
- `/vendor/` - Third-party libraries
- `/storage/logs/` - Log files

### Public Directories
Only these should be accessible:
- `/public/` - All user-facing pages
- `/assets/` - CSS, JS, images
- Root level: `index.php`, `register.php`, `logout.php`

### Recommended Protection
Add this to `.htaccess` in `/includes/`:
```apache
Order deny,allow
Deny from all
```

## Environment-Specific Configurations

### Development
```php
// config.php
define('ENVIRONMENT', 'development');
define('DEBUG', true);
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

### Production
```php
// config.php
define('ENVIRONMENT', 'production');
define('DEBUG', false);
error_reporting(0);
ini_set('display_errors', 0);
```

## Version Control (.gitignore)

Create `.gitignore` to exclude:
```
# Configuration
config.php
.env

# Logs
storage/logs/*.log

# Cache
storage/cache/*

# Uploads
storage/uploads/*

# Vendor
vendor/

# IDE
.vscode/
.idea/
*.sublime-*

# OS
.DS_Store
Thumbs.db
```

## Backup Structure

Recommended backup organization:
```
backups/
├── 2025-10-31/
│   ├── database/
│   │   └── enomy_finances_20251031.sql
│   └── files/
│       └── code_backup_20251031.zip
├── 2025-11-01/
└── ...
```

## File Naming Conventions

### PHP Files
- **Pages:** `dashboard.php`, `login.php` (lowercase, underscores)
- **Classes:** `UserController.php`, `InvestmentModel.php` (PascalCase)
- **Includes:** `functions.php`, `helpers.php` (lowercase)

### CSS/JS Files
- **Main:** `style.css`, `main.js`
- **Component:** `dashboard.css`, `calculator.js`
- **Minified:** `style.min.css`, `main.min.js`

### Database
- **Schema:** `schema.sql`, `setup.sql`
- **Migrations:** `001_create_users_table.sql`
- **Seeds:** `seed_admin_users.sql`

## Future Enhancements

### Composer Setup
```bash
composer init
composer require phpmailer/phpmailer
composer require monolog/monolog
```

### Autoloading
Add to `composer.json`:
```json
{
  "autoload": {
    "psr-4": {
      "App\\": "app/"
    },
    "files": [
      "includes/functions.php"
    ]
  }
}
```

### Environment Variables
Use `.env` file for configuration:
```env
DB_HOST=localhost
DB_NAME=enomy_finances
DB_USER=root
DB_PASS=
APP_ENV=production
APP_DEBUG=false
```

## Maintenance

### Regular Cleanup
- Clear cache files weekly
- Rotate log files monthly
- Archive old backups quarterly
- Review and optimize database quarterly

### File Permissions
```bash
# Files: 644
chmod 644 *.php

# Directories: 755
chmod 755 public/ includes/ assets/

# Writable directories: 775
chmod 775 storage/ storage/logs/ storage/cache/
```

---

## Quick Start After Restructuring

1. **Update all require paths** in PHP files
2. **Update all asset paths** in HTML/PHP (CSS, JS links)
3. **Test all pages** to ensure they load correctly
4. **Verify database connections** work from new locations
5. **Check file permissions** are correct
6. **Update documentation** with new paths
7. **Test authentication flow** completely
8. **Verify all features** work as expected

---

**Last Updated:** October 31, 2025  
**Version:** 1.0.0
