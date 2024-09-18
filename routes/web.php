<?php

use App\Http\Controllers\categoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\SavingController;
use App\Http\Controllers\UserController;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use App\Http\Middleware\Authentication;
use App\Http\Middleware\ifLogin;


// Show Only If User Not Logged In
// ifLogin Middleware
Route::middleware([ifLogin::class])->group(function () {

    // Register Page
    Route::get('/register', [UserController::class, 'registerPage'])->name('registerPage');

    // Register User
    Route::post('registerUser', [UserController::class, 'register'])->name('UserRegister');

    // Login Page
    Route::get('/login', [UserController::class, 'loginPage'])->name('LoginPage')->middleware([ifLogin::class]);

    // Login
    Route::post('/loginUser', [UserController::class, 'login'])->name('UserLogin');
});



// Show Only If User logged In 
// Authentication Middleware
Route::middleware([Authentication::class])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('home');

    // Categories
    Route::resource('/category', categoryController::class);

    // Transactions
    Route::resource('/transaction', TransactionController::class);

    // Savings
    Route::resource('/savings', SavingController::class);

    // Logout
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
});
