<?php

namespace App\Http\Controllers;
use App\Models\Prodotto;
use App\Models\Vino;
use App\Models\Vigneto;
use App\Models\Evento;
use Illuminate\Http\Request;

class AdminCatalogoController extends Controller
{
    public function getCatalogo()
    {
        // Recupero vini (prodotti con tipo = vino)
        $vini = Prodotto::with('vino')
            ->where('tipo', 'vino')
            ->get();

        // Recupero merch (prodotti senza tabella specifica)
        $merch = Prodotto::where('tipo', 'merch')->get();

        // Recupero eventi (prodotti con tabella evento)
        $eventi = Prodotto::with('evento')
            ->where('tipo', 'evento')
            ->get();

        // Recupero vigneti (tabella dedicata)
        $vigneti = Vigneto::all();

        // Ritorno la vista catalogo con tutte le variabili
        return view('admin.catalogo', compact('vini', 'merch', 'eventi', 'vigneti'));
    }

    /*----SEZIONE VINO-----*/
    
  

    public function storeVino(Request $request)
    {
        $validated = $request->validate([
            'nome'          => 'required|string|max:255',
            'prezzo'        => 'required|numeric|min:0',
            'annata'        => 'required|integer|min:1900|max:' . date('Y'),
            'formato'       => 'required|string|max:50',
            'gradazione'    => 'required|numeric|min:0|max:30',
            'disponibilita' => 'nullable|integer|min:0',
            'solfiti'       => 'nullable|numeric|min:0',
            'immagine'      => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ], [
            'nome.required'          => 'Il nome del vino è obbligatorio.',
            'prezzo.required'        => 'Inserisci un prezzo valido.',
            'annata.required'        => 'L\'annata è obbligatoria.',
            'annata.min'             => 'L\'annata minima consentita è il 1900.',
            'annata.max'             => 'L\'annata non può essere nel futuro.',
            'formato.required'       => 'Il formato è obbligatorio.',
            'gradazione.required'    => 'La gradazione è obbligatoria.',
            'gradazione.max'         => 'La gradazione massima è 30°.',
            'immagine.image'         => 'Il file caricato deve essere un\'immagine.',
            'immagine.mimes'         => 'Formato immagine non valido. Usa JPG, PNG o WEBP.',
        ]);

        //  Placeholder di default
        $imageName = 'placeholder_vino.png';

        //  Se carico una vera immagine, la salvo in public/img/vini
        if ($request->hasFile('immagine')) {
            $file = $request->file('immagine');
            $imageName = $file->getClientOriginalName();
            $file->move(public_path('img/vini'), $imageName);
        }

        $disponibilita = $validated['disponibilita'] ?? 0;
        // Prodotto generico
        $prodotto = Prodotto::create([
            'nome'     => $validated['nome'],
            'tipo'     => 'vino',
            'disponibilita' => $disponibilita,
            'prezzo'   => $validated['prezzo'],
            'immagine' => $imageName,
        ]);

        // Default a 0 se non inviati

        $solfiti       = $validated['solfiti']       ?? 0;

        Vino::create([
            'prodotto_id'   => $prodotto->id,
            'annata'        => $validated['annata'],
            'solfiti'       => $solfiti,
            'formato'       => $validated['formato'],
            'gradazione'    => $validated['gradazione'],
        ]);

        return redirect()
            ->back()
            ->with('success', 'Vino creato correttamente.');
    }

    public function updateVino(Request $request, $prodotto)
    {
        $validated = $request->validate([
            'nome'          => 'required|string|max:255',
            'prezzo'        => 'required|numeric|min:0',
            'annata'        => 'required|integer|min:1900|max:' . date('Y'),
            'formato'       => 'required|string|max:50',
            'gradazione'    => 'required|numeric|min:0|max:30',
            'disponibilita' => 'nullable|integer|min:0',
            'solfiti'       => 'nullable|numeric|min:0',
            'immagine'      => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ], [
            'nome.required'          => 'Il nome del vino è obbligatorio.',
            'prezzo.required'        => 'Inserisci un prezzo valido.',
            'annata.required'        => 'L\'annata è obbligatoria.',
            'annata.min'             => 'L\'annata minima consentita è il 1900.',
            'annata.max'             => 'L\'annata non può essere nel futuro.',
            'formato.required'       => 'Il formato è obbligatorio.',
            'gradazione.required'    => 'La gradazione è obbligatoria.',
            'gradazione.max'         => 'La gradazione massima è 30°.',
            'immagine.image'         => 'Il file caricato deve essere un\'immagine.',
            'immagine.mimes'         => 'Formato immagine non valido. Usa JPG, PNG o WEBP.',
        ]);

        $prod = Prodotto::where('tipo', 'vino')->findOrFail($prodotto);

        //  di base tengo l'immagine attuale (che può essere il placeholder)
        $imageName = $prod->immagine ?? 'placeholder_vino.png';

        //  se carico una nuova immagine, la sovrascrivo
        if ($request->hasFile('immagine')) {
            $file = $request->file('immagine');
            $newImageName = $file->getClientOriginalName();
            $file->move(public_path('img/vini'), $newImageName);

            
            if ($imageName && $imageName !== 'placeholder_vino.png') {
                $oldPath = public_path('img/vini/' . $imageName);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            

            $imageName = $newImageName;
        }

        $prod->update([
            'nome'          => $validated['nome'],
            'prezzo'        => $validated['prezzo'],
            'immagine'      => $imageName,
            'disponibilita' => $validated['disponibilita'] ?? $prod->disponibilita,
        ]);


        $vino = Vino::where('prodotto_id', $prod->id)->firstOrFail();

        
        if (isset($validated['solfiti'])) {
            $vino->solfiti = $validated['solfiti'];
        }

        $vino->annata     = $validated['annata'];
        $vino->formato    = $validated['formato'];
        $vino->gradazione = $validated['gradazione'];
        $vino->save();

        return redirect()
            ->back()
            ->with('success', 'Vino aggiornato correttamente.');
    }




    public function destroyVino($prodotto)
    {
        
        $vino = Prodotto::findOrFail($prodotto);

        // Se ha un'immagine salvata, la eliminiamo dal filesystem
        /*
        if ($vino->immagine && file_exists(public_path('img/vini/' . $vino->immagine))) {
            unlink(public_path('img/vini/' . $vino->immagine));
        }*/

       
        $vino->delete();

        
        return redirect()
            ->back()
            ->with('success', 'Vino eliminato correttamente.');
    }

    /*----SEZIONE MERCH-----*/

    public function storeMerch(Request $request)
    {
       $validated = $request->validate([
            'nome'          => 'required|string|max:255',
            'prezzo'        => 'required|numeric|min:0',
            'disponibilita' => 'nullable|integer|min:0',
            'immagine'      => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ], [
            // Nome
            'nome.required' => 'Il nome del prodotto è obbligatorio.',
            'nome.string'   => 'Il nome deve essere una stringa valida.',
            'nome.max'      => 'Il nome non può superare i 255 caratteri.',

            // Prezzo
            'prezzo.required' => 'Il prezzo è obbligatorio.',
            'prezzo.numeric'  => 'Il prezzo deve essere un valore numerico.',
            'prezzo.min'      => 'Il prezzo non può essere negativo.',

            // Disponibilità
            'disponibilita.integer' => 'La disponibilità deve essere un numero intero.',
            'disponibilita.min'     => 'La disponibilità non può essere negativa.',

            // Immagine
            'immagine.image' => 'Il file caricato deve essere un\'immagine.',
            'immagine.mimes' => 'I formati supportati sono: JPG, JPEG, PNG, WEBP.',
        ]);


    // Placeholder
    $imageName = 'placeholder_merch.png';

    // Upload immagine
    if ($request->hasFile('immagine')) {
        $file = $request->file('immagine');

        if ($file->isValid()) {
            $imageName = $file->getClientOriginalName();

            $file->move(public_path('img/merch'), $imageName);
        }
    }

    Prodotto::create([
        'nome'          => $validated['nome'],
        'tipo'          => 'merch',
        'prezzo'        => $validated['prezzo'],
        'disponibilita' => $validated['disponibilita'] ?? 0,
        'immagine'      => $imageName,
    ]);

    return back()->with('success', 'Merch creato correttamente!');
    }

    public function updateMerch(Request $request,$id)
    {
        $validated = $request->validate([
            'nome'          => 'required|string|max:255',
            'prezzo'        => 'required|numeric|min:0',
            'disponibilita' => 'nullable|integer|min:0',
            'immagine'      => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ], [
            // Nome
            'nome.required' => 'Il nome del prodotto è obbligatorio.',
            'nome.string'   => 'Il nome deve essere una stringa valida.',
            'nome.max'      => 'Il nome non può superare i 255 caratteri.',

            // Prezzo
            'prezzo.required' => 'Il prezzo è obbligatorio.',
            'prezzo.numeric'  => 'Il prezzo deve essere un valore numerico.',
            'prezzo.min'      => 'Il prezzo non può essere negativo.',

            // Disponibilità
            'disponibilita.integer' => 'La disponibilità deve essere un numero intero.',
            'disponibilita.min'     => 'La disponibilità non può essere negativa.',

            // Immagine
            'immagine.image' => 'Il file caricato deve essere un\'immagine.',
            'immagine.mimes' => 'I formati supportati sono: JPG, JPEG, PNG, WEBP.',
        ]);


    $prod = Prodotto::where('tipo', 'merch')->findOrFail($id);

    $imageName = $prod->immagine ?? 'placeholder_merch.png';

    // Se viene caricata una nuova immagine
    if ($request->hasFile('immagine')) {
        $file = $request->file('immagine');

        if ($file->isValid()) {
            $newName = $file->getClientOriginalName();
            $file->move(public_path('img/merch'), $newName);
            $imageName = $newName;
        }
    }

    $prod->update([
        'nome'          => $validated['nome'],
        'prezzo'        => $validated['prezzo'],
        'disponibilita' => $validated['disponibilita'] ?? $prod->disponibilita,
        'immagine'      => $imageName,
    ]);

    return back()->with('success', 'Merch aggiornato!');
    }
    public function destroyMerch($prodotto)
    {
        $merch = Prodotto::findOrFail($prodotto);

        
        if ($merch->immagine && file_exists(public_path('img/merch/' . $merch->immagine))) {
            unlink(public_path('img/merch/' . $merch->immagine));
        }
        

        $merch->delete();

        return redirect()
            ->back()
            ->with('success', 'Merch eliminato correttamente.');
    }

    /*----SEZIONE EVENTO-----*/

     public function storeEvento(Request $request)
    {
        $validated = $request->validate([
            'nome'          => 'required|string|max:255',
            'prezzo'        => 'required|numeric|min:0',
            'disponibilita' => 'nullable|integer|min:0',
            'data_evento'   => 'required|date',
            'ora_evento'    => 'required|string|max:10',
            'luogo'         => 'required|string|max:255',
            'descrizione'   => 'nullable|string',
            'immagine'      => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ], [

            // Nome
            'nome.required' => 'Il nome dell\'evento è obbligatorio.',
            'nome.string'   => 'Il nome deve essere una stringa valida.',
            'nome.max'      => 'Il nome non può superare i 255 caratteri.',

            // Prezzo
            'prezzo.required' => 'Il prezzo dell\'evento è obbligatorio.',
            'prezzo.numeric'  => 'Il prezzo deve essere un valore numerico.',
            'prezzo.min'      => 'Il prezzo non può essere negativo.',

            // Disponibilità
            'disponibilita.integer' => 'La disponibilità deve essere un numero intero.',
            'disponibilita.min'     => 'La disponibilità non può essere negativa.',

            // Data evento
            'data_evento.required' => 'La data dell\'evento è obbligatoria.',
            'data_evento.date'     => 'Inserisci una data valida per l\'evento.',

            // Ora evento
            'ora_evento.required' => 'L\'ora dell\'evento è obbligatoria.',
            'ora_evento.string'   => 'L\'ora deve essere una stringa valida.',
            'ora_evento.max'      => 'Il formato dell\'ora non può superare i 10 caratteri.',

            // Luogo
            'luogo.required' => 'Il luogo dell\'evento è obbligatorio.',
            'luogo.string'   => 'Il luogo deve essere una stringa valida.',
            'luogo.max'      => 'Il luogo non può superare i 255 caratteri.',

            // Descrizione
            'descrizione.string' => 'La descrizione deve essere un testo valido.',

            // Immagine
            'immagine.image' => 'Il file caricato deve essere un\'immagine.',
            'immagine.mimes' => 'I formati supportati sono: JPG, JPEG, PNG, WEBP.',
        ]);


        // Placeholder di default
        $imageName = 'placeholder_evento.png';

        // Se carico una nuova immagine
        if ($request->hasFile('immagine')) {
            $file = $request->file('immagine');

            if ($file->isValid()) {
                $imageName = $file->getClientOriginalName();
                $file->move(public_path('img/eventi'), $imageName);
            }
        }

        // 1) Creo il prodotto generico
        $prodotto = Prodotto::create([
            'nome'          => $validated['nome'],
            'tipo'          => 'evento',
            'prezzo'        => $validated['prezzo'],
            'disponibilita' => $validated['disponibilita'] ?? 0,
            'immagine'      => $imageName,
        ]);

        // 2) Creo il record specifico nella tabella eventi
        Evento::create([
            'prodotto_id' => $prodotto->id,
            'data_evento' => $validated['data_evento'],
            'ora_evento'  => $validated['ora_evento'],
            'luogo'       => $validated['luogo'],
            'descrizione' => $validated['descrizione'] ?? null,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Evento creato correttamente.');
    }
    

    public function updateEvento(Request $request,$prodotto)
    {
        $validated = $request->validate([
            'nome'          => 'required|string|max:255',
            'prezzo'        => 'required|numeric|min:0',
            'disponibilita' => 'nullable|integer|min:0',
            'data_evento'   => 'required|date',
            'ora_evento'    => 'required|string|max:10',
            'luogo'         => 'required|string|max:255',
            'descrizione'   => 'nullable|string',
            'immagine'      => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ], [

            // Nome
            'nome.required' => 'Il nome dell\'evento è obbligatorio.',
            'nome.string'   => 'Il nome deve essere una stringa valida.',
            'nome.max'      => 'Il nome non può superare i 255 caratteri.',

            // Prezzo
            'prezzo.required' => 'Il prezzo dell\'evento è obbligatorio.',
            'prezzo.numeric'  => 'Il prezzo deve essere un valore numerico.',
            'prezzo.min'      => 'Il prezzo non può essere negativo.',

            // Disponibilità
            'disponibilita.integer' => 'La disponibilità deve essere un numero intero.',
            'disponibilita.min'     => 'La disponibilità non può essere negativa.',

            // Data evento
            'data_evento.required' => 'La data dell\'evento è obbligatoria.',
            'data_evento.date'     => 'Inserisci una data valida per l\'evento.',

            // Ora evento
            'ora_evento.required' => 'L\'ora dell\'evento è obbligatoria.',
            'ora_evento.string'   => 'L\'ora deve essere una stringa valida.',
            'ora_evento.max'      => 'Il formato dell\'ora non può superare i 10 caratteri.',

            // Luogo
            'luogo.required' => 'Il luogo dell\'evento è obbligatorio.',
            'luogo.string'   => 'Il luogo deve essere una stringa valida.',
            'luogo.max'      => 'Il luogo non può superare i 255 caratteri.',

            // Descrizione
            'descrizione.string' => 'La descrizione deve essere un testo valido.',

            // Immagine
            'immagine.image' => 'Il file caricato deve essere un\'immagine.',
            'immagine.mimes' => 'I formati supportati sono: JPG, JPEG, PNG, WEBP.',
        ]);


        // 1) Prodotto generico (controllo anche tipo = evento per sicurezza)
        $prod = Prodotto::where('tipo', 'evento')->findOrFail($prodotto);

        $imageName = $prod->immagine ?? 'placeholder_evento.png';

        if ($request->hasFile('immagine')) {
            $file = $request->file('immagine');

            if ($file->isValid()) {
                $newName = $file->getClientOriginalName();
                $file->move(public_path('img/eventi'), $newName);
                $imageName = $newName;
            }
        }

        // aggiorno il prodotto
        $prod->update([
            'nome'          => $validated['nome'],
            'prezzo'        => $validated['prezzo'],
            'disponibilita' => $validated['disponibilita'] ?? $prod->disponibilita,
            'immagine'      => $imageName,
        ]);

        // 2) aggiorno il record di specializzazione evento
        $evento = Evento::where('prodotto_id', $prod->id)->firstOrFail();

        $evento->data_evento = $validated['data_evento'];
        $evento->ora_evento  = $validated['ora_evento'];
        $evento->luogo       = $validated['luogo'];
        $evento->descrizione = $validated['descrizione'] ?? $evento->descrizione;
        $evento->save();

        return redirect()
            ->back()
            ->with('success', 'Evento aggiornato correttamente.');
    }

    public function destroyEvento($prodotto)
    {
        $evento = Prodotto::findOrFail($prodotto);

        /*
        if ($evento->immagine && file_exists(public_path('img/eventi/' . $evento->immagine))) {
            unlink(public_path('img/eventi/' . $evento->immagine));
        }
        */

        $evento->delete();

        return redirect()
            ->back()
            ->with('success', 'Evento eliminato correttamente.');
    }

    /*----SEZIONE VIGNETO-----*/

     public function storeVigneto(Request $request)
    {
        $validated = $request->validate([
            'nome'              => 'required|string|max:255',
            'descrizione'       => 'nullable|string',
            'disponibilita'     => 'nullable|integer|min:0',
            'prezzo_annuo'      => 'required|numeric|min:0',

            // NUOVI CAMPI
            'bottiglie_stimate' => 'nullable|integer|min:0',
            'tipo_vino'         => 'nullable|in:rosso,bianco,rosato',
            'fase_produzione'   => 'nullable|in:potatura,germogliamento,fioritura,invaiatura,vendemmia,vinificazione,affinamento,imbottigliamento,pronto',

            'immagine'          => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'visibile'          => 'nullable|boolean',
        ], [
            // Nome
            'nome.required' => 'Il nome del vigneto è obbligatorio.',
            'nome.string'   => 'Il nome deve essere una stringa valida.',
            'nome.max'      => 'Il nome non può superare i 255 caratteri.',

            // Descrizione
            'descrizione.string' => 'La descrizione deve essere un testo valido.',

            // Disponibilità
            'disponibilita.integer' => 'La disponibilità deve essere un numero intero.',
            'disponibilita.min'     => 'La disponibilità non può essere negativa.',

            // Prezzo annuo
            'prezzo_annuo.required' => 'Il prezzo annuo è obbligatorio.',
            'prezzo_annuo.numeric'  => 'Il prezzo annuo deve essere un valore numerico.',
            'prezzo_annuo.min'      => 'Il prezzo annuo non può essere negativo.',

            // Nuovi campi
            'bottiglie_stimate.integer' => 'Le bottiglie stimate devono essere un numero intero.',
            'bottiglie_stimate.min'     => 'Le bottiglie stimate non possono essere negative.',
            'tipo_vino.in'              => 'Il tipo di vino deve essere: rosso, bianco o rosato.',
            'fase_produzione.in'        => 'La fase selezionata non è valida.',

            // Immagine
            'immagine.image' => 'Il file caricato deve essere un\'immagine.',
            'immagine.mimes' => 'I formati supportati sono: JPG, JPEG, PNG, WEBP.',
        ]);

        // Placeholder di default
        $imageName = 'placeholder_vigneto.png';

        if ($request->hasFile('immagine')) {
            $file = $request->file('immagine');
            if ($file->isValid()) {
                $imageName = $file->getClientOriginalName();
                $file->move(public_path('img/vigneti'), $imageName);
            }
        }

        $visibile = $request->has('visibile'); // true se spuntata, false se no

        Vigneto::create([
            'nome'              => $validated['nome'],
            'descrizione'       => $validated['descrizione'] ?? null,
            'disponibilita'     => $validated['disponibilita'] ?? 0,
            'prezzo_annuo'      => $validated['prezzo_annuo'],

            // NUOVI CAMPI
            'bottiglie_stimate' => $validated['bottiglie_stimate'] ?? null,
            'tipo_vino'         => $validated['tipo_vino'] ?? null,
            'fase_produzione'   => $validated['fase_produzione'] ?? null,

            'immagine'          => $imageName,
            'visibile'          => $visibile,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Vigneto creato correttamente.');
    }


    public function updateVigneto(Request $request, $prodotto)
    {
        $validated = $request->validate([
            'nome'              => 'required|string|max:255',
            'descrizione'       => 'nullable|string',
            'disponibilita'     => 'nullable|integer|min:0',
            'prezzo_annuo'      => 'required|numeric|min:0',

            // NUOVI CAMPI
            'bottiglie_stimate' => 'nullable|integer|min:0',
            'tipo_vino'         => 'nullable|in:rosso,bianco,rosato',
            'fase_produzione'   => 'nullable|in:potatura,germogliamento,fioritura,invaiatura,vendemmia,vinificazione,affinamento,imbottigliamento,pronto',

            'immagine'          => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'visibile'          => 'nullable|boolean',
        ], [
            // Nome
            'nome.required' => 'Il nome del vigneto è obbligatorio.',
            'nome.string'   => 'Il nome deve essere una stringa valida.',
            'nome.max'      => 'Il nome non può superare i 255 caratteri.',

            // Descrizione
            'descrizione.string' => 'La descrizione deve essere un testo valido.',

            // Disponibilità
            'disponibilita.integer' => 'La disponibilità deve essere un numero intero.',
            'disponibilita.min'     => 'La disponibilità non può essere negativa.',

            // Prezzo annuo
            'prezzo_annuo.required' => 'Il prezzo annuo è obbligatorio.',
            'prezzo_annuo.numeric'  => 'Il prezzo annuo deve essere un valore numerico.',
            'prezzo_annuo.min'      => 'Il prezzo annuo non può essere negativo.',

            // Nuovi campi
            'bottiglie_stimate.integer' => 'Le bottiglie stimate devono essere un numero intero.',
            'bottiglie_stimate.min'     => 'Le bottiglie stimate non possono essere negative.',
            'tipo_vino.in'              => 'Il tipo di vino deve essere: rosso, bianco o rosato.',
            'fase_produzione.in'        => 'La fase selezionata non è valida.',

            // Immagine
            'immagine.image' => 'Il file caricato deve essere un\'immagine.',
            'immagine.mimes' => 'I formati supportati sono: JPG, JPEG, PNG, WEBP.',
        ]);

        $vigneto = Vigneto::findOrFail($prodotto);

        $imageName = $vigneto->immagine ?? 'placeholder_vigneto.png';

        if ($request->hasFile('immagine')) {
            $file = $request->file('immagine');
            if ($file->isValid()) {
                $newName = $file->getClientOriginalName();
                $file->move(public_path('img/vigneti'), $newName);
                $imageName = $newName;
            }
        }

        $visibile = $request->has('visibile');

        $vigneto->update([
            'nome'              => $validated['nome'],
            'descrizione'       => $validated['descrizione'] ?? $vigneto->descrizione,
            'disponibilita'     => $validated['disponibilita'] ?? $vigneto->disponibilita,
            'prezzo_annuo'      => $validated['prezzo_annuo'],

            // NUOVI CAMPI
            'bottiglie_stimate' => $validated['bottiglie_stimate'] ?? $vigneto->bottiglie_stimate,
            'tipo_vino'         => array_key_exists('tipo_vino', $validated) ? $validated['tipo_vino'] : $vigneto->tipo_vino,
            'fase_produzione'   => array_key_exists('fase_produzione', $validated) ? $validated['fase_produzione'] : $vigneto->fase_produzione,

            'immagine'          => $imageName,
            'visibile'          => $visibile,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Vigneto aggiornato correttamente.');
    }


    public function destroyVigneto($prodotto)
    {
        $vigneto = Vigneto::findOrFail($prodotto);

        /*
        if ($vigneto->immagine && file_exists(public_path('img/vigneti/' . $vigneto->immagine))) {
            unlink(public_path('img/vigneti/' . $vigneto->immagine));
        }
        */

        $vigneto->delete();

        return redirect()
            ->back()
            ->with('success', 'Vigneto eliminato correttamente.');
    }


}
