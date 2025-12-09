<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;





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

Route::prefix('utente')->middleware('auth', 'can:isUtente')->group(function () {

   
});

Route::prefix('staff')->middleware('auth', 'can:isStaff')->group(function () {

   
});

Route::prefix('admin')->middleware('auth', 'can:isAdmin')->group(function () {

    Route::get('/dipendenti', [AdminController::class, 'getDipendenti'])->name('dipendenti');

    Route::post('/dipendenti/aggiungi', [AdminController::class, 'storeDipendente'])
        ->name('dipendenti.store');

    Route::put('/dipendenti/{user}', [AdminController::class, 'updateDipendente'])
        ->name('dipendenti.update');
    
    Route::delete('/dipendenti/{user}', [AdminController::class, 'destroyDipendente'])
        ->name('dipendenti.destroy');

    Route::get('/catalogo', [AdminController::class, 'getCatalogo'])->name('catalogo');

});



require __DIR__.'/auth.php';
