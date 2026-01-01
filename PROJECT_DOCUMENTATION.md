# Cable TV Billing Software - Project Documentation

## 1. Overview
This project is a comprehensive **Cable TV Billing & Management System** built with **PHP** and **MySQL**. It is designed to manage customer subscriptions, individual and group billing, payment tracking, and various administrative reports. It also includes integrations for SMS notifications and Biometric attendance.

## 2. Features

### Core Modules
- **Authentication**: Secure login/logout for Admins and Employees. `check-login.php`, `logout.php`.
- **Customer Management**: Add, view, edit, and search customers (`customer-details.php`, `search-customer.php`, `customer-history.php`).
- **Billing System**:
    - **Individual Billing**: `billing-dashboard.php`, `adv-indiv-billing-dashboard.php` (Includes Integrated Calculator & Real-Time Payable)
    - **Group Billing**: `billing-group-dashboard.php` (Features: Manual Received Amount Verification, Detailed Confirmation)
    - **POS Billing**: Point of Sale interface (`pos-billing.php`, `pos-product.php`)
- **Utilities**:
    - **Global Calculator**: Accessible via Menu Bar. Features Casio-style UI, Keyboard Support, and Auto-Reset.
- **Employee Dashboard**: Standardized premium UI for employees (`employee-dashboard.php`) with collection summaries.
- **Admin Dashboard**: Central hub (`admin-dashboard.php`) featuring:
    - **Analytics Overview**: Interactive Visualization for Revenue Trends (Daily/Monthly), Collection Sources, and Payment Modes. (Visible only to Super Admin 'A').
    - **Privacy Mode**: One-click toggle to blur sensitive financial data, active by default.
    - **Data Filtering**: Date presets (Today, Yesterday, Custom) for precise reporting.
- **Payment & Cancellation**: Cancel bills (`admin-bill-cancel.php`), credit bills (`admin-bill-credit.php`).

### Reports
- **Financial Reports**: Income/Expense (`admin-in-ex-report.php`), Today's Collection (`todaycollection.php`).
- **Billing Reports**: Advance Bill Reports, Cancelled Bills, Unpaid Lists.
- **Biometric & Attendance**: Attendance tracking reports (`rpt-biometric-*.php`).

### Integrations
- **SMS Gateway**: Integrated in `dbconfig.php` for sending billing alerts and OTPs.
- **Biometric API**: Connection settings available in `dbconfig.php`.

## 3. Installation & Setup

### Prerequisites
- **Web Server**: WAMP, XAMPP, or any LAMP stack.
- **PHP**: Version 7.4 or higher recommended.
- **MySQL**: Database for storing user and billing data.

### Configuration
1. **Database Connection**:
   - Open `dbconfig.php`.
   - Update the database credentials:
     ```php
     $con = mysqli_connect("localhost", "root", "", "pdpctv_dt_com");
     ```
2. **SMS Settings**:
   - Configure `$SMS_API_KEY`, `$SMS_GATEWAY_URL`, and Template IDs in `dbconfig.php`.
3. **Timezone**:
   - Default is set to `'Asia/Kolkata'` in `dbconfig.php`.

## 4. File Structure Overview
- `assets/`: Static assets (images, CSS, JS).
- `api/`: Backend API endpoints.
- `domPDF_lib/`, `excel_lib/`: Libraries for generating PDF and Excel reports.
- `vendor/`: Composer dependencies.

## 5. Key Workflows
- **Creating a Bill**: Go to **Billing Dashboard**, search for a customer, and generate a bill.
- **Cancelling a Bill**: Navigate to `Action > Cancel Bill`, search for the bill by date, and approve/cancel.
- **Reports**: Use the **Report** menu to generate PDF/Excel reports for collections and dues.
