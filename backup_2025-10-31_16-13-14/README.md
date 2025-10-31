# 🏦 Enomy-Finances Management System

> A comprehensive web-based financial management platform for RBSX Group

[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-blue)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-orange)](https://www.mysql.com/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3.2-purple)](https://getbootstrap.com/)
[![License](https://img.shields.io/badge/License-Proprietary-red)](LICENSE)

## 📋 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Screenshots](#screenshots)
- [Installation](#installation)
- [Folder Structure](#folder-structure)
- [Usage](#usage)
- [Documentation](#documentation)
- [Technology Stack](#technology-stack)
- [Security](#security)
- [Support](#support)

## 🌟 Overview

Enomy-Finances is a full-featured financial management system that enables clients to manage their investments, apply for mortgages, convert currencies, and plan their financial future. The system includes separate portals for clients, staff, and administrators with role-based access control.

### Key Capabilities

- **Multi-Currency Transactions** - Convert between 6 major currencies with automatic fee calculation
- **Investment Planning** - Calculate returns on 3 different investment products with tax considerations
- **Mortgage Management** - Submit and track mortgage applications
- **Portfolio Tracking** - Monitor all investments and applications in one place
- **Secure Access** - Role-based authentication and authorization

## ✨ Features

### 👤 For Clients

- ✅ **Currency Converter**
  - Support for 6 currencies (GBP, USD, EUR, BRL, JPY, TRY)
  - Transaction limits: £300 - £5,000
  - Tiered fee structure (1.5% - 3.5%)
  - Transaction history tracking

- ✅ **Investment Calculator**
  - 3 investment plans with different risk profiles
  - Projections for 1, 5, and 10 years
  - Min/Max return scenarios
  - Automatic tax and fee calculations
  - Quote saving and history

- ✅ **Mortgage Applications**
  - Online application submission
  - Real-time status tracking
  - Property and loan details management

- ✅ **Investment Portfolio**
  - Create and track investments
  - 10 different product types
  - Maturity date tracking
  - Portfolio value overview

### 👔 For Staff

- ✅ **Application Management**
  - View all client mortgage applications
  - Update application status
  - Client information access

- ✅ **Investment Oversight**
  - View all client investments
  - Monitor portfolio performance
  - Investment management tools

### 👨‍💼 For Administrators

- ✅ **User Management**
  - Create admin and staff accounts
  - Role assignment
  - User overview and statistics

- ✅ **System Administration**
  - Full access to all features
  - Investment deletion capability
  - System-wide oversight

## 📸 Screenshots

### Client Dashboard
Clean, modern interface with portfolio overview and quick actions.

### Currency Converter
Real-time conversion with transparent fee structure.

### Investment Calculator
Detailed projections with comprehensive breakdown of returns, fees, and taxes.

## 🚀 Installation

### Prerequisites

- **PHP 7.4 or higher**
- **MySQL 5.7 or higher**
- **Apache/Nginx** web server
- **mod_rewrite** enabled (for clean URLs)

### Step-by-Step Setup

1. **Clone or Download the Project**
   ```bash
   cd c:\xampp\htdocs
   # Extract project files to 'code' folder
   ```

2. **Create Database**
   ```sql
   CREATE DATABASE enomy_finances CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

3. **Configure Database Connection**
   ```php
   // Edit config.php
   $host = 'localhost';
   $db   = 'enomy_finances';
   $user = 'root';
   $pass = ''; // Your MySQL password
   ```

4. **Run Database Setup**
   
   **Option A: Web Interface**
   ```
   http://localhost/code/run_setup.php
   ```
   
   **Option B: Command Line**
   ```bash
   php run_setup.php
   ```
   
   **Option C: phpMyAdmin**
   - Import `setup.sql`

5. **Create Admin Account**
   ```
   http://localhost/code/add_admin_staff.php
   ```
   - Create admin user
   - Create staff user (optional)
   - **⚠️ DELETE this file after setup for security**

6. **Access the System**
   ```
   http://localhost/code/index.php
   ```

### Quick Start

```bash
# Navigate to project
cd c:\xampp\htdocs\code

# Run setup
php run_setup.php

# Start XAMPP services
# - Apache
# - MySQL

# Open browser
start http://localhost/code/index.php
```

## 📁 Folder Structure

```
code/
├── 📄 index.php                    # Login page
├── 📄 register.php                 # Client registration
├── 📄 logout.php                   # Logout handler
│
├── 📁 assets/                      # Static assets
│   ├── css/style.css               # Modern UI styles
│   ├── js/                         # JavaScript files
│   └── images/                     # Images and icons
│
├── 📁 includes/                    # Core PHP files
│   ├── config.php                  # Database config
│   └── functions.php               # Business logic
│
├── 📁 public/                      # User pages
│   ├── client/                     # Client portal
│   ├── staff/                      # Staff portal
│   └── admin/                      # Admin portal
│
├── 📁 database/                    # Database files
│   ├── setup.sql                   # Schema
│   └── run_setup.php               # Setup script
│
└── 📁 docs/                        # Documentation
    ├── SYSTEM_DOCUMENTATION.md     # Full documentation
    ├── PROJECT_STRUCTURE.md        # Folder guide
    └── ...                         # Other docs
```

**📖 For detailed structure:** See [PROJECT_STRUCTURE.md](PROJECT_STRUCTURE.md)

## 📖 Usage

### For Clients

1. **Register Account**
   - Visit: `http://localhost/code/register.php`
   - Fill in personal details
   - Create account

2. **Login**
   - Use your username and password
   - Access client dashboard

3. **Convert Currency**
   - Navigate to Currency Converter
   - Select currencies and amount
   - View conversion with fees

4. **Calculate Investment**
   - Choose investment plan
   - Enter amounts
   - View projections and save quote

5. **Apply for Mortgage**
   - Fill mortgage application
   - Submit and track status

### For Staff/Admin

1. **Login with Admin Credentials**
   - Created during setup

2. **Manage Applications**
   - View pending mortgages
   - Update status
   - Review client portfolios

3. **Create Users** (Admin only)
   - Access admin dashboard
   - Create staff/admin accounts

## 📚 Documentation

| Document | Description |
|----------|-------------|
| [SYSTEM_DOCUMENTATION.md](docs/SYSTEM_DOCUMENTATION.md) | Complete system documentation |
| [PROJECT_STRUCTURE.md](PROJECT_STRUCTURE.md) | Folder structure guide |
| [REQUIREMENTS_IMPLEMENTATION.md](docs/REQUIREMENTS_IMPLEMENTATION.md) | Features & requirements |
| [INVESTMENTS_INTEGRATION.md](docs/INVESTMENTS_INTEGRATION.md) | Investment features |
| [MODERN_UI_README.md](docs/MODERN_UI_README.md) | UI design system |

## 🛠️ Technology Stack

### Backend
- **PHP 7.4+** - Server-side scripting
- **MySQL 5.7+** - Database management
- **PDO** - Database abstraction layer

### Frontend
- **HTML5** - Markup
- **CSS3** - Styling with custom design system
- **Bootstrap 5.3.2** - Responsive framework
- **JavaScript (ES6)** - Client-side interactivity

### Security
- **Bcrypt** - Password hashing
- **Prepared Statements** - SQL injection prevention
- **Session Management** - Secure authentication
- **Role-Based Access Control** - Authorization

## 🔒 Security Features

- ✅ **Password Hashing** - Bcrypt with salt
- ✅ **SQL Injection Protection** - PDO prepared statements
- ✅ **XSS Prevention** - Input sanitization
- ✅ **Session Security** - Secure session handling
- ✅ **CSRF Protection** - POST form validation
- ✅ **Role-Based Access** - Authorization checks on every page
- ✅ **Input Validation** - Server-side validation

### Security Best Practices

1. **Change Default Credentials** - After setup, change admin password
2. **Delete Setup Files** - Remove `add_admin_staff.php` after use
3. **Use HTTPS** - In production, enable SSL
4. **Regular Backups** - Backup database regularly
5. **Update Passwords** - Enforce password policy

## 📊 Database Schema

### Tables

1. **users** - User authentication
2. **client_profiles** - Client information
3. **mortgages** - Mortgage applications
4. **investments** - Investment portfolio
5. **currency_transactions** - Currency conversion history
6. **investment_quotes** - Investment calculations
7. **appointments** - Client-staff appointments
8. **messages** - Internal messaging

**📖 Full schema:** See [setup.sql](database/setup.sql)

## 🎨 Design System

### Color Palette

```css
--primary-color: #4F46E5    /* Indigo */
--success-color: #10B981    /* Green */
--warning-color: #F59E0B    /* Amber */
--danger-color: #EF4444     /* Red */
--info-color: #3B82F6       /* Blue */
```

### Features

- 🎨 Modern gradient backgrounds
- ✨ Smooth animations and transitions
- 📱 Fully responsive design (320px - 2560px)
- ♿ Accessible components
- 🌙 Professional color scheme

## 🔧 Configuration

### Database Settings

Edit `includes/config.php`:

```php
$host = 'localhost';        // Database host
$db   = 'enomy_finances';   // Database name
$user = 'root';             // Database user
$pass = '';                 // Database password
```

### Environment

```php
define('ENVIRONMENT', 'development'); // or 'production'
define('DEBUG', true);                 // false in production
```

## 🐛 Troubleshooting

### Common Issues

**Database Connection Failed**
- Check MySQL is running
- Verify credentials in `config.php`
- Ensure database exists

**Login Not Working**
- Clear browser cache
- Check user exists in database
- Verify password hash

**Page Not Found**
- Check file paths
- Verify mod_rewrite enabled
- Check .htaccess file

**📖 More help:** See [Troubleshooting Section](docs/SYSTEM_DOCUMENTATION.md#troubleshooting)

## 🔄 Updates & Maintenance

### Backup

```bash
# Database backup
mysqldump -u root -p enomy_finances > backup.sql

# File backup
zip -r backup.zip code/
```

### Restore

```bash
# Restore database
mysql -u root -p enomy_finances < backup.sql
```

## 📝 Changelog

### Version 1.0.0 (October 31, 2025)

#### Added
- ✅ Complete authentication system
- ✅ Currency converter (6 currencies)
- ✅ Investment calculator (3 plans)
- ✅ Mortgage application system
- ✅ Investment portfolio management
- ✅ Modern responsive UI
- ✅ Role-based access control
- ✅ Complete documentation

#### Features
- Multi-currency conversion with tiered fees
- Investment projections with tax calculations
- Client, staff, and admin portals
- Transaction history tracking
- Quote saving and retrieval

## 🤝 Support

### Getting Help

1. **Documentation** - Check [SYSTEM_DOCUMENTATION.md](docs/SYSTEM_DOCUMENTATION.md)
2. **Troubleshooting** - See troubleshooting section
3. **Database** - Verify schema matches setup.sql

### Contact

- **Email:** support@rbsx.com
- **Website:** www.rbsx.com

## 📄 License

Proprietary - © 2025 RBSX Group. All rights reserved.

This software is for internal use only. Unauthorized copying, distribution, or modification is prohibited.

## 🙏 Acknowledgments

- Built with Bootstrap 5.3.2
- Modern UI design inspired by contemporary fintech applications
- Security best practices following OWASP guidelines

---

**Version:** 1.0.0  
**Release Date:** October 31, 2025  
**Last Updated:** October 31, 2025

**Made with ❤️ for RBSX Group**
