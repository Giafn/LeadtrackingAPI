<?php

use App\Http\Controllers\Api\LeadController;
use App\Http\Controllers\Api\LoginController;
use App\Models\Lead;
use Illuminate\Support\Facades\Route;

Route::post('/login', LoginController::class)->name('login');

// route group for authenticated users
Route::middleware('auth:api')->group(function () {
    Route::get('/leads', [LeadController::class, 'index']);
    Route::post('/leads', [LeadController::class, 'store']);
    Route::put('/leads/{leadId}/status', [LeadController::class, 'updateStatus']);
});