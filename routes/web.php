<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ADMIN CONTROLLERS
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController as AdminUser;
use App\Http\Controllers\Admin\AreaController as AdminArea;
use App\Http\Controllers\Admin\ProductController as AdminProduct;
use App\Http\Controllers\Admin\OrderController as AdminOrder;
use App\Http\Controllers\Admin\OfferController as AdminOffer;
use App\Http\Controllers\Admin\ReportController as AdminReport;
use App\Http\Controllers\Admin\SettingsController as AdminSettings;

/*
|--------------------------------------------------------------------------
| SALESPERSON CONTROLLERS
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Salesperson\DashboardController as SalespersonDashboard;
use App\Http\Controllers\Salesperson\ShopController as SalespersonShop;
use App\Http\Controllers\Salesperson\OrderController as SalespersonOrder;
use App\Http\Controllers\Salesperson\ProductController as SalespersonProduct;
use App\Http\Controllers\Salesperson\VisitController as SalespersonVisit;
use App\Http\Controllers\Salesperson\ProfileController as SalespersonProfile;

/*
|--------------------------------------------------------------------------
| SHOP OWNER CONTROLLERS
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\ShopOwner\DashboardController as ShopOwnerDashboard;
use App\Http\Controllers\ShopOwner\OrderController as ShopOwnerOrder;
use App\Http\Controllers\ShopOwner\ProductController as ShopOwnerProduct;
use App\Http\Controllers\ShopOwner\ProfileController as ShopOwnerProfile;
use App\Http\Controllers\ShopOwner\WalletController as ShopOwnerWallet;
use App\Http\Controllers\ShopOwner\CartController as ShopOwnerCart;
use App\Http\Controllers\ShopOwner\CheckoutController as ShopOwnerCheckout;

/*
|--------------------------------------------------------------------------
| AUTH CONTROLLERS
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;

/*
|--------------------------------------------------------------------------
| AUTHENTICATION ROUTES
|--------------------------------------------------------------------------
*/
// Show login form (GET)
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// Handle login submission (POST)
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// Show registration form (GET)
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');

// Handle registration submission (POST)
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

// Show forgot password form (GET)
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');

// Handle forgot password submission (POST)
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Add this after your authentication routes
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    
    // Users
    Route::get('/users', [AdminUser::class, 'index'])->name('users.index');
    Route::get('/users/create', [AdminUser::class, 'create'])->name('users.create');
    Route::post('/users', [AdminUser::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [AdminUser::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [AdminUser::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [AdminUser::class, 'destroy'])->name('users.destroy');
    
    // Salespersons
    Route::get('/salespersons', [AdminUser::class, 'salespersons'])->name('salespersons.index');
    Route::get('/salespersons/{id}/details', [AdminUser::class, 'salespersonDetails'])->name('salespersons.details');
    Route::get('/salespersons/assign', [AdminUser::class, 'assignSalespersonForm'])->name('salespersons.assign.form');
    Route::post('/salespersons/assign', [AdminUser::class, 'storeAssignment'])->name('salespersons.assign.store');
    Route::get('/salespersons/shops-by-area/{area_id}', [AdminUser::class, 'getShopsByArea'])->name('salespersons.shops-by-area');
    Route::get('/salespersons/top', [AdminUser::class, 'topSalespersons'])->name('salespersons.top');
    
    // Areas
    Route::get('/areas', [AdminArea::class, 'index'])->name('areas.index');
    Route::get('/areas/create', [AdminArea::class, 'create'])->name('areas.create');
    Route::post('/areas', [AdminArea::class, 'store'])->name('areas.store');
    Route::get('/areas/{id}/edit', [AdminArea::class, 'edit'])->name('areas.edit');
    Route::put('/areas/{id}', [AdminArea::class, 'update'])->name('areas.update');
    Route::delete('/areas/{id}', [AdminArea::class, 'destroy'])->name('areas.destroy');
    Route::get('/areas/{id}/performance', [AdminArea::class, 'performance'])->name('areas.performance');
    Route::get('/areas/assign', [AdminArea::class, 'assignForm'])->name('areas.assign.form');
    Route::get('/assignments', [AdminArea::class, 'viewAssignments'])->name('assignments.view');
    
    // Products
    Route::get('/products', [AdminProduct::class, 'index'])->name('products.index');
    Route::get('/products/create', [AdminProduct::class, 'create'])->name('products.create');
    Route::post('/products', [AdminProduct::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [AdminProduct::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [AdminProduct::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [AdminProduct::class, 'destroy'])->name('products.destroy');
    Route::get('/products/stock', [AdminProduct::class, 'stock'])->name('products.stock');
    Route::post('/products/bulk-destroy', [AdminProduct::class, 'bulkDestroy'])->name('products.bulk-destroy');
    Route::get('/products/top', [AdminProduct::class, 'top'])->name('products.top');
    // Orders
    Route::get('/orders/consolidation', [AdminOrder::class, 'consolidation'])->name('orders.consolidation');
    Route::get('/orders', [AdminOrder::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [AdminOrder::class, 'show'])->name('orders.show');
    Route::get('/orders/{id}/details', [AdminOrder::class, 'details'])->name('orders.details');
    Route::get('/orders/{id}/update-status', [AdminOrder::class, 'updateStatusForm'])->name('orders.update-status.form');
    
    // Offers
    Route::get('/offers', [AdminOffer::class, 'index'])->name('offers.index');
    Route::get('/offers/create', [AdminOffer::class, 'create'])->name('offers.create');
    Route::post('/offers', [AdminOffer::class, 'store'])->name('offers.store');
    Route::get('/offers/{id}/edit', [AdminOffer::class, 'edit'])->name('offers.edit');
    Route::put('/offers/{id}', [AdminOffer::class, 'update'])->name('offers.update');
    Route::delete('/offers/{id}', [AdminOffer::class, 'destroy'])->name('offers.destroy');
    Route::get('/offers/{id}', [AdminOffer::class, 'show'])->name('offers.show');
    
    // Shops
    Route::get('/shops/analysis', [AdminReport::class, 'shopAnalysis'])->name('shops.analysis');
    Route::get('/shops/top', [AdminReport::class, 'topShops'])->name('shops.top');
    
    // Reports
    Route::get('/reports', [AdminReport::class, 'index'])->name('reports.index');
    Route::get('/reports/visit', [AdminReport::class, 'visitReports'])->name('reports.visit');
    
    // Settings
    Route::get('/settings', [AdminSettings::class, 'index'])->name('settings.index');
});

/*
|--------------------------------------------------------------------------
| SALESPERSON ROUTESw
|--------------------------------------------------------------------------
*/
Route::prefix('salesperson')->name('salesperson.')->middleware(['auth', 'salesperson'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [SalespersonDashboard::class, 'index'])->name('dashboard');
    
    // Shops
    Route::get('/shops', [SalespersonShop::class, 'index'])->name('shops.index');
    Route::get('/shops/select', [SalespersonShop::class, 'select'])->name('shops.select');
    Route::get('/shops/{id}', [SalespersonShop::class, 'show'])->name('shops.show');
    
    // Products
    Route::get('/products', [SalespersonProduct::class, 'index'])->name('products.index');
    
    // Orders
    Route::get('/orders/create', [SalespersonOrder::class, 'create'])->name('orders.create');
    Route::post('/orders', [SalespersonOrder::class, 'store'])->name('orders.store');
    Route::get('/orders/{id}/review', [SalespersonOrder::class, 'review'])->name('orders.review');
    Route::get('/orders/{id}/invoice', [SalespersonOrder::class, 'invoice'])->name('orders.invoice');
    
    // Visits
    Route::get('/visits', [SalespersonVisit::class, 'index'])->name('visits.index');
    Route::get('/visits/create', [SalespersonVisit::class, 'create'])->name('visits.create');
    Route::post('/visits', [SalespersonVisit::class, 'store'])->name('visits.store');
    
    // Sales
    Route::get('/sales', [SalespersonDashboard::class, 'sales'])->name('sales.index');
    
    // Profile
    Route::get('/profile', [SalespersonProfile::class, 'index'])->name('profile.index');
});

/*
|--------------------------------------------------------------------------
| SHOP OWNER ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('shop-owner')->name('shop-owner.')->middleware(['auth', 'shop-owner'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [ShopOwnerDashboard::class, 'index'])->name('dashboard');
    
    // Products
    Route::get('/products', [ShopOwnerProduct::class, 'index'])->name('products.index');
    
    // Orders
    Route::get('/orders', [ShopOwnerOrder::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [ShopOwnerOrder::class, 'show'])->name('orders.show');
    Route::get('/orders/{id}/details', [ShopOwnerOrder::class, 'details'])->name('orders.details');
    
    // Cart
    Route::get('/cart', [ShopOwnerCart::class, 'index'])->name('cart.index');
    
    // Checkout
    Route::get('/checkout', [ShopOwnerCheckout::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [ShopOwnerCheckout::class, 'store'])->name('checkout.store');
    
    // Invoice
    Route::get('/invoices', [ShopOwnerOrder::class, 'invoices'])->name('invoices.index');
    Route::get('/invoices/{id}', [ShopOwnerOrder::class, 'invoice'])->name('invoices.show');
    
    // Profile
    Route::get('/profile', [ShopOwnerProfile::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ShopOwnerProfile::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ShopOwnerProfile::class, 'update'])->name('profile.update');
    
    // Wallet
    Route::get('/wallet', [ShopOwnerWallet::class, 'index'])->name('wallet.index');
    
    // Referral
    Route::get('/referral', [ShopOwnerProfile::class, 'referral'])->name('referral.index');
});
