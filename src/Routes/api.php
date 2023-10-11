<?php


use AnisAronno\LaravelSettings\Http\Controllers\LaravelSettingsController;

Route::prefix('/v1')->group(function () {
    Route::get('/settings', [LaravelSettingsController::class, 'index'])->name('settings.index');
    Route::get('/settings/{key}', [LaravelSettingsController::class, 'show'])->name('settings.show');
    Route::post('/settings', [LaravelSettingsController::class, 'store'])->name('settings.store');
    Route::post('/settings/update/{key}', [LaravelSettingsController::class, 'update'])->name('settings.update');
    Route::delete('/settings/{key}', [LaravelSettingsController::class, 'destroy'])->name('settings.destroy');
});
