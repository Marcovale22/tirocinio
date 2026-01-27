<?php

namespace App\Http\Controllers;
use App\Models\Prodotto;
use App\Models\Vino;
use App\Models\Vigneto;
use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $eventi = Prodotto::with([
            'evento.vini.prodotto' 
        ])
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
            'nome_vino'          => 'required|string|max:255',
            'prezzo_vino'        => 'required|numeric|min:0',
            'annata'        => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'formato'       => 'required|string|max:50',
            'gradazione'    => 'required|numeric|min:0|max:30',
            'disponibilita_vino' => 'nullable|integer|min:0',
            'solfiti'       => 'nullable|numeric|min:0',
            'immagine_vino'      => 'nullable|image|mimes:jpg,jpeg,png,webp',
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

        // Blocca duplicati: stesso nome + stessa annata + stesso formato
        $exists = Prodotto::where('tipo', 'vino')
        ->where('nome', $validated['nome_vino'])
        ->whereHas('vino', function ($q) use ($validated) {
            $q->where('annata', $validated['annata'])
            ->where('formato', $validated['formato']);
        })
        ->exists();

        if ($exists) {
        return back()
            ->withInput()
            ->withErrors([
                'annata' => 'Esiste già un vino con lo stesso nome, annata e formato.',
            ]);
        }


    
        //  Placeholder di default
        $imageName = 'placeholder_vino.png';

        //  Se carico una vera immagine, la salvo in public/img/vini
        if ($request->hasFile('immagine_vino')) {
            $file = $request->file('immagine_vino');
            $imageName = $file->getClientOriginalName();
            $file->move(public_path('img/vini'), $imageName);
        }

        $disponibilita = $validated['disponibilita_vino'] ?? 0;
        // Prodotto generico
        $prodotto = Prodotto::create([
            'nome'     => $validated['nome_vino'],
            'tipo'     => 'vino',
            'disponibilita' => $disponibilita,
            'prezzo'   => $validated['prezzo_vino'],
            'immagine' => $imageName,
        ]);

        // Default a 0 se non inviati

        $solfiti = $validated['solfiti']  ?? 0;

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
            'nome_vino'          => 'required|string|max:255',
            'prezzo_vino'        => 'required|numeric|min:0',
            'annata'        => 'required|integer|min:1900|max:' . date('Y'),
            'formato'       => 'required|string|max:50',
            'gradazione'    => 'required|numeric|min:0|max:30',
            'disponibilita_vino' => 'nullable|integer|min:0',
            'solfiti'       => 'nullable|numeric|min:0',
            'immagine_vino'      => 'nullable|image|mimes:jpg,jpeg,png,webp',
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

        // Controllo duplicato (escludo questo prodotto)
        $exists = Prodotto::where('tipo', 'vino')
            ->where('id', '!=', $prod->id)
            ->where('nome', $validated['nome_vino'])
            ->whereHas('vino', function ($q) use ($validated) {
                $q->where('annata', $validated['annata'])
                  ->where('formato', $validated['formato']);
            })
            ->exists();
    
        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'annata' => 'Esiste già un vino con lo stesso nome, annata e formato.',
                ]);
        }
    
        try {
            DB::transaction(function () use ($request, $validated, $prod) {
    
                // immagine: se non carico nulla, mantengo quella attuale
                $imageName = $prod->immagine ?? 'placeholder_vino.png';
    
                if ($request->hasFile('immagine_vino')) {
                    $file = $request->file('immagine_vino');
    
                    if ($file->isValid()) {
                        $newName = $file->getClientOriginalName();
                        $file->move(public_path('img/vini'), $newName);
                        $imageName = $newName;
                    }
                }
    
                $disponibilita = $validated['disponibilita_vino'] ?? $prod->disponibilita ?? 0;
                $solfiti       = $validated['solfiti'] ?? 0;
    
                // 1) aggiorno prodotto generico
                $prod->update([
                    'nome'          => $validated['nome_vino'],
                    'prezzo'        => $validated['prezzo_vino'],
                    'disponibilita' => $disponibilita,
                    'immagine'      => $imageName,
                ]);
    
                // 2) aggiorno record specifico vino
                $vino = Vino::where('prodotto_id', $prod->id)->firstOrFail();
    
                $vino->annata     = $validated['annata'];
                $vino->formato    = $validated['formato'];
                $vino->gradazione = $validated['gradazione'];
                $vino->solfiti    = $solfiti;
                $vino->save();
            });
    
        } catch (\Illuminate\Database\QueryException $e) {
            return back()
                ->withInput()
                ->withErrors(['db' => 'Errore nel salvataggio. Riprova.']);
    
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->withErrors(['db' => 'Errore imprevisto. Riprova.']);
        }
    
        return redirect()
            ->back()
            ->with('success', 'Vino aggiornato correttamente.');
    
    }




    public function destroyVino($prodotto)
    {
        
        $vino = Prodotto::findOrFail($prodotto);

   
        if ($vino->immagine && file_exists(public_path('img/vini/' . $vino->immagine))) {
            unlink(public_path('img/vini/' . $vino->immagine));
        }

       
        $vino->delete();

        
        return redirect()
            ->back()
            ->with('success', 'Vino eliminato correttamente.');
    }

    /*----SEZIONE MERCH-----*/

    public function storeMerch(Request $request)
    {
       $validated = $request->validate([
            'nome_merch'          => 'required|string|max:255',
            'prezzo_merch'        => 'required|numeric|min:0',
            'disponibilita_merch' => 'nullable|integer|min:0',
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
        'nome'          => $validated['nome_merch'],
        'tipo'          => 'merch',
        'prezzo'        => $validated['prezzo_merch'],
        'disponibilita' => $validated['disponibilita_merch'] ?? 0,
        'immagine'      => $imageName,
    ]);

    return back()->with('success', 'Merch creato correttamente!');
    }

    public function updateMerch(Request $request,$id)
    {
        $validated = $request->validate([
            'nome_merch'          => 'required|string|max:255',
            'prezzo_merch'        => 'required|numeric|min:0',
            'disponibilita_merch' => 'nullable|integer|min:0',
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
        'nome'          => $validated['nome_merch'],
        'prezzo'        => $validated['prezzo_merch'],
        'disponibilita' => $validated['disponibilita_merch'] ?? $prod->disponibilita,
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
    public function createEvento()
    {
        return view('admin.createEvento');
    }

    public function editEvento($prodotto)
    {
        // Prodotto generico (evento)
        $prod = Prodotto::where('tipo', 'evento')
            ->findOrFail($prodotto);

        // Record specializzato evento + vini associati
        $evento = Evento::where('prodotto_id', $prod->id)
            ->with('vini.prodotto')
            ->firstOrFail();

        // Tutti i vini disponibili
        $vini = Vino::with('prodotto')
            ->orderBy('id')
            ->get();

        return view('admin.editEvento', compact('prod', 'evento', 'vini'));
    }

    private function syncViniEvento(Evento $evento, array $viniInput): void
    {
        // 1) Normalizza e accorpa duplicati
        $new = [];
        foreach ($viniInput as $row) {
            if (empty($row['vino_id']) || empty($row['quantita'])) continue;

            $vinoId = (int) $row['vino_id'];
            $qty    = (int) $row['quantita'];
            if ($qty <= 0) continue;

            $new[$vinoId] = ($new[$vinoId] ?? 0) + $qty;
        }

        // 2) Stato attuale pivot: [vino_id => quantita]
        // NB: nome tabella pivot = evento_vini
        $old = $evento->vini()->pluck('evento_vini.quantita', 'vini.id')->toArray();

        $allIds = array_unique(array_merge(array_keys($old), array_keys($new)));

        foreach ($allIds as $vinoId) {
            $oldQty = (int)($old[$vinoId] ?? 0);
            $newQty = (int)($new[$vinoId] ?? 0);
            $delta  = $newQty - $oldQty;

            if ($delta === 0) continue;

            // Lock per evitare corse su disponibilità
            $vino = Vino::with('prodotto')->lockForUpdate()->findOrFail($vinoId);
            $prodVino = $vino->prodotto; // prodotti.disponibilita

            if ($delta > 0) {
                if ($prodVino->disponibilita < $delta) {
                    throw new \RuntimeException("Disponibilità insufficiente per il vino selezionato.");
                }
                $prodVino->disponibilita -= $delta;
            } else {
                $prodVino->disponibilita += abs($delta);
            }

            $prodVino->save();
        }

        // 3) Sync pivot finale
        $syncData = [];
        foreach ($new as $vinoId => $qty) {
            $syncData[$vinoId] = ['quantita' => $qty];
        }

        $evento->vini()->sync($syncData);
    }
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
            'vini' => 'nullable|array',
            'vini.*.vino_id' => 'required_with:vini|exists:vini,id',
            'vini.*.quantita' => 'required_with:vini|integer|min:1',
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

        DB::transaction(function () use ($validated, $request, $prodotto, &$evento) {

            $evento = Evento::create([
                'prodotto_id' => $prodotto->id,
                'data_evento' => $validated['data_evento'],
                'ora_evento'  => $validated['ora_evento'],
                'luogo'       => $validated['luogo'],
                'descrizione' => $validated['descrizione'] ?? null,
            ]);

            if ($request->has('vini')) {
                $this->syncViniEvento($evento, $request->input('vini', []));
            }
        });

        return redirect()
            ->route('catalogo.edit.evento', $prodotto->id)
            ->with('success', 'Evento creato correttamente. Ora puoi associare i vini.')
            ->with('from_create', true);

    }
    

    public function updateEvento(Request $request,$prodotto)
    {
        $validated = $request->validate([
            'nome'          => 'required|string|max:255',
            'prezzo'        => 'required|numeric|min:0.01',
            'disponibilita' => 'nullable|integer|min:1',
            'data_evento'   => 'required|date',
            'ora_evento'    => 'required|string|max:10',
            'luogo'         => 'required|string|max:255',
            'descrizione'   => 'nullable|string',
            'immagine'      => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'vini' => 'nullable|array',
            'vini.*.vino_id' => 'required_with:vini|exists:vini,id',
            'vini.*.quantita' => 'required_with:vini|integer|min:1',
        ], [

            // Nome
            'nome.required' => 'Il nome dell\'evento è obbligatorio.',
            'nome.string'   => 'Il nome deve essere una stringa valida.',
            'nome.max'      => 'Il nome non può superare i 255 caratteri.',

            // Prezzo
            'prezzo.required' => 'Il prezzo dell\'evento è obbligatorio.',
            'prezzo.numeric'  => 'Il prezzo deve essere un valore numerico.',
            'prezzo.min'      => 'Il prezzo deve essere maggiore di 0.',

            // Disponibilità
            'disponibilita.integer' => 'La disponibilità deve essere un numero intero.',
            'disponibilita.min'     => 'La disponibilità deve essere maggiore di 0.',

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

        try {
            DB::transaction(function () use ($request, $validated, $prod) {

                $imageName = $prod->immagine ?? 'placeholder_evento.png';

                if ($request->hasFile('immagine')) {
                    $file = $request->file('immagine');

                    if ($file->isValid()) {
                        $newName = $file->getClientOriginalName();
                        $file->move(public_path('img/eventi'), $newName);
                        $imageName = $newName;
                    }
                }

                // 1) aggiorno il prodotto generico evento
                $prod->update([
                    'nome'          => $validated['nome'],
                    'prezzo'        => $validated['prezzo'],
                    'disponibilita' => $validated['disponibilita'] ?? $prod->disponibilita,
                    'immagine'      => $imageName,
                ]);

                // 2) aggiorno il record specifico evento
                $evento = Evento::where('prodotto_id', $prod->id)->firstOrFail();

                $evento->data_evento = $validated['data_evento'];
                $evento->ora_evento  = $validated['ora_evento'];
                $evento->luogo       = $validated['luogo'];
                $evento->descrizione = $validated['descrizione'] ?? $evento->descrizione;
                $evento->save();

                // 3) se arrivano vini dalla form, sincronizzo pivot + aggiorno giacenze
                if ($request->has('vini')) {
                    $evento->load('vini'); // per avere lo stato corrente
                    $this->syncViniEvento($evento, $request->input('vini', []));
                }
            });

        }  catch (\Illuminate\Database\QueryException $e) {

            return back()
                ->withInput()
                ->withErrors([
                    'db' => 'Errore nel salvataggio. Controlla data e ora e riprova.'
                ]);
        
        } catch (\RuntimeException $e) {
        
            return back()
                ->withInput()
                ->withErrors([
                    'vini' => 'Errore nell’associazione dei vini. Controlla quantità e disponibilità.'
                ]);
        
        } catch (\Throwable $e) {
        
            return back()
                ->withInput()
                ->withErrors([
                    'db' => 'Errore imprevisto. Riprova.'
                ]);
        }

        return redirect()
            ->route('catalogo.index')
            ->with('success', 'Evento aggiornato correttamente.');

    }

    public function destroyEvento($prodotto)
    {
        $evento = Prodotto::findOrFail($prodotto);

        
        if ($evento->immagine && file_exists(public_path('img/eventi/' . $evento->immagine))) {
            unlink(public_path('img/eventi/' . $evento->immagine));
        }
        

        $evento->delete();

        return redirect()
            ->back()
            ->with('success', 'Evento eliminato correttamente.');
    }

    /*----SEZIONE VIGNETO-----*/

     public function storeVigneto(Request $request)
    {
        $validated = $request->validate([
            'nome_vigneto'              => 'required|string|max:255',
            'descrizione'       => 'nullable|string',
            'disponibilita_vigneto'     => 'nullable|integer|min:0',
            'prezzo_annuo'      => 'required|numeric|min:0',

            // NUOVI CAMPI
            'bottiglie_stimate' => 'nullable|integer|min:0',
            'tipo_vino'         => 'nullable|in:rosso,bianco,rosato',
            'fase_produzione'   => 'nullable|in:potatura,germogliamento,fioritura,invaiatura,vendemmia,vinificazione,affinamento,imbottigliamento,pronto',

            'immagine_vigneto'          => 'nullable|image|mimes:jpg,jpeg,png,webp',
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

        if ($request->hasFile('immagine_vigneto')) {
            $file = $request->file('immagine_vigneto');
            if ($file->isValid()) {
                $imageName = $file->getClientOriginalName();
                $file->move(public_path('img/vigneti'), $imageName);
            }
        }

        $visibile = $request->has('visibile'); // true se spuntata, false se no

        Vigneto::create([
            'nome'              => $validated['nome_vigneto'],
            'descrizione'       => $validated['descrizione'] ?? null,
            'disponibilita'     => $validated['disponibilita_vigneto'] ?? 0,
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
            'nome_vigneto'      => 'required|string|max:255',
            'descrizione'       => 'nullable|string',
            'disponibilita_vigneto'     => 'nullable|integer|min:0',
            'prezzo_annuo'      => 'required|numeric|min:0.1',

            // NUOVI CAMPI
            'bottiglie_stimate' => 'nullable|integer|min:0',
            'tipo_vino'         => 'nullable|in:rosso,bianco,rosato',
            'fase_produzione'   => 'nullable|in:potatura,germogliamento,fioritura,invaiatura,vendemmia,vinificazione,affinamento,imbottigliamento,pronto',

            'immagine_vigneto'          => 'nullable|image|mimes:jpg,jpeg,png,webp',
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

        if ($request->hasFile('immagine_vigneto')) {
            $file = $request->file('immagine_vigneto');
            if ($file->isValid()) {
                $newName = $file->getClientOriginalName();
                $file->move(public_path('img/vigneti'), $newName);
                $imageName = $newName;
            }
        }

        $visibile = $request->has('visibile');

        $vigneto->update([
            'nome'              => $validated['nome_vigneto'],
            'descrizione'       => $validated['descrizione'] ?? $vigneto->descrizione,
            'disponibilita'     => $validated['disponibilita_vigneto'] ?? $vigneto->disponibilita,
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

        
        if ($vigneto->immagine && file_exists(public_path('img/vigneti/' . $vigneto->immagine))) {
            unlink(public_path('img/vigneti/' . $vigneto->immagine));
        }
        

        $vigneto->delete();

        return redirect()
            ->back()
            ->with('success', 'Vigneto eliminato correttamente.');
    }


}
