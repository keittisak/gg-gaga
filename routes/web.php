<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // return view('welcome');
    return redirect()->route('orders.index');
});
// Authentication Routes...
Route::get('/login', 'Auth\LoginController@showLoginForm')->name('auth.login.from');
Route::post('/login', 'Auth\LoginController@login')->name('auth.login');
Route::get('/logout', 'Auth\LoginController@logout')->name('auth.logout');

Route::group(['prefix' =>'admin', 'middleware' => ['auth','accessLog']], function () {
    Route::get('/', function () {
        return redirect()->route('auth.login.from');
    });

    //User
    Route::get('/users', 'UserController@index')->name('users.index');
    Route::get('/users/data','UserController@data')->name('users.data');
    Route::get('/users/create','UserController@create')->name('users.create');
    Route::post('/users/create','UserController@store')->name('users.store');
    Route::get('/users/{id}/edit','UserController@edit')->name('users.edit');
    Route::put('/users/{id}/edit','UserController@update')->name('users.update');
    Route::delete('/users/{id}','UserController@destroy')->name('users.delete');

    //Order
    Route::get('/orders', 'OrderController@index')->name('orders.index');
    Route::get('/orders/history', 'OrderController@history')->name('orders.history');
    Route::get('/orders/data', 'OrderController@data')->name('orders.data');
    Route::get('/orders/data/{id}', 'OrderController@getOrderById')->name('orders.by.id');
    Route::get('/orders/overview', 'OrderController@overview')->name('orders.overview');
    Route::get('/orders/create', 'OrderController@create')->name('orders.create');
    Route::post('/orders', 'OrderController@store')->name('orders.store');
    Route::patch('/orders/change-status', 'OrderController@changeStatus')->name('orders.status');
    Route::get('/orders/print/label','OrderController@printLabel')->name('orders.print.label');
    Route::get('/orders/print/label/to-text','OrderController@labelToText')->name('orders.print.label.to_text');
    Route::get('/orders/print/label/small','OrderController@printLabelSmall')->name('orders.print.label.small');
    Route::get('/orders/print/label/large','OrderController@printLabelLarge')->name('orders.print.label.large');
    Route::get('/orders/print/list','OrderController@printList')->name('orders.print.list');
    Route::get('/orders/export/excel','OrderController@exportExcel')->name('orders.export.excel');
    Route::get('/orders/flash/export','OrderController@flashOrderExport')->name('orders.flash-export');
    Route::get('/orders/{id}/edit', 'OrderController@edit')->name('orders.edit');
    Route::put('/orders/{id}/update', 'OrderController@update')->name('orders.update');

    //Product
    Route::get('/products', 'ProductController@index')->name('products.index');
    Route::get('/products/data', 'ProductController@data')->name('products.data');
    Route::get('/products/create', 'ProductController@create')->name('products.create');
    Route::post('/products', 'ProductController@store')->name('products.store');
    Route::get('/products/{id}', 'ProductController@show')->name('products.show');
    Route::get('/products/{id}/edit', 'ProductController@edit')->name('products.edit');
    Route::put('/products/{id}', 'ProductController@update')->name('products.update');
    Route::patch('/products/{id}', 'ProductController@changeStatus')->name('products.status');
    Route::delete('/products/{id}', 'ProductController@destroy')->name('products.delete');
    //Customer
    Route::get('/customers', 'CustomerController@index')->name('customers.index');
    Route::get('/customers/data', 'CustomerController@data')->name('customers.index');
    Route::get('/customers/search-phone', 'CustomerController@searchPhone')->name('customers.search.phone');

    //Stock
    Route::get('/stocks', 'StockController@index')->name('stocks.index');
    Route::get('/stocks/data', 'StockController@data')->name('stocks.data');
    Route::post('/stocks', 'StockController@store')->name('stocks.store');
    Route::put('/stocks/{id}', 'StockController@update')->name('stocks.update');
    Route::delete('/stocks/{id}', 'StockController@destroy')->name('stocks.destroy');

    //Stock Movement
    Route::get('/stock-movement', 'StockMovementController@index')->name('stock.movement.index');
    Route::get('/stock-movement/data', 'StockMovementController@data')->name('stock.movement.data');

    //Dashboard
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard.index');
    Route::post('/dashboard/data/sales-by-product', 'DashboardController@SalesByProduct')->name('dashboard.data.sales-by-product');
    Route::post('/dashboard/data/order-by-status-total', 'DashboardController@orderByStatusTotal')->name('dashboard.data.order-by-status-total');
    Route::post('/dashboard/data/sale-by-channel', 'DashboardController@saleByChannel')->name('dashboard.data.sale-by-channel');

    //Report
    Route::get('/reports/daily-sales', 'ReportController@dailySales')->name('reports.daily-sales');
    Route::get('/reports/daily-sales/data', 'ReportController@dailySalesData')->name('reports.daily-sales.data');
    Route::get('/reports/sales-by-product', 'ReportController@salesByProduct')->name('reports.sales-by-product');
    Route::get('/reports/sales-by-product/data', 'ReportController@salesByProductData')->name('reports.sales-by-product.data');

});

//Customer Portal
Route::get('/customer-portal/{id}', 'CustomerPortalController@index')->name('customerportal.index');

Route::get('/reset', function (){
    Artisan::call('route:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
});

Route::get('/key-generate', function (){
    Artisan::call('key:generate');
});

Route::get('/artisan/storage', function() {
    $command = 'storage:link';
    $result = \Artisan::call($command);
    return \Artisan::output();
});
