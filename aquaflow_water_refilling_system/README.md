# AquaFlow Water Refilling Station Management System

A PHP + MySQL starter project styled to match the supplied glossy blue water/glass UI reference.

## Requirements
- PHP 8.1+
- MySQL 5.7+ / MariaDB 10.4+
- Apache (XAMPP recommended)

## Install on XAMPP
1. Extract `aquaflow_water_refilling_system.zip` into `C:\xampp\htdocs\`.
2. Start Apache and MySQL in XAMPP.
3. Open phpMyAdmin and import `database/schema.sql`.
4. Edit `config/database.php` if your MySQL username/password differs.
5. Visit `http://localhost/aquaflow_water_refilling_system/`.
6. Default demo login: `admin` / `admin123`.

Change the default password immediately after first login. This starter stores passwords using PHP `password_hash()`.

## HTTPS / SSL
HTTPS is a server configuration, not something a PHP folder can turn on by itself.
- For local testing, configure Apache SSL in XAMPP and use a locally trusted/self-signed certificate.
- For public deployment, point a domain to your hosting/server and install a valid certificate (for example, Let's Encrypt or your hosting provider's SSL).
- Then enable `APP_FORCE_HTTPS` in `config/app.php`.
- Keep backups outside the public web root on production hosting.

## Backup
The Backup & Restore page creates SQL backups into `storage/backups/`. On production, move this folder outside the public web root and schedule `php scripts/backup.php` using cron/Task Scheduler. The included restore action is intentionally CLI-only for safety; restore from a trusted backup using MySQL/phpMyAdmin or your hosting control panel.

## Notes
This is a starter implementation with working login, dashboard, navigation, sample records, customer/order/product/inventory/dealer listing views, activity log, security page, and backup generation. Extend validation, permissions, audit coverage, and operational workflows before production use. Do not expose demo credentials or backup files publicly.
