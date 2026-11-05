<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ReceitaController;
use Illuminate\Support\Facades\Route;

Route::post('/usuarios', [AuthController::class, 'cadastrar']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('categorias', CategoriaController::class)->only(['index', 'show']);
    Route::apiResource('receitas', ReceitaController::class);
});
