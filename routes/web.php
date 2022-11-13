<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LetterTypeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceTypeController;
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

Auth::routes(['register' => false]);

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::controller(ServiceController::class)->group(function () {
    Route::get('service','index')->name('service');
    Route::get('service/get_all','getAll')->name('service.getAll');
    Route::post('service/store', 'store')->name('service.store');
    Route::put('service/update/{id?}', 'update')->name('service.update');
    Route::delete('service/destroy/{id?}', 'destroy')->name('service.destroy');
});

Route::controller(ReportController::class)->group(function () {
    Route::get('report','index')->name('report');
});

Route::controller(CompanyController::class)->group(function () {
    Route::get('company','index')->name('company');
    Route::get('company/get_all','getAll')->name('company.get_all');
    Route::post('company/store', 'store')->name('company.store');
    Route::put('company/update/{id?}', 'update')->name('company.update');
    Route::delete('company/destroy/{id?}', 'destroy')->name('company.destroy');
});

Route::controller(ServiceTypeController::class)->group(function () {
    Route::get('service_type','index')->name('service_type');
    Route::get('service_type/get_all','getAll')->name('service_type.get_all');
    Route::post('service_type/store', 'store')->name('service_type.store');
    Route::put('service_type/update/{id?}', 'update')->name('service_type.update');
    Route::delete('service_type/destroy/{id?}', 'destroy')->name('service_type.destroy');
});

Route::controller(LetterTypeController::class)->group(function () {
    Route::get('letter_type','index')->name('letter_type');
    Route::get('letter_type/get_all','getAll')->name('letter_type.get_all');
    Route::post('letter_type/store', 'store')->name('letter_type.store');
    Route::put('letter_type/update/{id?}', 'update')->name('letter_type.update');
    Route::delete('letter_type/destroy/{id?}', 'destroy')->name('letter_type.destroy');
});
