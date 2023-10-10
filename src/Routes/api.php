<?php


use AnisAronno\LaravelSettings\Http\Controllers\LaravelSettingsController;


Route::resource('settings', LaravelSettingsController::class, ['except' => ['create, update']]);
Route::post('/settings/update/{key}', [LaravelSettingsController::class, 'update'])->name('settings.update');
Route::post('/settings/bulk/update', [LaravelSettingsController::class, 'bulkUpdate'])->name('settings.bulk.update');
