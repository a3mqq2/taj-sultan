<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\SpecialOrderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PosPointController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SettingController;

Route::redirect('/', 'login');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/chart', [DashboardController::class, 'chartData'])->name('dashboard.chart');
    Route::get('/dashboard/recent-orders', [DashboardController::class, 'recentOrders'])->name('dashboard.recent-orders');
    Route::get('/dashboard/upcoming-deliveries', [DashboardController::class, 'upcomingDeliveries'])->name('dashboard.upcoming-deliveries');

    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/data', [ProductController::class, 'data'])->name('data');
        Route::get('/export', [ProductController::class, 'exportExcel'])->name('export');
        Route::post('/', [ProductController::class, 'store'])->middleware('permission:product.create')->name('store');
        Route::get('/{product}', [ProductController::class, 'show'])->name('show');
        Route::put('/{product}', [ProductController::class, 'update'])->middleware('permission:product.edit')->name('update');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->middleware('permission:product.delete')->name('destroy');
        Route::patch('/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->middleware('permission:product.edit')->name('toggle-status');
        Route::post('/bulk-delete', [ProductController::class, 'bulkDelete'])->middleware('permission:product.delete')->name('bulk-delete');
        Route::post('/bulk-toggle', [ProductController::class, 'bulkToggle'])->middleware('permission:product.edit')->name('bulk-toggle');
        Route::post('/{product}/add-stock', [ProductController::class, 'addStock'])->middleware('permission:product.edit')->name('add-stock');
        Route::post('/{product}/deduct-stock', [ProductController::class, 'deductStock'])->middleware('permission:product.edit')->name('deduct-stock');
        Route::get('/{product}/stock-movements', [ProductController::class, 'stockMovements'])->name('stock-movements');
        Route::delete('/{product}/stock-movements/{movement}', [ProductController::class, 'deleteStockMovement'])->middleware('permission:product.edit')->name('delete-stock-movement');
    });

    Route::prefix('payment-methods')->name('payment-methods.')->group(function () {
        Route::get('/', [PaymentMethodController::class, 'index'])->name('index');
        Route::get('/data', [PaymentMethodController::class, 'data'])->name('data');
        Route::post('/', [PaymentMethodController::class, 'store'])->middleware('permission:payment_method.create')->name('store');
        Route::get('/{paymentMethod}', [PaymentMethodController::class, 'show'])->name('show');
        Route::put('/{paymentMethod}', [PaymentMethodController::class, 'update'])->middleware('permission:payment_method.edit')->name('update');
        Route::delete('/{paymentMethod}', [PaymentMethodController::class, 'destroy'])->middleware('permission:payment_method.delete')->name('destroy');
        Route::patch('/{paymentMethod}/toggle-status', [PaymentMethodController::class, 'toggleStatus'])->middleware('permission:payment_method.edit')->name('toggle-status');
    });

    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::get('/data', [CustomerController::class, 'data'])->name('data');
        Route::get('/search', [CustomerController::class, 'search'])->name('search');
        Route::post('/', [CustomerController::class, 'store'])->middleware('permission:customer.create')->name('store');
        Route::get('/{customer}', [CustomerController::class, 'show'])->name('show');
        Route::put('/{customer}', [CustomerController::class, 'update'])->middleware('permission:customer.edit')->name('update');
        Route::delete('/{customer}', [CustomerController::class, 'destroy'])->middleware('permission:customer.delete')->name('destroy');
        Route::patch('/{customer}/toggle-status', [CustomerController::class, 'toggleStatus'])->middleware('permission:customer.edit')->name('toggle-status');
        Route::get('/{customer}/statement', [CustomerController::class, 'statement'])->middleware('permission:customer.view_statement')->name('statement');
        Route::get('/{customer}/statement/print', [CustomerController::class, 'printStatement'])->middleware('permission:customer.view_statement')->name('print-statement');
        Route::post('/{customer}/payment', [CustomerController::class, 'addPayment'])->middleware('permission:customer.add_payment')->name('payment');
    });

    Route::prefix('special-orders')->name('special-orders.')->group(function () {
        Route::get('/', [SpecialOrderController::class, 'index'])->name('index');
        Route::get('/data', [SpecialOrderController::class, 'data'])->name('data');
        Route::post('/', [SpecialOrderController::class, 'store'])->middleware('permission:special_order.create')->name('store');
        Route::post('/event-types', [SpecialOrderController::class, 'storeEventType'])->middleware('permission:special_order.create')->name('store-event-type');
        Route::get('/{specialOrder}', [SpecialOrderController::class, 'show'])->name('show');
        Route::put('/{specialOrder}', [SpecialOrderController::class, 'update'])->middleware('permission:special_order.edit')->name('update');
        Route::delete('/{specialOrder}', [SpecialOrderController::class, 'destroy'])->middleware('permission:special_order.delete')->name('destroy');
        Route::patch('/{specialOrder}/status', [SpecialOrderController::class, 'updateStatus'])->middleware('permission:special_order.change_status')->name('update-status');
        Route::get('/{specialOrder}/payments', [SpecialOrderController::class, 'payments'])->name('payments');
        Route::post('/{specialOrder}/payments', [SpecialOrderController::class, 'addPayment'])->middleware('permission:special_order.add_payment')->name('add-payment');
        Route::get('/{specialOrder}/print', [SpecialOrderController::class, 'printReceipt'])->name('print');
        Route::get('/payments/{payment}/print', [SpecialOrderController::class, 'printPaymentReceipt'])->name('print-payment');
    });

    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/data', [OrderController::class, 'data'])->name('data');
        Route::get('/export', [OrderController::class, 'export'])->name('export');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
    });

    Route::prefix('users')->name('users.')->middleware('permission:user.view')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/data', [UserController::class, 'data'])->name('data');
        Route::post('/', [UserController::class, 'store'])->middleware('permission:user.create')->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::put('/{user}', [UserController::class, 'update'])->middleware('permission:user.edit')->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->middleware('permission:user.delete')->name('destroy');
        Route::patch('/{user}/toggle-status', [UserController::class, 'toggleStatus'])->middleware('permission:user.edit')->name('toggle-status');
    });

    Route::prefix('pos-points')->name('pos-points.')->middleware('permission:pos_point.view')->group(function () {
        Route::get('/', [PosPointController::class, 'index'])->name('index');
        Route::get('/data', [PosPointController::class, 'data'])->name('data');
        Route::post('/', [PosPointController::class, 'store'])->middleware('permission:pos_point.edit')->name('store');
        Route::get('/{posPoint}', [PosPointController::class, 'show'])->name('show');
        Route::put('/{posPoint}', [PosPointController::class, 'update'])->middleware('permission:pos_point.edit')->name('update');
        Route::delete('/{posPoint}', [PosPointController::class, 'destroy'])->middleware('permission:pos_point.edit')->name('destroy');
    });

    Route::prefix('reports')->name('reports.')->middleware('permission:reports.view')->group(function () {
        Route::get('/sales', [SalesReportController::class, 'index'])->name('sales.index');
        Route::get('/sales/data', [SalesReportController::class, 'data'])->name('sales.data');
        Route::get('/sales/summary', [SalesReportController::class, 'summary'])->name('sales.summary');
        Route::get('/sales/chart', [SalesReportController::class, 'chartData'])->name('sales.chart');
        Route::get('/sales/products', [SalesReportController::class, 'productsData'])->name('sales.products');
        Route::get('/sales/export/excel', [SalesReportController::class, 'exportExcel'])->name('sales.export.excel');
        Route::get('/sales/export/products', [SalesReportController::class, 'exportProductsExcel'])->name('sales.export.products');
        Route::get('/sales/print', [SalesReportController::class, 'print'])->name('sales.print');
        Route::get('/sales/special-orders/data', [SalesReportController::class, 'specialOrdersData'])->name('sales.special-orders.data');
        Route::get('/sales/special-orders/summary', [SalesReportController::class, 'specialOrdersSummary'])->name('sales.special-orders.summary');
        Route::get('/sales/special-orders/export', [SalesReportController::class, 'exportSpecialOrdersExcel'])->name('sales.special-orders.export');
    });

    Route::post('/backup', [BackupController::class, 'create'])->name('admin.backup');

    Route::get('/settings/general', [SettingController::class, 'index'])->name('settings.general');
    Route::post('/settings/general', [SettingController::class, 'update'])->name('settings.general.update');
});

Route::prefix('cashier')->name('cashier.')->middleware(['auth', 'cashier'])->group(function () {
    Route::get('/', [CashierController::class, 'index'])->name('index');
    Route::post('/fetch-order', [CashierController::class, 'fetchOrder'])->name('fetch-order');
    Route::post('/new-invoice', [CashierController::class, 'newInvoice'])->name('new-invoice');
    Route::post('/find-by-barcode', [CashierController::class, 'findByBarcode'])->name('find-by-barcode');
    Route::post('/add-item-to-order', [CashierController::class, 'addItemToOrder'])->name('add-item-to-order');
    Route::post('/add-weight-barcode', [CashierController::class, 'addWeightBarcode'])->name('add-weight-barcode');
    Route::post('/add-weight-manual', [CashierController::class, 'addWeightManual'])->name('add-weight-manual');
    Route::post('/add-weight-item', [CashierController::class, 'addWeightItem'])->name('add-weight-item');
    Route::post('/pay', [CashierController::class, 'pay'])->name('pay');
    Route::post('/remove-item', [CashierController::class, 'removeItem'])->name('remove-item');
    Route::get('/weight-products', [CashierController::class, 'weightProducts'])->name('weight-products');

    Route::get('/search-customers', [CashierController::class, 'searchCustomers'])->name('search-customers');
    Route::post('/quick-customer', [CashierController::class, 'createQuickCustomer'])->name('quick-customer');

    Route::get('/customers', [CashierController::class, 'customersPage'])->name('customers');
    Route::get('/customers/data', [CashierController::class, 'customersData'])->name('customers.data');
    Route::get('/customers/{customer}', [CashierController::class, 'customerDetails'])->name('customers.details');
    Route::post('/customers/{customer}/pay-debt', [CashierController::class, 'payDebt'])->name('customers.pay-debt');

    Route::get('/special-orders', [CashierController::class, 'specialOrders'])->name('special-orders');
    Route::get('/special-orders/products', [CashierController::class, 'specialOrderProducts'])->name('special-orders.products');
    Route::get('/special-orders/customers', [CashierController::class, 'specialOrderCustomers'])->name('special-orders.customers');
    Route::post('/special-orders/store', [CashierController::class, 'storeSpecialOrder'])->name('special-orders.store');
    Route::post('/special-orders/fetch', [CashierController::class, 'fetchSpecialOrder'])->name('special-orders.fetch');
    Route::post('/special-orders/payment', [CashierController::class, 'addSpecialOrderPayment'])->name('special-orders.payment');
    Route::get('/special-orders/{id}/print', [CashierController::class, 'printSpecialOrder'])->name('special-orders.print');
    Route::post('/special-orders/{id}/cancel', [CashierController::class, 'cancelSpecialOrder'])->name('special-orders.cancel');

    Route::post('/fetch-order-for-merge', [CashierController::class, 'fetchOrderForMerge'])->name('fetch-order-for-merge');
    Route::post('/merge-orders', [CashierController::class, 'mergeOrders'])->name('merge-orders');
    Route::post('/find-invoice', [CashierController::class, 'findInvoice'])->name('find-invoice');
    Route::post('/delete-invoice/{id}', [CashierController::class, 'deleteInvoice'])->name('delete-invoice');
    Route::post('/verify-cancel-code', [SettingController::class, 'verifyCancelCode'])->name('verify-cancel-code');

    Route::get('/deliveries', [CashierController::class, 'deliveries'])->name('deliveries');
    Route::get('/deliveries/data', [CashierController::class, 'deliveriesData'])->name('deliveries.data');
    Route::post('/deliveries/mark-delivered', [CashierController::class, 'markDelivered'])->name('deliveries.mark-delivered');
});

Route::prefix('pos')->name('pos.')->group(function () {
    Route::get('/{slug}', [PosController::class, 'show'])->name('show');
    Route::get('/{slug}/login', [PosController::class, 'loginForm'])->name('login');
    Route::post('/{slug}/login', [PosController::class, 'login']);
    Route::post('/{slug}/logout', [PosController::class, 'logout'])->name('logout');
    Route::get('/{slug}/products', [PosController::class, 'products'])->name('products');
    Route::get('/{slug}/categories', [PosController::class, 'categories'])->name('categories');
    Route::post('/{slug}/orders', [PosController::class, 'createOrder'])->name('create-order');
    Route::get('/{slug}/sticker/{barcode}', [PosController::class, 'sticker'])->name('sticker');
});
