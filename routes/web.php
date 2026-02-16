<?php

use App\Http\Controllers\TransposeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TransposeController::class, 'index'])->name('transpose.index');
Route::post('/transpose', [TransposeController::class, 'transpose'])->name('transpose.api');
Route::post('/pdf', [TransposeController::class, 'pdf'])->name('transpose.pdf');
