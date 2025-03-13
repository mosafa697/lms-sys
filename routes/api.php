<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\SubscriptionController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook']);

Route::middleware(['auth:api', 'role:admin|instructor|student'])->group(function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::put('profile', [AuthController::class, 'update']);
    Route::post('logout', [AuthController::class, 'logout']);


    Route::middleware(['role:admin'])->group(function () {
        Route::get('/subscriptions', [SubscriptionController::class, 'index']);
        Route::post('/courses', [CourseController::class, 'store']);
        Route::put('/courses/{course}', [CourseController::class, 'update']);
        Route::delete('/courses/{course}', [CourseController::class, 'destroy']);
    });

    Route::middleware(['role:student'])->group(function () {
        Route::get('/courses', [CourseController::class, 'index']);
        Route::get('/courses/{course}', [CourseController::class, 'show']);

        Route::post('/stripe/checkout', [StripeController::class, 'createCheckoutSession']);
        Route::get('/stripe/success', [StripeController::class, 'success'])->name('stripe.success');
        Route::get('/stripe/cancel', [StripeController::class, 'cancel'])->name('stripe.cancel');
    });
});
