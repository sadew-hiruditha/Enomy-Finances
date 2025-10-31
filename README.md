# 🏦 Enomy-Finances

A web-based financial management system with currency conversion, investment calculators, and portfolio management.

## Features

- **Currency Converter** - Convert between 6 currencies (GBP, USD, EUR, BRL, JPY, TRY)
- **Investment Calculator** - 3 investment plans with projections for 1, 5, and 10 years
- **Mortgage Applications** - Submit and track mortgage applications
- **Portfolio Management** - Track all investments and mortgages
- **User Roles** - Client, Staff, and Admin access levels

## Installation

1. **Setup Database**
   ```bash
   # Create database
   CREATE DATABASE enomy_finances;
   
   # Run setup
   php database/run_setup.php
   ```

2. **Configure Database**
   ```php
   // Edit includes/config.php
   $host = 'localhost';
   $db   = 'enomy_finances';
   $user = 'root';
   $pass = ''; // Your password
   ```

3. **Create Admin Account**
   - Visit: `http://localhost/code/public/admin/add_admin_staff.php`
   - Create your admin account
   - Delete the file after setup

4. **Login**
   - Visit: `http://localhost/code/index.php`
   - Login with your credentials

## Usage

### Client Features
- Currency conversion with automatic fees
- Investment quote calculator
- Mortgage applications
- Portfolio overview

### Staff Features
- Review mortgage applications
- Manage client investments
- Update application status

### Admin Features
- Create/manage users
- Full system access
- Investment management

## Tech Stack

- **Backend:** PHP 8.0+
- **Database:** MySQL 5.7+
- **Frontend:** Bootstrap 5.3.2
- **Security:** Bcrypt, PDO, Session Management

## Project Structure

```
code/
├── index.php              # Login
├── register.php           # Registration
├── includes/              # Core PHP files
├── public/                # User pages
│   ├── client/           # Client portal
│   ├── staff/            # Staff portal
│   └── admin/            # Admin portal
├── database/             # Database scripts
└── assets/               # CSS, JS, Images
```

## Quick Start

```bash
# Start XAMPP (Apache + MySQL)
# Navigate to: http://localhost/code/index.php
```

## Security

- Password hashing with Bcrypt
- SQL injection protection via PDO
- Role-based access control
- Session management
- Input validation

## License

Proprietary - © 2025 RBSX Group

---

**Version:** 1.0.0 | **Date:** October 31, 2025
