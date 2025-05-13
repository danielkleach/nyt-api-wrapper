<?php

use App\Http\Controllers\BestSellerController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/best-sellers', [BestSellerController::class, 'index']);
});
