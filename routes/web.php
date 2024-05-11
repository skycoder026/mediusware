<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\WithdrawalController;

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


Route::group(['middleware' => 'auth'], function() {
    Route::get('/',                             [DashboardController::class,        'index'])->name('dashboard');



    // USER CREATE ROUTE
    Route::post('users',                        [LoginController::class,             'store'])->name('users.store');

    
    // BALANCE DEPOSIT ROUTES
    Route::get('deposit/create',                [DepositController::class,          'create'])->name('deposit.create');
    Route::post('deposit/store',                [DepositController::class,          'store'])->name('deposit.store');
    Route::get('deposit',                       [DepositController::class,          'index'])->name('deposit.index');
    

    // BALANCE WITHDRAWAL ROUTES
    Route::get('withdrawal/create',                [WithdrawalController::class,    'create'])->name('withdrawal.create');
    Route::post('withdrawal/store',                [WithdrawalController::class,    'store'])->name('withdrawal.store');
    Route::get('withdrawal',                       [WithdrawalController::class,    'index'])->name('withdrawal.index');
});
