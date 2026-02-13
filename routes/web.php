<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EvenementController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);

Route::middleware('auth')->group(function () {
    Route::get('/creer', [EvenementController::class, 'creer']);
    Route::post('/docreer', [EvenementController::class, 'doCreer']);
});

Route::get('/connexion', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/inscription', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);
