<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DropdownController;
use App\Http\Controllers\FileTypeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceFileController;
use App\Http\Controllers\ServiceTypeController;
use App\Http\Controllers\ViewController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::controller(ViewController::class)->group(function () {
    Route::get('view/logo/{id?}','logo')->name('view.logo');
});

Route::controller(DropdownController::class)->group(function () {
    Route::get('dropdown/service_type','serviceType')->name('dropdown.service_type');
    Route::get('dropdown/file_type','fileType')->name('dropdown.file_type');
});

Auth::routes(['register' => false]);

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::group(['middleware' => ['role:superadmin']], function () {

    Route::controller(CompanyController::class)->group(function () {
        Route::get('employee','index')->name('employee');
        Route::get('employee/get_all','getAll')->name('employee.get_all');
        Route::post('employee/store', 'store')->name('employee.store');
        Route::put('employee/update/{id?}', 'update')->name('employee.update');
        Route::delete('employee/destroy/{id?}', 'destroy')->name('employee.destroy');
    });
});

Route::group(['middleware' => ['role:employee']], function () {
    
    Route::controller(ServiceController::class)->group(function () {
        Route::get('service','index')->name('service');
        Route::get('service/get_all','getAll')->name('service.get_all');
        Route::post('service/store', 'store')->name('service.store');
        Route::put('service/update/{id?}', 'update')->name('service.update');
        Route::delete('service/destroy/{id?}', 'destroy')->name('service.destroy');
    });
    
    Route::controller(ServiceFileController::class)->group(function () {
        Route::get('service_file/get_all','getAll')->name('service_file.get_all');
        Route::post('service_file/store', 'store')->name('service_file.store');
        Route::put('service_file/update/{id?}', 'update')->name('service_file.update');
        Route::delete('service_file/destroy/{id?}', 'destroy')->name('service_file.destroy');

        Route::post('service_file/upload', 'upload')->name('service_file.upload');
    });

    Route::controller(ReportController::class)->group(function () {
        Route::get('report','index')->name('report');
    });

    Route::controller(ServiceTypeController::class)->group(function () {
        Route::get('service_type','index')->name('service_type');
        Route::get('service_type/get_all','getAll')->name('service_type.get_all');
        Route::post('service_type/store', 'store')->name('service_type.store');
        Route::put('service_type/update/{id?}', 'update')->name('service_type.update');
        Route::delete('service_type/destroy/{id?}', 'destroy')->name('service_type.destroy');
    });
    
    Route::controller(FileTypeController::class)->group(function () {
        Route::get('file_type','index')->name('file_type');
        Route::get('file_type/get_all','getAll')->name('file_type.get_all');
        Route::post('file_type/store', 'store')->name('file_type.store');
        Route::put('file_type/update/{id?}', 'update')->name('file_type.update');
        Route::delete('file_type/destroy/{id?}', 'destroy')->name('file_type.destroy');
    });
});
