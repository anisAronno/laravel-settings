<?php


use AnisAronno\LaravelSettings\Http\Controllers\LaravelSettingsController;

$middleware = config()->has('laravel-settings.guard') ? config('laravel-settings.guard') : [];

Route::prefix('/v1')->group(function () use ($middleware) {
    Route::get('/settings', [LaravelSettingsController::class, 'index'])->name('settings.index');
    Route::get('/settings/{key}', [LaravelSettingsController::class, 'show'])->name('settings.show');   

    Route::middleware($middleware)->group(function () {
        Route::post('/settings', [LaravelSettingsController::class, 'store'])->name('settings.store');
        Route::post('/settings/update/{key}', [LaravelSettingsController::class, 'update'])->name('settings.update');
        Route::delete('/settings/{key}', [LaravelSettingsController::class, 'destroy'])->name('settings.destroy');
    });
    
});
