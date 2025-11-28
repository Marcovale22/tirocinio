<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



/*--------------PARTE PUBLICA----------*/

Route::get('/', function () {
return view('publica.homepage');
})->name('home');


Route::get('/vini', [PublicController::class,'vini'])->name('vini');
Route::get('/tenute', [PublicController::class,'tenute'])->name('tenute');
Route::get('/affitta-vigneto', [PublicController::class,'affittaVigneto'])->name('affitta');
Route::get('/shop', [PublicController::class,'shop'])->name('shop');

require __DIR__.'/auth.php';
