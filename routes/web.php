<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrangChuController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/trangchu', [TrangChuController::class, 'index'])->name('trangchu');
