<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255','regex:/^[A-Za-zÀ-ÖØ-öø-ÿ\'\-\s]+$/u'],
            'username'       => ['required', 'string', 'max:255', 'unique:users,username'],
            'email'          => ['required', 'string', 'email','email:rfc,dns', 'max:255', 'unique:users,email'],
            'password'       => ['required', 'confirmed', Rules\Password::defaults()],

            'tipo_utente'    => ['required', 'in:persona,azienda'],
            'partita_iva'    => ['nullable', 'required_if:tipo_utente,azienda', 'digits:11'],
            'codice_fiscale' => ['nullable', 'string', 'size:16','regex:/^[A-Z]{6}[0-9]{2}[A-Z][0-9]{2}[A-Z][0-9]{3}[A-Z]$/i'],
            'data_di_nascita'=> ['nullable', 'date', 'before:today', 'after:1900-01-01'],
            'numero' => ['nullable','regex:/^\+?[0-9\s\-]{7,20}$/'],
        ], [
            // messaggi personalizzati opzionali
            'name.required' => 'Il nome è obbligatorio.',
            'name.regex'    => 'Il nome può contenere solo lettere, spazi, apostrofi e trattini.',

            'username.required' => 'Lo username è obbligatorio.',
            'username.unique'   => 'Questo username è già stato utilizzato.',

            'email.required' => 'L\'email è obbligatoria.',
            'email.email'    => 'Inserisci un indirizzo email valido.',
            'email.unique'   => 'Questa email è già registrata.',

            'password.required'  => 'La password è obbligatoria.',
            'password.confirmed' => 'Le password non coincidono.',
            'password.min'       => 'La password deve contenere almeno :min caratteri.',

            'tipo_utente.required' => 'Seleziona il tipo di utente.',

            'partita_iva.required_if' => 'La partita IVA è obbligatoria per le aziende.',
            'partita_iva.digits'      => 'La partita IVA deve contenere esattamente 11 cifre.',

            'codice_fiscale.string' => 'Il codice fiscale non è valido.',
            'codice_fiscale.size'   => 'Il codice fiscale deve contenere esattamente 16 caratteri.',
            'codice_fiscale.regex'  => 'Il codice fiscale non è valido.',


            'data_di_nascita.date'   => 'Inserisci una data di nascita valida.',
            'data_di_nascita.before' => 'La data di nascita deve essere nel passato.',
            'data_di_nascita.after'  => 'La data di nascita non può essere precedente al 1900.',

            'numero.regex' => 'Il numero di telefono deve contenere solo cifre e avere tra 7 e 15 numeri.',
        ]);

        $user = User::create([
            'name'           => $validated['name'],
            'username'       => $validated['username'],
            'email'          => $validated['email'],
            'password'       => $validated['password'],

            'tipo_utente'    => $validated['tipo_utente'],
            'partita_iva'    => $validated['partita_iva'] ?? null,
            'codice_fiscale' => $validated['codice_fiscale'] ?? null,
            'data_di_nascita'=> $validated['data_di_nascita'] ?? null,
            'numero'         => $validated['numero'] ?? null,

            // qui forziamo SEMPRE il ruolo a "utente"
            'ruolo'          => 'utente',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect('/');
    }
}

