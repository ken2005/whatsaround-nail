<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EvenementController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/evenement/{id}', [EvenementController::class, 'consulter']);
Route::post('/evenement/{id}/sinscrire', [EvenementController::class, 'sInscrire']);
Route::post('/evenement/{id}/sedesinscrire', [EvenementController::class, 'seDesinscrire']);

Route::get('/connexion', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/inscription', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);
