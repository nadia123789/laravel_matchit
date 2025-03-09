<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\JoueurController;
 
Route::post('/register', [JoueurController::class, 'register']);
Route::post('/login', [UsersController::class, 'login']);
Route::get('/dashboard', [UsersController::class, 'dashboard']);
Route::post('/logout', [UsersController::class, 'logout']);