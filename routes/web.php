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

use App\Exports\DatabaseExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

Auth::routes();
Route::get('/logout', 'Auth\LoginController@logout');

Route::get('menus/{uuid}/list','HomeController@menu')->name('menus');
Route::middleware('auth', 'active')->group(function () {

    Route::get('/backup', 'HomeController@backup')->name('backup');
    Route::get('/backup/store', 'HomeController@backup')->name('backup.store');
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/home', 'HomeController@index');
    Route::get('kitchen', 'KitchenController@index');

    Route::resource('sells', 'Backend\SellController');
    Route::resource('purchase', 'Backend\PurchaseController');

    Route::resource('expense-category', 'Backend\ExpenseCategoryController');
    Route::resource('expense', 'Backend\ExpenseController');
    Route::get('expense-filter', 'Backend\ExpenseController@filter')->name('expense-filter');

    Route::resource('payment-to-supplier', 'Backend\PaymentToSupplierController');
    Route::resource('payment-from-customer', 'Backend\PaymentFromCustomerController');


    Route::resource('product', 'Backend\ProductController');
    Route::resource('menu', 'Backend\MenuController');
    Route::get('product-filter', 'Backend\ProductController@filter')->name('product-filter');
    Route::get('change-product-status/{id}', 'Backend\ProductController@changeStatus')->name('change-product-status');
    Route::resource('tax', 'Backend\TaxController');

    Route::resource('sell', 'Backend\SellController');

    Route::resource('category', 'Backend\CategoryController');
    Route::resource('menu-category', 'Backend\MenuCategoryController');

    Route::resource('table', 'Backend\TableController');

    Route::resource('unit', 'Backend\UnitController');
    Route::resource('menu-unit', 'Backend\MenuUnitController');

    Route::resource('user', 'Backend\UserController');
    Route::resource('businesses', 'Backend\BusinessController');
    Route::resource('supplier', 'Backend\SupplierController');
    Route::get('change-supplier-status/{id}', 'Backend\SupplierController@changeStatus')->name('change-supplier-status');
    Route::resource('customer', 'Backend\CustomerController');
    Route::resource('department', 'Backend\DepartmentController');
    Route::resource('designation', 'Backend\DesignationController');
    Route::resource('employee', 'Backend\EmployeeController');
    Route::get('change-user-status/{user_id}', 'Backend\EmployeeController@changeStatus');
    Route::resource('role', 'Backend\RoleController');
    Route::get('permission', 'Backend\RoleController@permission')->name('userPermission');
    Route::post('save-permission', 'Backend\RoleController@savePermission')->name('savePermission');

    Route::get('notification/{id}', 'Backend\NotificationController@getNotification')->name('notification');


    Route::prefix('trash')->group(function () {
        Route::get('category', 'Backend\TrashController@categories')->name('category-trash');
        Route::post('category-restore-ok', 'Backend\TrashController@categoryRestore')->name('category-restore-ok');

        Route::get('tax', 'Backend\TrashController@taxes')->name('tax-trash');
        Route::post('tax-restore-ok', 'Backend\TrashController@taxRestore')->name('tax-restore-ok');

        Route::get('expense-category', 'Backend\TrashController@expenseCategories')->name('expense-category-trash');
        Route::post('expense-category-restore-ok', 'Backend\TrashController@expenseCategoryRestore')->name('expense-category-restore-ok');

        Route::get('department-trash', 'Backend\TrashController@department')->name('department-trash');
        Route::post('department-restore-ok', 'Backend\TrashController@departmentRestore')->name('department-restore-ok');

        Route::get('designation-trash', 'Backend\TrashController@designation')->name('designation-trash');
        Route::post('designation-restore-ok', 'Backend\TrashController@designationRestore')->name('designation-restore-ok');

        Route::post('branch-restore-ok', 'Backend\TrashController@branchRestore')->name('branch-restore-ok');
    });

    Route::prefix('report')->group(function () {
        Route::prefix('sell')->group(function () {
            Route::get('summary', 'Backend\SellReportController@sellsSummary');
            Route::get('product-wise', 'Backend\SellReportController@productWise');
            Route::get('sells', 'Backend\SellReportController@sells');
            Route::get('statistics', 'Backend\SellReportController@sellsStatistics');
            Route::get('statistics-filter', 'Backend\SellReportController@sellsStatisticsFilter');
            Route::get('statistics-pdf', 'Backend\SellReportController@sellsStatisticsFilterPDF');
        });

        Route::prefix('purchase')->group(function () {
            Route::get('summary', 'Backend\PurchaseReportController@summary');
            Route::get('product-wise', 'Backend\PurchaseReportController@productWise');
            Route::get('purchases', 'Backend\PurchaseReportController@purchases');
            Route::get('statistics', 'Backend\PurchaseReportController@statistics');
            Route::get('statistics-filter', 'Backend\PurchaseReportController@statisticsFilter');
            Route::get('statistics-pdf', 'Backend\PurchaseReportController@statisticsFilterPDF');
        });

        Route::get('stock-report', 'Backend\StockReportController@index');
        Route::get('stock-report-pdf', 'Backend\StockReportController@stockReportPdf');
        Route::get('payment/supplier', 'Backend\PaymentToSupplierController@pdf');
        Route::get('payment/customer', 'Backend\PaymentFromCustomerController@pdf');


        Route::get('profit-loss', 'Backend\ProfitLoosReportController@index')->name('profitLoss');
    });

    Route::prefix('export')->group(function () {
        Route::get('sell/invoice/id={sell_id}/type={action_type}', 'Backend\SellController@pdf');
        Route::get('sell/print-invoice/id={sell_id}', 'Backend\SellController@printInvoice');
        Route::get('purchase/print-invoice/id={purchase_id}/type={action_type}', 'Backend\PurchaseController@pdf');
        Route::get('stock-report/filter', 'Backend\StockReportController@filter');
        Route::get('stock-report-pdf', 'Backend\StockReportController@stockReportPdf');
    });

    Route::prefix('settings')->group(function () {
        Route::get('profile', 'Backend\SettingsController@profile')->name('profile');
        Route::post('update-profile', 'Backend\SettingsController@updateProfile')->name('update-profile');
        Route::get('password', 'Backend\SettingsController@password')->name('password');
        Route::post('update-password}', 'Backend\SettingsController@updatePassword')->name('update-password');

        Route::get('change-email', 'Backend\SettingsController@changeEmail')->name('change-email');
        Route::post('update-user-email', 'Backend\SettingsController@updateEmail')->name('update-user-email');

        Route::get('general', 'Backend\SettingsController@generalSetting')->name('general-settings');
        Route::post('save-application-setting', 'Backend\SettingsController@saveApplicationSetting')->name('save-application-setting');

        Route::get('prefix', 'Backend\SettingsController@prefixSetting')->name('prefix-settings');
        Route::post('update-prefix', 'Backend\SettingsController@generalSetting')->name('update-prefix');

        Route::get('email', 'Backend\SettingsController@emailSetting')->name('email-settings');
        Route::post('update-email', 'Backend\SettingsController@generalSetting')->name('update-email-settings');

        Route::get('currency', 'Backend\SettingsController@currencySettings')->name('currency-settings');
        Route::post('update-currency', 'Backend\SettingsController@currencySettings')->name('update-currency');

        Route::resource('app-currency', 'Backend\CurrencyController');
    });

    Route::prefix('vue/api')->group(function (){
        Route::get('order-type','Backend\VeuApiController@orderTypes');
        Route::get('transactions','Backend\VeuApiController@transactions');
        Route::get('get-local-lang','Backend\VeuApiController@getAppLang');
        Route::get('get-app-configs','Backend\VeuApiController@getAppConfigs');
        Route::get('tables','Backend\VeuApiController@tables');
        Route::get('products','Backend\VeuApiController@products');
        Route::get('products-with-paginate','Backend\VeuApiController@productsWithPaginate');
        Route::get('product-available-stock-qty/{product_id}','Backend\VeuApiController@productAvailableStockQty');
        Route::get('product-available-stock-qty-without-invoice/{product_id}/{sell_id}','Backend\VeuApiController@productAvailableStockQtyWithoutSellInvoice');
        Route::get('suppliers','Backend\VeuApiController@suppliers');
        Route::get('get-supplier-due/{id}','Backend\VeuApiController@supplierDue');
        Route::get('categories','Backend\VeuApiController@categories');
        Route::get('brands','Backend\VeuApiController@brands');
        Route::get('customers','Backend\VeuApiController@customers');
        Route::post('store-customer','Backend\VeuApiController@storeCustomer');
        Route::post('store-sell','Backend\SellController@store');

        Route::get('purchase-details/{purchase_id}','Backend\PurchaseController@getPurchaseDetails');
        Route::get('sell-details/{sell_id}','Backend\SellController@getSellDetails');
        /** ======= get departments ========== */
        Route::get('departments','Backend\DepartmentController@departments');
        /** ====== get designations ========= */
        Route::get('designations', 'Backend\DesignationController@designations');
        Route::get('taxes','Backend\TaxController@taxes');
    });

    Route::prefix('chart/api')->group(function (){
        Route::get('get-dashboard-data','Backend\ChartApiController@dashboard');
        Route::get('sale-report-statistic-data','Backend\ChartApiController@saleReportStatisticData');
        Route::get('sale-report-statistic-by-day/{days}','Backend\ChartApiController@saleReportStatisticByDay');
        Route::get('sale-report-statistics-filter/{selected_month}/{selected_year}/{selected_branch}/{search_type}','Backend\ChartApiController@saleReportStatisticsFilter');
        Route::get('purchase-report-statistic-data', 'Backend\ChartApiController@purchaseReportStatisticData');
        Route::get('purchase-report-statistic-by-day/{days}','Backend\ChartApiController@purchaseReportStatisticByDay');
        Route::get('purchase-report-statistics-filter/{selected_month}/{selected_year}/{selected_branch}/{search_type}','Backend\ChartApiController@purchaseReportStatisticsFilter');
        Route::get('get-dashboard-sell-purchase-data','HomeController@dashboardSellPurchaseData');

        Route::get('get-product-data/{id}','Backend\ChartApiController@product');
    });

    Route::prefix('dashboard/api')->group(function (){
        Route::get('get-dashboard-sell-purchase-data','HomeController@dashboardSellPurchaseData');
        Route::get('get-dashboard-last-10-days-profit-loss','HomeController@last10DaysProfitLoss');
    });
});

Route::get('verify-license', 'Backend\SettingsController@verifyLicense')->name('verifyLicense');
Route::post('activate-license-before-create-user', 'Backend\SettingsController@activateLicense')->name('check.purchase.key');
Route::post('activate-license', 'Backend\SettingsController@activateLicense')->name('activateLicense');

