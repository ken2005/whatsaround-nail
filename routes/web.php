<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EvenementController;
use App\Http\Controllers\UtilisateurController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);

Route::middleware('auth')->group(function () {
    Route::get('/creer', [EvenementController::class, 'creer']);
    Route::post('/docreer', [EvenementController::class, 'doCreer']);
});

Route::get('/evenement/{id}', [EvenementController::class, 'consulter']);
Route::post('/evenement/{id}/sinscrire', [EvenementController::class, 'sInscrire']);
Route::post('/evenement/{id}/sedesinscrire', [EvenementController::class, 'seDesinscrire']);

Route::get('/connexion', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/inscription', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth')->group(function () {
    Route::get('/evenements/inscriptions', [EvenementController::class, 'inscriptions']);
    Route::get('/evenements/crees', [EvenementController::class, 'crees']);
    Route::get('/evenement/{id}/modifier', [EvenementController::class, 'modifier']);
    Route::post('/evenement/{id}/modifier', [EvenementController::class, 'doModifier']);
    Route::delete('/evenement/{id}', [EvenementController::class, 'supprimer']);
    Route::get('/evenement/{id}/participants', [EvenementController::class, 'participants']);
});
Route::get('/profil/{id}', [UtilisateurController::class, 'profil']);
