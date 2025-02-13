<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;


Route::middleware(['guest'])->group(static function (): void {
    Route::get(
        'login/{email}',
        LoginController::class,
    )->middleware('signed')->name('login.store');
});