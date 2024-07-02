<?php

use Illuminate\Support\Facades\Route;
use Zus1\LaravelAuth\Constant\RouteName;

Route::prefix('api/auth')->group(function () {
    Route::get('verify', \Zus1\LaravelAuth\Controllers\Verify::class)
        ->name(RouteName::VERIFY_USER);
    Route::post('/reset-password-send', \Zus1\LaravelAuth\Controllers\ResetPasswordSend::class)
        ->name(RouteName::RESET_PASSWORD_SEND);
    Route::post('/reset_password',\Zus1\LaravelAuth\Controllers\ResetPassword::class)
        ->name(RouteName::RESET_PASSWORD);
    Route::post('/login', \Zus1\LaravelAuth\Controllers\Login::class)
        ->name(RouteName::LOGIN);
    Route::get('/logout', \Zus1\LaravelAuth\Controllers\Logout::class)
        ->name(RouteName::LOGOUT);
    Route::get('/refresh-token', \Zus1\LaravelAuth\Controllers\RefreshToken::class)
        ->name(RouteName::REFRESH_TOKEN);
    Route::post('/resend-email', \Zus1\LaravelAuth\Controllers\ResendEmail::class)
        ->name(RouteName::RESEND_EMAIL);
});