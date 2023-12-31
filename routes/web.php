<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administrative\AreaController;
use App\Http\Controllers\Administrative\AuthController;
use App\Http\Controllers\Administrative\HomeController;
use App\Http\Controllers\Administrative\RoleController;
use App\Http\Controllers\Administrative\UnitController;
use App\Http\Controllers\Administrative\UserController;
use App\Http\Controllers\Administrative\ZoneController;
use App\Http\Controllers\Administrative\TargetController;
use App\Http\Controllers\Administrative\ProfileController;
use App\Http\Controllers\Administrative\SubUnitController;
use App\Http\Controllers\Administrative\CustomerController;
use App\Http\Controllers\Administrative\ForecastController;
use App\Http\Controllers\Administrative\StatementController;
use App\Http\Controllers\Administrative\CollectionController;
use App\Http\Controllers\Administrative\PermissionController;
use App\Http\Controllers\Administrative\TargetImportController;
use App\Http\Controllers\Administrative\DisapprovedNoteController;
use App\Http\Controllers\Administrative\FinalCollectionController;
use App\Http\Controllers\Administrative\PreviousNotApprovedController;
use App\Http\Controllers\Administrative\ApproveDateWiseCollectionController;
use App\Http\Controllers\Administrative\DepositDateWiseCollectionController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

Route::namespace('Administrative')->middleware('guest')->group(function () {

    Route::get('/', [AuthController::class, 'index'])->name('login');

    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
});

Route::prefix('administrative')->group(function () {
    Route::resource('collection', CollectionController::class);
    Route::get('collection-download', [CollectionController::class, 'download'])->name('collection.download');

    Route::get('entry-collection/{id}', [CollectionController::class, 'entryCollection'])->name('collect_entry');

    Route::get('import-target', [TargetImportController::class, 'index'])->name('view_target_import');
    Route::post('import-target-store', [TargetImportController::class, 'import'])->name('store_target');


    Route::get('import-customer', [CustomerController::class, 'importIndex'])->name('view_customer_import');
    Route::post('import-customer-store', [CustomerController::class, 'import'])->name('store_customer');

    Route::get('import-bank', [CustomerController::class, 'importBank'])->name('view_bank_import');
    Route::post('import-bank-store', [CustomerController::class, 'importBankSheet'])->name('store_bank');

    Route::get('import-account-statement', [StatementController::class, 'importIndex'])->name('view_account_statement_import');
    Route::post('import-account-statement-store', [StatementController::class, 'importAccountStatement'])->name('store_account_statement');

    Route::get('pending-collection', [CollectionController::class, 'pendingCollectionIndex'])->name('pending_collection.index');
    Route::post('data-pending-collection', [CollectionController::class, 'pendingCollection'])->name('pending_collection.data');
    Route::get('pending-collection-download', [CollectionController::class, 'pendingCollectionDownload'])->name('pending_collection_download');
    Route::get('reject-collection', [CollectionController::class, 'rejectCollection'])->name('reject_collection');
    Route::get('get-branch', [TargetController::class, 'getBranch'])->name('get.branch');


    Route::resource('customer', CustomerController::class);
    Route::get('customer-download', [CustomerController::class, 'download'])->name('c.download');
    Route::get('customer-data/{id}', [CustomerController::class, 'custInfo'])->name('cust_info');
    Route::get('statement', [CustomerController::class, 'statement'])->name('get_statement');


    Route::get('payment-schedule', [CustomerController::class, 'paymentSchedule'])->name('get_payment');
    Route::resource('profile', ProfileController::class);
    Route::get('reset-password', [ProfileController::class, 'resetPassword'])->name('reset_password');
    Route::post('password-update', [ProfileController::class, 'updatePassword'])->name('reset_password_update');


    Route::get('approved-collection/{id}', [CollectionController::class, 'approvedCollection'])->name('approved_collection');
    Route::post('multi-approved-collection', [CollectionController::class, 'multiApprovedCollection'])->name('multi_approved_collection');
    Route::get('disapproved-collection', [CollectionController::class, 'disapprovedCollection'])->name('disapproved_collection');
    Route::get('credit-sales-organogram', [CollectionController::class, 'organogram'])->name('c_s_organogram');


    Route::get('view-statement', [CollectionController::class, 'viewStatement'])->name('view_statement');
    Route::get('view-payment', [CollectionController::class, 'viewPayment'])->name('view_payment');
    Route::get('make-payment-list', [CollectionController::class, 'paymentIndex'])->name('make_payment_index');
    Route::post('make-payment-data', [CollectionController::class, 'paymentData'])->name('make_payment_data');
    Route::post('download-payment', [CollectionController::class, 'paymentDownload'])->name('download.payment');
});

Route::namespace('Administrative')->middleware('auth')->prefix('administrative')->name('administrative.')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    // Role
    Route::prefix('role')->group(function () {

        Route::get('/list', [RoleController::class, 'index'])->name('role');

        Route::get('role-data', [RoleController::class, 'data'])->name('role.data');

        Route::get('create', [RoleController::class, 'create'])->name('role.create');

        Route::get('edit/{id}', [RoleController::class, 'edit'])->name('role.edit');

        Route::put('update/{id}', [RoleController::class, 'update'])->name('role.update');

        Route::post('create', [RoleController::class, 'store'])->name('role.store');

        Route::delete('delete/{id}', [RoleController::class, 'destroy'])->name('role.destroy');
    });

    // Permission
    Route::prefix('permission')->group(function () {

        Route::get('/list', [PermissionController::class, 'index'])->name('permission');

        Route::get('permission-data', [PermissionController::class, 'data'])->name('permission.data');

        Route::get('create', [PermissionController::class, 'create'])->name('permission.create');

        Route::get('edit/{id}', [PermissionController::class, 'edit'])->name('permission.edit');

        Route::put('update/{id}', [PermissionController::class, 'update'])->name('permission.update');

        Route::post('create', [PermissionController::class, 'store'])->name('permission.store');

        Route::delete('delete/{id}', [PermissionController::class, 'destroy'])->name('permission.destroy');
    });

    // User
    Route::prefix('user')->group(function () {

        Route::get('/list', [UserController::class, 'index'])->name('user');

        Route::get('user-data', [UserController::class, 'data'])->name('user.data');

        Route::get('create', [UserController::class, 'create'])->name('user.create');

        Route::get('edit/{id}', [UserController::class, 'edit'])->name('user.edit');

        Route::put('update/{id}', [UserController::class, 'update'])->name('user.update');

        Route::delete('delete/{id}', [UserController::class, 'destroy'])->name('user.destroy');

        Route::post('create', [UserController::class, 'store'])->name('user.store');

        Route::get('get-create-form', [UserController::class, 'getCreateForm'])->name('user.get.create.form');

        Route::get('get-edit-form', [UserController::class, 'getEditForm'])->name('user.get.edit.form');
    });

    // Zone
    Route::prefix('zone')->group(function () {
        Route::get('/list', [ZoneController::class, 'index'])->name('zone');
        Route::get('zone-data', [ZoneController::class, 'data'])->name('zone.data');
        Route::get('edit/{zone}', [ZoneController::class, 'edit'])->name('zone.edit');
        Route::delete('delete/{zone}', [ZoneController::class, 'destroy'])->name('zone.destroy');
        Route::get('create', [ZoneController::class, 'create'])->name('zone.create');
        Route::post('create', [ZoneController::class, 'saveOrUpdate'])->name('zone.store');
    });

    // area
    Route::prefix('area')->group(function () {
        Route::get('/list', [AreaController::class, 'index'])->name('area');
        Route::get('area-data', [AreaController::class, 'data'])->name('area.data');
        Route::get('edit/{area}', [AreaController::class, 'edit'])->name('area.edit');
        Route::delete('delete/{area}', [AreaController::class, 'destroy'])->name('area.destroy');
        Route::get('create', [AreaController::class, 'create'])->name('area.create');
        Route::post('create', [AreaController::class, 'saveOrUpdate'])->name('area.store');
    });

    // Unit
    Route::prefix('unit')->group(function () {
        Route::get('/list', [UnitController::class, 'index'])->name('unit');
        Route::get('unit-data', [UnitController::class, 'data'])->name('unit.data');
        Route::get('edit/{unit}', [UnitController::class, 'edit'])->name('unit.edit');
        Route::delete('delete/{unit}', [UnitController::class, 'destroy'])->name('unit.destroy');
        Route::get('create', [UnitController::class, 'create'])->name('unit.create');
        Route::post('create', [UnitController::class, 'saveOrUpdate'])->name('unit.store');
        Route::get('get-area', [UnitController::class, 'getArea'])->name('unit.get.area');
        Route::get('get-subUnit', [UnitController::class, 'getSubUnit'])->name('unit.get.subUnit');
    });

    // Sub Unit
    Route::prefix('sub-unit')->group(function () {
        Route::get('/list', [SubUnitController::class, 'index'])->name('sub.unit');
        Route::get('sub-unit-data', [SubUnitController::class, 'data'])->name('sub.unit.data');
        Route::get('edit/{subUnit}', [SubUnitController::class, 'edit'])->name('sub.unit.edit');
        Route::delete('delete/{subUnit}', [SubUnitController::class, 'destroy'])->name('sub.unit.destroy');
        Route::get('create', [SubUnitController::class, 'create'])->name('sub.unit.create');
        Route::post('create', [SubUnitController::class, 'saveOrUpdate'])->name('sub.unit.store');
        Route::get('get-unit', [SubUnitController::class, 'getUnit'])->name('sub.unit.get.unit');
    });

     // Target Sheet
     Route::prefix('target')->group(function () {
        Route::get('/list', [TargetController::class, 'index'])->name('target');
        Route::get('target-data', [TargetController::class, 'data'])->name('target.data');
        Route::get('download', [TargetController::class, 'download'])->name('target.download');
        Route::get('/target/show/{id}', [TargetController::class, 'showData'])->name('target_show');
    });

    // Forecast
    Route::prefix('forecast')->group(function () {
        Route::get('/list', [ForecastController::class, 'index'])->name('forecast');
        Route::get('target-data', [ForecastController::class, 'data'])->name('forecast.data');
        Route::post('store-date', [ForecastController::class, 'storeDateWise'])->name('forecast.date');
        Route::get('get-date-data/{id?}', [ForecastController::class, 'getDateData'])->name('forecast.date.data');
        Route::post('store-month', [ForecastController::class, 'storeMonthWise'])->name('forecast.date');
        Route::get('get-month-data/{id?}', [ForecastController::class, 'getMonthData'])->name('forecast.month.data');
        Route::get('get-daily-data', [ForecastController::class, 'index'])->name('get_daily_data');
    });

    Route::prefix('reports')->group(function () {
        Route::get('/final-collection', [FinalCollectionController::class, 'index'])->name('final.collection');
        Route::get('/data/final-collection', [FinalCollectionController::class, 'data'])->name('data.final.collection');
        Route::get('/final-collection-download', [FinalCollectionController::class, 'download'])->name('data.final.collection.download');

        Route::get('/deposit-slip-wise-report', [DepositDateWiseCollectionController::class, 'index2'])->name('deposit.slip.wise.report');
        Route::post('/data/deposit-slip-wise-report', [DepositDateWiseCollectionController::class, 'data2'])->name('data.deposit.slip.wise.report');
        Route::post('/data/deposit-slip-wise-report-download', [DepositDateWiseCollectionController::class, 'downloadData'])->name('data.deposit.slip.wise.report.download');

        Route::get('/deposit-date-wise-collection', [DepositDateWiseCollectionController::class, 'index'])->name('deposit.date.wise.collection');
        Route::post('/data/deposit-date-wise-collection', [DepositDateWiseCollectionController::class, 'data'])->name('data.deposit.date.wise.collection');
        Route::post('/data/deposit-date-wise-collection-download', [DepositDateWiseCollectionController::class, 'download'])->name('data.deposit.date.wise.collection.download');

        Route::get('/approve-date-wise-collection', [ApproveDateWiseCollectionController::class, 'index'])->name('approve.date.wise.collection');
        Route::post('/data/approve-date-wise-collection', [ApproveDateWiseCollectionController::class, 'data'])->name('data.approve.date.wise.collection');
        Route::post('/data/approve-date-wise-collection-download', [ApproveDateWiseCollectionController::class, 'download'])->name('data.approve.date.wise.collection.download');
        Route::get('/previous-not-approve', [PreviousNotApprovedController::class, 'index'])->name('previous.not.approve');
        Route::get('/data/previous-not-approve', [PreviousNotApprovedController::class, 'data'])->name('data.previous.not.approve');
        Route::get('/data/previous-not-approve-download', [PreviousNotApprovedController::class, 'download'])->name('data.previous.not.approve.download');
    });
});
