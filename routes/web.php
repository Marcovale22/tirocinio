<?php

use App\Http\Controllers\AdminDipendentiController;
use App\Http\Controllers\AdminCatalogoController;
use App\Http\Controllers\AdminRifornimentoController;
use App\Http\Controllers\StaffCatalogoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaffRifornimentiController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\CarrelloController;
use App\Http\Controllers\OrdineController;
use App\Http\Controllers\StaffOrdiniController;
use App\Http\Controllers\VignetoAffittoController;
use App\Http\Controllers\Staff\RichiestaVignetoStaffController;
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


Route::controller(PublicController::class)->group(function () {

    Route::get('/vini', 'vini')->name('vini');
    Route::get('/tenute', 'tenute')->name('tenute');
    Route::get('/affitta-vigneto', 'affittaVigneto')->name('affitta');
    Route::get('/shop', 'shop')->name('shop');

    Route::get('/vini/{vino}', 'vinoDettaglio')->name('vini.dettaglio');
    Route::get('/eventi/{evento}', 'eventoDettaglio')->name('eventi.dettaglio');
    Route::get('/vigneti/{vigneto}', 'vignetoDettaglio')->name('vigneti.dettaglio');
});

Route::prefix('utente')->middleware('auth', 'can:isUtente')->group(function () {
    
    Route::controller(CarrelloController::class)->prefix('utente')->name('utente.')->group(function () {
        
        Route::get('/carrello',  'index')->name('carrello.index');
        Route::post('/carrello/add/{prodotto}',  'add')->name('carrello.add');
        Route::post('/carrello/update/{prodotto}',  'update')->name('carrello.update');
        Route::post('/carrello/remove/{prodotto}',  'remove')->name('carrello.remove');
    });

    Route::controller(OrdineController::class)->prefix('utente')->name('utente.')->group(function () {
    
        Route::post('/carrello/checkout',  'checkout')->name('carrello.checkout');

        Route::get('/ordini',  'mieiOrdini')->name('ordini');
    });

   Route::controller(VignetoAffittoController::class)->prefix('utente')->name('utente.')->group(function () {
        
        Route::post('/vigneti/{vigneto}/richiesta',  'store')->name('vigneti.richiesta.store');

        Route::get('/i-miei-vigneti',  'mieiVigneti')->name('vigneti.miei');

        Route::post('/vigneti/richieste/{richiesta}/annulla',  'annulla')->name('vigneti.richieste.annulla');
   });


});

Route::prefix('staff')->middleware('auth', 'can:isStaff')->group(function () {

    Route::controller(StaffCatalogoController::class)->prefix('catalogo')->name('staff.catalogo.')->group(function () {
        
        Route::get('/', 'getCatalogo')->name('index');
    });

    Route::controller(StaffRifornimentiController::class)->prefix('rifornimenti')->name('staff.rifornimenti.')->group(function () {
        Route::post('/', 'store')->name('store');
    });

    Route::controller(StaffOrdiniController::class)->prefix('ordine')->name('staff.ordini.')->group(function () {
        
        Route::get('/ordini', 'index')->name('index');

        Route::post('/ordini/{ordine}/stato', 'aggiornaStato')->name('stato');
    });    

    Route::controller(RichiestaVignetoStaffController::class)->prefix('vigneti')->name('staff.vigneti.')->group(function () {
        
        Route::get('/richieste', 'index')->name('richieste');

        Route::post('/richieste/{richiesta}/conferma', 'conferma')->name('richieste.conferma');

        Route::post('/richieste/{richiesta}/rifiuta', 'rifiuta')->name('richieste.rifiuta');


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
        Route::get('/evento/create', 'createEvento')
            ->name('create.evento');

        // Mostra form MODIFICA evento
        Route::get('/evento/{prodotto}/edit', 'editEvento')
            ->name('edit.evento');

        Route::post('/evento', 'storeEvento')->name('store.evento');

        Route::put('/evento/{prodotto}', 'updateEvento')->name('update.evento');

        Route::delete('/evento/{prodotto}', 'destroyEvento')->name('destroy.evento');
        
        /*----CRUD vigneto----*/ 
        Route::post('/vigneto', 'storeVigneto')->name('store.vigneto');

        Route::put('/vigneto/{prodotto}', 'updateVigneto')->name('update.vigneto');

        Route::delete('vigneto/{prodotto}', 'destroyVigneto')->name('destroy.vigneto');
    });
    

    Route::controller(AdminRifornimentoController::class)->prefix('rifornimenti')->name('admin.rifornimenti.')->group(function () {
            Route::get('/', 'getRifornimenti')->name('getRifornimenti');

            Route::get('/storico', 'getStoricoRifornimenti')->name('storico');

            Route::post('/{rifornimento}','aggiornaStato')->name('aggiornaStato');
        });

});



require __DIR__.'/auth.php';
