# CABLE TV BILLING SOFTWARE

## Overview
A comprehensive web-based billing and management solution for Cable TV operators. This system handles customer subscriptions, individual and group billing, payment tracking, and generates detailed financial reports.

**For detailed documentation, please refer to [PROJECT_DOCUMENTATION.md](PROJECT_DOCUMENTATION.md)**

## Key Features
- **Customer Management**: Complete CRUD operations for customer profiles.
- **Billing**: Individual, Group, and POS billing capabilities.
- **Reports**: Daily collections, income/expense, unpaid lists, and tax reports.
- **Integrations**: SMS Gateway for alerts and Biometric attendance tracking.

## Development Updates

### Latest Changes
### Latest Changes
- **UI Consistency**: Unified Admin and Employee dashboards with Premium Gradient Blue theme (Bootstrap 5). Updated `menu-bar.php`, `sub-menu-btn.php`, and `employee-dashboard.php`.
- **Group Billing**: Implemented `transaction_id` for robust data linking. Fixed `rptgroupbill.php` grand total calculations and added "Billing Date" vs "Entry Date" columns.
- **Bug Fixes**: Fixed `bill-last5-print.php` undefined array key error and corrected POS print button linking.
- **UI Redesign**: Modernized `admin-bill-cancel.php`, `export-stbno.php`, and `profile.php`.
- **POS Reports**: Enhanced `rptposinvoice.php` with dynamic "Billed By" user filtering.
- **Biometric**: Added Tabular and Calendar reports for biometric attendance.
- **Security**: Added passcode in profile for hassle-free login without OTP.
- **Settings**: Added SMS turn on/off toggle in App Settings.
- **Printing**: Added 3-inch print view for Income/Expense reports.

### Previous Notes
- Added prompt in credit and cancel action before processing.
- Admin dashboard: removed all widgets except SMS credit.
- Removed deprecated `component2` code.
