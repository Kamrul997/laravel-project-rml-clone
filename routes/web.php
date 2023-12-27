<?php

use App\Http\Controllers\Administrative\AreaController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administrative\AuthController;
use App\Http\Controllers\Administrative\HomeController;
use App\Http\Controllers\Administrative\PermissionController;
use App\Http\Controllers\Administrative\RoleController;
use App\Http\Controllers\Administrative\SubUnitController;
use App\Http\Controllers\Administrative\UnitController;
use App\Http\Controllers\Administrative\UserController;
use App\Http\Controllers\Administrative\ZoneController;

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
});
