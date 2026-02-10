# Vamika - Complete Project Documentation

## Table of Contents
1. [Project Overview](#project-overview)
2. [User Roles & Permissions](#user-roles--permissions)
3. [Architecture & Structure](#architecture--structure)
4. [Authentication Flow](#authentication-flow)
5. [Module Breakdown](#module-breakdown)
6. [Routes & Controllers Mapping](#routes--controllers-mapping)
7. [Database Schema](#database-schema)
8. [Features & Workflows](#features--workflows)
9. [API Endpoints Summary](#api-endpoints-summary)

---

## Project Overview

**Vamika** is a multi-role Laravel application designed to manage B2B sales operations. It serves three primary user types:

- **Admin**: System administrator managing overall operations
- **Salesperson**: Field representatives selling products to shops
- **Shop Owner**: Retail partners purchasing products and managing orders

The platform facilitates:
- Product catalog management
- Order management and tracking
- Sales performance monitoring
- Bit/territory assignment
- Wallet & payment management
- Referral programs

**Tech Stack:**
- Backend: Laravel (PHP)
- Frontend: Blade Templating with Vite
- Database: Laravel migrations (MySQL/PostgreSQL compatible)
- Authentication: Laravel built-in Auth
- Testing: PHPUnit

---

## User Roles & Permissions

### 1. **Admin**
- **Prefix Route**: `/admin`
- **Middleware**: `auth`, `admin`
- **Responsibilities**:
  - Manage all users (create, edit, view)
  - Manage salespersons and their assignments
  - Manage bits/territories
  - Product management and inventory
  - Order monitoring and status updates
  - Offer/promotion management
  - Sales reports and analytics
  - System settings

### 2. **Salesperson**
- **Prefix Route**: `/salesperson`
- **Middleware**: `auth`, `salesperson`
- **Responsibilities**:
  - View assigned shops
  - Create and manage orders for shops
  - Track product inventory
  - Record shop visits
  - View sales performance
  - Manage personal profile
  - Generate invoices

### 3. **Shop Owner**
- **Prefix Route**: `/shop-owner`
- **Middleware**: `auth`, `shop-owner`
- **Responsibilities**:
  - Browse and view products
  - Place orders
  - Manage shopping cart
  - Checkout process
  - Track order history
  - View invoices
  - Manage wallet/payments
  - View referral information
  - Update profile

---

## Architecture & Structure

### Directory Structure

```
vamika/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/           # Admin-specific logic
│   │   │   ├── Salesperson/     # Salesperson-specific logic
│   │   │   ├── ShopOwner/       # Shop Owner-specific logic
│   │   │   └── Auth/            # Authentication logic
│   │   └── Middleware/          # Custom middleware (auth checks, role checks)
│   ├── Models/                  # Database models
│   └── Providers/               # Service providers
├── database/
│   ├── migrations/              # Database schema
│   ├── factories/               # Model factories for testing
│   └── seeders/                 # Database seeders
├── routes/
│   └── web.php                  # All web routes
├── resources/
│   ├── views/                   # Blade templates
│   │   ├── admin/               # Admin UI
│   │   ├── salesperson/         # Salesperson UI
│   │   ├── shop-owner/          # Shop Owner UI
│   │   ├── auth/                # Authentication pages
│   │   └── layouts/             # Layout templates
│   ├── css/                     # Stylesheets
│   └── js/                      # JavaScript files
├── config/                      # Configuration files
├── tests/                       # Test files
└── public/                      # Public assets
```

### Middleware Flow

```
Request
  ↓
[Public Routes - No Auth Required]
  ├── GET  / → Login Form
  ├── GET  /login → Login Form
  ├── POST /login → Authenticate
  ├── GET  /register → Registration Form
  ├── POST /register → Create User
  ├── GET  /forgot-password → Password Reset Form
  └── POST /forgot-password → Send Reset Link
  ↓
[Protected Routes - Auth Required]
  ├── POST /logout → Clear Session
  ├── Admin Routes (auth + admin middleware)
  ├── Salesperson Routes (auth + salesperson middleware)
  └── Shop Owner Routes (auth + shop-owner middleware)
```

---

## Authentication Flow

### User Registration Flow
```
1. User visits /register
2. RegisterController::showRegistrationForm() → Shows registration form
3. User submits form (POST /register)
4. RegisterController::register() 
   ├── Validate input
   ├── Create User record
   ├── Assign role (admin/salesperson/shop-owner)
   └── Redirect to login
5. User logs in with credentials
```

### User Login Flow
```
1. User visits /login
2. LoginController::showLoginForm() → Shows login form
3. User submits credentials (POST /login)
4. LoginController::login()
   ├── Validate credentials
   ├── Check user role
   ├── Create session
   ├── Redirect to appropriate dashboard
   │  ├── Admin → /admin/dashboard
   │  ├── Salesperson → /salesperson/dashboard
   │  └── Shop Owner → /shop-owner/dashboard
   └── Return to login if failed
5. User session maintained via Laravel Session
```

### Logout Flow
```
1. User clicks Logout
2. POST /logout
3. LoginController::logout()
   ├── Clear session
   ├── Invalidate token
   └── Redirect to login
```

---

## Module Breakdown

### 📊 Admin Module

#### Dashboard
- **Route**: `GET /admin/dashboard`
- **Controller**: `AdminDashboard@index`
- **View**: `admin/dashboard.blade.php`
- **Purpose**: Overview of system statistics, sales, orders

#### User Management
| Route | Method | Controller | Purpose |
|-------|--------|-----------|---------|
| `/admin/users` | GET | `AdminUser@index` | List all users |
| `/admin/users/create` | GET | `AdminUser@create` | Create user form |
| `/admin/users/{id}/edit` | GET | `AdminUser@edit` | Edit user form |

#### Salesperson Management
| Route | Method | Controller | Purpose |
|-------|--------|-----------|---------|
| `/admin/salespersons` | GET | `AdminUser@salespersons` | List salespersons |
| `/admin/salespersons/{id}/details` | GET | `AdminUser@salespersonDetails` | View salesperson details |
| `/admin/salespersons/assign` | GET | `AdminUser@assignSalespersonForm` | Assign form |
| `/admin/salespersons/top` | GET | `AdminUser@topSalespersons` | Top performers |
| `/admin/assignments` | GET | `AdminBit@viewAssignments` | View bit assignments |

#### Bit/Territory Management
| Route | Method | Controller | Purpose |
|-------|--------|-----------|---------|
| `/admin/bits` | GET | `AdminBit@index` | List bits |
| `/admin/bits/create` | GET | `AdminBit@create` | Create bit form |
| `/admin/bits/{id}/edit` | GET | `AdminBit@edit` | Edit bit form |
| `/admin/bits/{id}/performance` | GET | `AdminBit@performance` | Bit performance metrics |
| `/admin/bits/assign` | GET | `AdminBit@assignForm` | Assign salesperson to bit |

#### Product Management
| Route | Method | Controller | Purpose |
|-------|--------|-----------|---------|
| `/admin/products` | GET | `AdminProduct@index` | List products |
| `/admin/products/create` | GET | `AdminProduct@create` | Create product form |
| `/admin/products/{id}/edit` | GET | `AdminProduct@edit` | Edit product form |
| `/admin/products/stock` | GET | `AdminProduct@stock` | Inventory management |
| `/admin/products/top` | GET | `AdminProduct@top` | Best selling products |

#### Order Management
| Route | Method | Controller | Purpose |
|-------|--------|-----------|---------|
| `/admin/orders` | GET | `AdminOrder@index` | List all orders |
| `/admin/orders/{id}` | GET | `AdminOrder@show` | View order details |
| `/admin/orders/{id}/details` | GET | `AdminOrder@details` | Detailed order info |
| `/admin/orders/consolidation` | GET | `AdminOrder@consolidation` | Consolidated orders report |
| `/admin/orders/{id}/update-status` | GET | `AdminOrder@updateStatusForm` | Update order status |

#### Offer Management
| Route | Method | Controller | Purpose |
|-------|--------|-----------|---------|
| `/admin/offers` | GET | `AdminOffer@index` | List offers/promotions |
| `/admin/offers/create` | GET | `AdminOffer@create` | Create offer form |
| `/admin/offers/{id}` | GET | `AdminOffer@show` | View offer details |

#### Reports & Analytics
| Route | Method | Controller | Purpose |
|-------|--------|-----------|---------|
| `/admin/shops/analysis` | GET | `AdminReport@shopAnalysis` | Shop performance analysis |
| `/admin/shops/top` | GET | `AdminReport@topShops` | Top performing shops |
| `/admin/reports` | GET | `AdminReport@index` | Reports dashboard |
| `/admin/reports/visit` | GET | `AdminReport@visitReports` | Salesperson visit reports |

#### Settings
| Route | Method | Controller | Purpose |
|-------|--------|-----------|---------|
| `/admin/settings` | GET | `AdminSettings@index` | System settings |

---

### 👤 Salesperson Module

#### Dashboard
- **Route**: `GET /salesperson/dashboard`
- **Controller**: `SalespersonDashboard@index`
- **View**: `salesperson/dashboard.blade.php`
- **Purpose**: Personal sales metrics and quick stats

#### Shop Management
| Route | Method | Controller | Purpose |
|-------|--------|-----------|---------|
| `/salesperson/shops` | GET | `SalespersonShop@index` | List assigned shops |
| `/salesperson/shops/select` | GET | `SalespersonShop@select` | Select shop to work with |
| `/salesperson/shops/{id}` | GET | `SalespersonShop@show` | View shop details |

#### Product Browsing
| Route | Method | Controller | Purpose |
|-------|--------|-----------|---------|
| `/salesperson/products` | GET | `SalespersonProduct@index` | Browse product catalog |

#### Order Management
| Route | Method | Controller | Purpose |
|-------|--------|-----------|---------|
| `/salesperson/orders/create` | GET | `SalespersonOrder@create` | Create order for shop |
| `/salesperson/orders/{id}/review` | GET | `SalespersonOrder@review` | Review order before submit |
| `/salesperson/orders/{id}/invoice` | GET | `SalespersonOrder@invoice` | View order invoice |

#### Visit Tracking
| Route | Method | Controller | Purpose |
|-------|--------|-----------|---------|
| `/salesperson/visits` | GET | `SalespersonVisit@index` | Track shop visits |

#### Sales Reports
| Route | Method | Controller | Purpose |
|-------|--------|-----------|---------|
| `/salesperson/sales` | GET | `SalespersonDashboard@sales` | Sales performance |

#### Profile Management
| Route | Method | Controller | Purpose |
|-------|--------|-----------|---------|
| `/salesperson/profile` | GET | `SalespersonProfile@index` | View profile |

---

### 🏪 Shop Owner Module

#### Dashboard
- **Route**: `GET /shop-owner/dashboard`
- **Controller**: `ShopOwnerDashboard@index`
- **View**: `shop-owner/dashboard.blade.php`
- **Purpose**: Shop overview and quick stats

#### Product Browsing
| Route | Method | Controller | Purpose |
|-------|--------|-----------|---------|
| `/shop-owner/products` | GET | `ShopOwnerProduct@index` | Browse available products |

#### Shopping Cart
| Route | Method | Controller | Purpose |
|-------|--------|-----------|---------|
| `/shop-owner/cart` | GET | `ShopOwnerCart@index` | View shopping cart |

#### Checkout
| Route | Method | Controller | Purpose |
|-------|--------|-----------|---------|
| `/shop-owner/checkout` | GET | `ShopOwnerCheckout@index` | Checkout process |

#### Order Management
| Route | Method | Controller | Purpose |
|-------|--------|-----------|---------|
| `/shop-owner/orders` | GET | `ShopOwnerOrder@index` | View all orders |
| `/shop-owner/orders/{id}` | GET | `ShopOwnerOrder@show` | View order details |
| `/shop-owner/orders/{id}/details` | GET | `ShopOwnerOrder@details` | Detailed order info |

#### Invoice Management
| Route | Method | Controller | Purpose |
|-------|--------|-----------|---------|
| `/shop-owner/invoices` | GET | `ShopOwnerOrder@invoices` | List invoices |
| `/shop-owner/invoices/{id}` | GET | `ShopOwnerOrder@invoice` | View invoice |

#### Wallet Management
| Route | Method | Controller | Purpose |
|-------|--------|-----------|---------|
| `/shop-owner/wallet` | GET | `ShopOwnerWallet@index` | Wallet and payments |

#### Profile Management
| Route | Method | Controller | Purpose |
|-------|--------|-----------|---------|
| `/shop-owner/profile` | GET | `ShopOwnerProfile@index` | View profile |
| `/shop-owner/profile/edit` | GET | `ShopOwnerProfile@edit` | Edit profile |

#### Referral Program
| Route | Method | Controller | Purpose |
|-------|--------|-----------|---------|
| `/shop-owner/referral` | GET | `ShopOwnerProfile@referral` | Referral information |

---

## Routes & Controllers Mapping

### Authentication Controllers

**LoginController** - Handles user authentication
```php
- showLoginForm()      → Display login page
- login()              → Process login (POST)
- logout()             → Clear session (POST)
```

**RegisterController** - Handles user registration
```php
- showRegistrationForm()  → Display registration page
- register()              → Process registration (POST)
```

**ForgotPasswordController** - Handles password reset
```php
- showLinkRequestForm()    → Display forgot password form
- sendResetLinkEmail()     → Send reset link (POST)
```

### Admin Controllers

**AdminDashboard**
```php
- index()  → Admin dashboard overview
```

**AdminUser**
```php
- index()                    → List all users
- create()                   → Create user form
- edit($id)                  → Edit user form
- salespersons()             → List salespersons
- salespersonDetails($id)    → Salesperson details
- assignSalespersonForm()    → Assign form
- topSalespersons()          → Top performers
```

**AdminBit**
```php
- index()              → List bits
- create()             → Create bit form
- edit($id)            → Edit bit form
- performance($id)     → Bit performance
- assignForm()         → Assign salesperson to bit
- viewAssignments()    → View all assignments
```

**AdminProduct**
```php
- index()          → List products
- create()         → Create product form
- edit($id)        → Edit product form
- stock()          → Inventory management
- top()            → Best selling products
```

**AdminOrder**
```php
- index()              → List orders
- show($id)            → Order details
- details($id)         → Detailed information
- consolidation()      → Consolidated report
- updateStatusForm($id)→ Update status form
```

**AdminOffer**
```php
- index()      → List offers
- create()     → Create offer form
- show($id)    → Offer details
```

**AdminReport**
```php
- index()          → Reports dashboard
- visitReports()   → Visit reports
- shopAnalysis()   → Shop analysis
- topShops()       → Top shops
```

**AdminSettings**
```php
- index()  → Settings page
```

### Salesperson Controllers

**SalespersonDashboard**
```php
- index()  → Dashboard overview
- sales()  → Sales performance
```

**SalespersonShop**
```php
- index()      → List assigned shops
- select()     → Select shop interface
- show($id)    → Shop details
```

**SalespersonProduct**
```php
- index()  → Product catalog
```

**SalespersonOrder**
```php
- create()          → Create order form
- review($id)       → Review order
- invoice($id)      → View invoice
```

**SalespersonVisit**
```php
- index()  → Visit tracking
```

**SalespersonProfile**
```php
- index()  → Profile view
```

### Shop Owner Controllers

**ShopOwnerDashboard**
```php
- index()  → Dashboard overview
```

**ShopOwnerProduct**
```php
- index()  → Product catalog
```

**ShopOwnerCart**
```php
- index()  → Shopping cart
```

**ShopOwnerCheckout**
```php
- index()  → Checkout process
```

**ShopOwnerOrder**
```php
- index()          → List orders
- show($id)        → Order details
- details($id)     → Detailed info
- invoices()       → List invoices
- invoice($id)     → View invoice
```

**ShopOwnerWallet**
```php
- index()  → Wallet management
```

**ShopOwnerProfile**
```php
- index()      → Profile view
- edit()       → Profile edit
- referral()   → Referral info
```

---

## Database Schema

### Migration Files

#### 1. `create_users_table` (0001_01_01_000000)
**Primary user table with essential fields**
```
Columns:
- id (Primary Key)
- name (String)
- email (String, Unique)
- email_verified_at (Timestamp, Nullable)
- password (String)
- remember_token (String, Nullable)
- created_at (Timestamp)
- updated_at (Timestamp)

Purpose: Core user authentication and identification
```

#### 2. `add_role_to_users_table` (2026_01_13_184605)
**Add role-based access control**
```
Additions:
- role (Enum: 'admin', 'salesperson', 'shop-owner')

Purpose: Distinguish between user types for authorization
```

#### 3. `create_cache_table` (0001_01_01_000001)
**Laravel cache storage**
```
Columns:
- key (Primary Key)
- value (Longtext)
- expiration (Integer)

Purpose: Store cached data
```

#### 4. `create_jobs_table` (0001_01_01_000002)
**Queue jobs storage**
```
Columns:
- id (Primary Key)
- queue (String)
- payload (Longtext)
- attempts (Integer)
- reserved_at (Integer, Nullable)
- available_at (Integer)
- created_at (Integer)

Purpose: Process queued jobs
```

### Expected Models

Based on the routes and features, the following models should exist:

| Model | Table | Purpose |
|-------|-------|---------|
| User | users | User accounts and authentication |
| Bit | bits | Geographic territories |
| Product | products | Product catalog |
| Order | orders | Customer orders |
| OrderItem | order_items | Individual items in orders |
| Offer | offers | Promotions and offers |
| Shop | shops | Shop owner businesses |
| Visit | visits | Salesperson shop visits |
| Wallet | wallets | Shop owner payment wallets |

---

## Features & Workflows

### Workflow 1: Admin Managing Sales Operations
```
1. Admin logs in → /admin/dashboard
2. Creates bit → /admin/bits/create
3. Creates products → /admin/products/create
4. Adds salesperson → /admin/users/create (role: salesperson)
5. Assigns salesperson to bit → /admin/bits/assign
6. Creates promotional offers → /admin/offers/create
7. Monitors orders → /admin/orders
8. Updates order status → /admin/orders/{id}/update-status
9. Views reports → /admin/reports
```

### Workflow 2: Salesperson Making Sales
```
1. Salesperson logs in → /salesperson/dashboard
2. Selects shop to visit → /salesperson/shops/select
3. Views shop details → /salesperson/shops/{id}
4. Browses products → /salesperson/products
5. Creates order → /salesperson/orders/create
6. Reviews order → /salesperson/orders/{id}/review
7. Generates invoice → /salesperson/orders/{id}/invoice
8. Records visit → /salesperson/visits
9. Checks sales → /salesperson/sales
```

### Workflow 3: Shop Owner Purchasing
```
1. Shop Owner logs in → /shop-owner/dashboard
2. Browses products → /shop-owner/products
3. Adds to cart → /shop-owner/cart
4. Proceeds to checkout → /shop-owner/checkout
5. Places order → POST to order creation endpoint
6. Views order status → /shop-owner/orders/{id}
7. Accesses invoice → /shop-owner/invoices/{id}
8. Manages wallet → /shop-owner/wallet
9. Updates profile → /shop-owner/profile/edit
```

### Workflow 4: Order Lifecycle
```
Stage 1: Creation
  - Salesperson creates order for shop (or Shop Owner creates self-order)
  - Order stored with status "pending"

Stage 2: Review
  - Order reviewed for accuracy
  - Salesperson can modify before submission

Stage 3: Processing
  - Admin receives order notification
  - Admin updates status → "processing"

Stage 4: Fulfillment
  - Admin updates status → "shipped" or "ready_for_pickup"

Stage 5: Delivery
  - Admin updates status → "delivered"
  - Shop Owner receives order

Stage 6: Closure
  - Order status → "completed"
  - Invoice generated
  - Payment processed
```

---

## API Endpoints Summary

### Authentication Endpoints

| Method | Endpoint | Controller | View | Authenticated |
|--------|----------|-----------|------|---|
| GET | `/` | LoginController@showLoginForm | auth/login | No |
| GET | `/login` | LoginController@showLoginForm | auth/login | No |
| POST | `/login` | LoginController@login | - | No |
| GET | `/register` | RegisterController@showRegistrationForm | auth/register | No |
| POST | `/register` | RegisterController@register | - | No |
| GET | `/forgot-password` | ForgotPasswordController@showLinkRequestForm | auth/forgot-password | No |
| POST | `/forgot-password` | ForgotPasswordController@sendResetLinkEmail | - | No |
| POST | `/logout` | LoginController@logout | - | Yes |

### Admin Endpoints (Total: 32)

**Dashboard & Users (7)**
- GET `/admin/dashboard`
- GET `/admin/users`
- GET `/admin/users/create`
- GET `/admin/users/{id}/edit`
- GET `/admin/salespersons`
- GET `/admin/salespersons/{id}/details`
- GET `/admin/salespersons/assign`

**Salespersons & Bits (8)**
- GET `/admin/salespersons/top`
- GET `/admin/bits`
- GET `/admin/bits/create`
- GET `/admin/bits/{id}/edit`
- GET `/admin/bits/{id}/performance`
- GET `/admin/bits/assign`
- GET `/admin/assignments`

**Products (5)**
- GET `/admin/products`
- GET `/admin/products/create`
- GET `/admin/products/{id}/edit`
- GET `/admin/products/stock`
- GET `/admin/products/top`

**Orders & Offers (5)**
- GET `/admin/orders`
- GET `/admin/orders/{id}`
- GET `/admin/orders/{id}/details`
- GET `/admin/orders/consolidation`
- GET `/admin/orders/{id}/update-status`

**Offers, Reports & Settings (7)**
- GET `/admin/offers`
- GET `/admin/offers/create`
- GET `/admin/offers/{id}`
- GET `/admin/shops/analysis`
- GET `/admin/shops/top`
- GET `/admin/reports`
- GET `/admin/reports/visit`
- GET `/admin/settings`

### Salesperson Endpoints (Total: 11)

- GET `/salesperson/dashboard`
- GET `/salesperson/shops`
- GET `/salesperson/shops/select`
- GET `/salesperson/shops/{id}`
- GET `/salesperson/products`
- GET `/salesperson/orders/create`
- GET `/salesperson/orders/{id}/review`
- GET `/salesperson/orders/{id}/invoice`
- GET `/salesperson/visits`
- GET `/salesperson/sales`
- GET `/salesperson/profile`

### Shop Owner Endpoints (Total: 15)

- GET `/shop-owner/dashboard`
- GET `/shop-owner/products`
- GET `/shop-owner/cart`
- GET `/shop-owner/checkout`
- GET `/shop-owner/orders`
- GET `/shop-owner/orders/{id}`
- GET `/shop-owner/orders/{id}/details`
- GET `/shop-owner/invoices`
- GET `/shop-owner/invoices/{id}`
- GET `/shop-owner/wallet`
- GET `/shop-owner/profile`
- GET `/shop-owner/profile/edit`
- GET `/shop-owner/referral`

---

## Development Guidelines for Backend Developers

### 1. **Adding New Features**
- Create controllers in appropriate namespace (Admin/, Salesperson/, ShopOwner/)
- Add routes to `/routes/web.php` with proper prefix and middleware
- Create views in corresponding `/resources/views/` directories
- Add necessary migrations in `/database/migrations/`

### 2. **Authentication & Authorization**
- All protected routes require `auth` middleware
- Role-based routes require specific role middleware (`admin`, `salesperson`, `shop-owner`)
- User role is stored in `users.role` field
- Implement authorization checks in controllers

### 3. **Creating Controllers**
```php
// Example structure
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NewController extends Controller
{
    public function __construct()
    {
        // Optional: Add middleware specific to this controller
        $this->middleware('admin');
    }

    public function index()
    {
        // Fetch data from model
        // Return view
    }
}
```

### 4. **Creating Models**
```php
// Place in app/Models/
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewModel extends Model
{
    protected $fillable = ['column1', 'column2'];
}
```

### 5. **Creating Migrations**
```bash
php artisan make:migration create_table_name --create=table_name
```

### 6. **Naming Conventions**
- Controllers: PascalCase with "Controller" suffix
- Models: PascalCase, singular
- Tables: snake_case, plural
- Routes: kebab-case
- Methods: camelCase
- Views: kebab-case with directory structure

### 7. **Error Handling**
- Use Laravel's exception handling
- Return appropriate HTTP status codes
- Log errors to storage/logs/
- Provide user-friendly error messages

### 8. **Testing**
```bash
# Run tests
php artisan test

# Run specific test
php artisan test tests/Feature/ExampleTest.php
```

### 9. **Database Operations**
- Use Eloquent ORM (not raw queries when possible)
- Implement proper relationships between models
- Use migrations for schema changes
- Use seeders for initial data

### 10. **Security Best Practices**
- Always validate input: `$request->validate([])`
- Use Laravel's CSRF protection (automatic in forms)
- Hash passwords: `Hash::make($password)`
- Escape output: `{{ $variable }}` in Blade
- Implement authorization policies for resource access

---

## Configuration Files to Check

| File | Purpose |
|------|---------|
| `config/app.php` | Application settings |
| `config/auth.php` | Authentication configuration |
| `config/database.php` | Database connection settings |
| `config/mail.php` | Email configuration |
| `config/queue.php` | Queue jobs configuration |
| `config/session.php` | Session configuration |
| `composer.json` | PHP dependencies |
| `package.json` | JavaScript dependencies |
| `vite.config.js` | Frontend build configuration |

---

## Testing the Application

### Test Structure
```
tests/
├── Feature/          # Feature/Integration tests
│   └── ExampleTest.php
└── Unit/            # Unit tests
    └── ExampleTest.php
```

### Running Tests
```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run specific file
php artisan test tests/Feature/ExampleTest.php

# Run specific test method
php artisan test --filter=testMethodName
```

---

## Common Tasks

### Add New Admin Feature
1. Create controller: `app/Http/Controllers/Admin/NewFeatureController.php`
2. Add routes in `routes/web.php` under admin group
3. Create views in `resources/views/admin/new-feature/`
4. Create migration if needed: `php artisan make:migration create_table`
5. Create model: `app/Models/Model.php`
6. Add navigation link in admin layout

### Add New Salesperson Feature
1. Create controller: `app/Http/Controllers/Salesperson/NewFeatureController.php`
2. Add routes in `routes/web.php` under salesperson group
3. Create views in `resources/views/salesperson/new-feature/`
4. Follow same database pattern as admin features

### Add New Shop Owner Feature
1. Create controller: `app/Http/Controllers/ShopOwner/NewFeatureController.php`
2. Add routes in `routes/web.php` under shop-owner group
3. Create views in `resources/views/shop-owner/new-feature/`
4. Follow same database pattern

---

## Important Notes

1. **Session Management**: Laravel automatically manages user sessions after authentication
2. **Route Model Binding**: Can use `Route::model()` or implicit binding with type hints
3. **View Variables**: Pass data from controller to view using `view('name', $data)`
4. **CSRF Protection**: Automatic in all POST/PUT/PATCH/DELETE routes
5. **Logging**: Check `storage/logs/laravel.log` for debugging

---

## Contact & Support

For questions about specific features or flows, refer to the appropriate controller files and view templates mentioned in this documentation.

---

**Last Updated**: January 20, 2026  
**Version**: 1.0  
**Framework**: Laravel 11.x
