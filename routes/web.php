<?php

use App\Http\Controllers\AdminDipendentiController;
use App\Http\Controllers\AdminCatalogoController;
use App\Http\Controllers\StaffCatalogoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaffRifornimentiController;
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

    Route::controller(StaffCatalogoController::class)->prefix('catalogo')->name('staff.catalogo.')->group(function () {
        
        Route::get('/', 'getCatalogo')->name('index');
    });

    Route::controller(StaffRifornimentiController::class)
        ->prefix('rifornimenti')->name('staff.rifornimenti.')
        ->group(function () {
            Route::post('/', 'store')->name('store');
        });
});

Route::prefix('admin')->middleware('auth', 'can:isAdmin')->group(function () {

    Route::controller(AdminDipendentiController::class)->prefix('dipendenti')->name('dipendenti.')->group(function () {

        Route::get('/', 'getDipendenti')->name('index');

        Route::post('/aggiungi', 'storeDipendente')->name('store');

        Route::put('/{user}', 'updateDipendente')->name('update');

        Route::delete('/{user}', 'destroyDipendente')->name('destroy');
    });


    Route::controller(AdminCatalogoController::class)->prefix('catalogo')->name('catalogo.')->group(function () {
        
        Route::get('/', 'getCatalogo')->name('index');
        /*----CRUD vini----*/ 
        Route::post('/vini', 'storeVino')->name('store.vini');

        Route::put('/vini/{prodotto}', 'updateVino')->name('update.vini');

        Route::delete('/vini/{prodotto}', 'destroyVino')->name('destroy.vino');
        
   
        
        /*----CRUD merch----*/ 
        Route::post('/merch', 'storeMerch')->name('store.merch');

        Route::put('/merch/{prodotto}', 'updateMerch')->name('update.merch');

        Route::delete('/merch/{prodotto}', 'destroyMerch')->name('destroy.merch');
        
        /*----CRUD evento----*/ 
        Route::post('/evento', 'storeEvento')->name('store.evento');

        Route::put('/evento/{prodotto}', 'updateEvento')->name('update.evento');

        Route::delete('/evento/{prodotto}', 'destroyEvento')->name('destroy.evento');
        
        /*----CRUD vigneto----*/ 
        Route::post('/vigneto', 'storeVigneto')->name('store.vigneto');

        Route::put('/vigneto/{prodotto}', 'updateVigneto')->name('update.vigneto');

        Route::delete('vigneto/{prodotto}', 'destroyVigneto')->name('destroy.vigneto');
    });
    

});



require __DIR__.'/auth.php';
