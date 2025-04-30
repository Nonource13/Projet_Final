<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategorieApiController;
use App\Http\Controllers\Api\OuvrageApiController;
use App\Http\Controllers\Api\StockApiController;
use App\Http\Controllers\Api\VenteApiController;
use App\Http\Controllers\Api\CommentaireApiController;
use App\Http\Controllers\Api\LivreImageApiController;

// 🟢 API REST RESOURCE
Route::apiResource('categories', CategorieApiController::class);
Route::apiResource('ouvrages', OuvrageApiController::class);
Route::apiResource('stocks', StockApiController::class);
Route::apiResource('ventes', VenteApiController::class);
Route::apiResource('commentaires', CommentaireApiController::class);
Route::apiResource('livre-images', LivreImageApiController::class);

// Route de recherche pour les ouvrages (utilisé dans les listes de cadeaux)
Route::get('ouvrages/search', [OuvrageApiController::class, 'search']);
