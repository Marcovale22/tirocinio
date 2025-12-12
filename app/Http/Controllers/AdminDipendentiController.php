<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminDipendentiController extends Controller
{   
     /*---------SEZIONE DIPENDENTI------*/
    public function getDipendenti(){

        $dipendenti = User::where('ruolo', 'staff')->get();
        return view('admin.dipendenti',compact('dipendenti'));
    }

    public function destroyDipendente(User $user)
    {
       
        if ($user->ruolo !== 'staff') {   
            return redirect()
                ->route('dipendenti')
                ->with('error', 'Non puoi eliminare questo utente.');
        }

        $user->delete();

        return redirect()
            ->route('dipendenti.index')
            ->with('success', 'Dipendente eliminato correttamente.');
    }

    public function storeDipendente(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'username'       => 'required|string|max:255|unique:users,username',
            'email'          => ['required', 'string', 'email','email:rfc,dns', 'max:255', 'unique:users,email'],
            'data_di_nascita'=> ['nullable', 'date', 'before:today', 'after:1900-01-01'],
            'password'       => 'required|string|min:8|confirmed', // password + password_confirmation
        ],[
            'name.required' => 'Il nome è obbligatorio.',

            'username.required' => 'Lo username è obbligatorio.',
            'username.unique'   => 'Questo username è già stato utilizzato.',

            'email.required' => 'L\'email è obbligatoria.',
            'email.email'    => 'Inserisci un indirizzo email valido.',
            'email.unique'   => 'Questa email è già registrata.',

            'password.required'  => 'La password è obbligatoria.',
            'password.confirmed' => 'Le password non coincidono.',
            'password.min'       => 'La password deve contenere almeno :min caratteri.',

            'data_di_nascita.date'   => 'Inserisci una data di nascita valida.',
            'data_di_nascita.before' => 'La data di nascita deve essere nel passato.',
            'data_di_nascita.after'  => 'La data di nascita non può essere precedente al 1900.',

            'numero.regex' => 'Il numero di telefono deve contenere solo cifre e avere tra 7 e 15 numeri.',
        ]);

        User::create([
            'name'           => $data['name'],
            'username'       => $data['username'],
            'email'          => $data['email'],
            'data_di_nascita'=> $data['data_di_nascita'] ?? null,
            'password'       => Hash::make($data['password']),
            'ruolo'           => 'staff', // fisso staff
        ]);

        return redirect()
            ->route('dipendenti.index')
            ->with('success', 'Dipendente creato correttamente.');
    }

    



    public function updateDipendente(Request $request, User $user)
    {
        if ($user->ruolo !== 'staff') { 
            return redirect()
                ->route('dipendenti') 
                ->with('error', 'Non puoi modificare questo utente.');
        }

        $data = $request->validate([
            'name'            => 'required|string|max:255',
            'username'        => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'email'           => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'data_di_nascita' => [
                'nullable',
                'date_format:Y-m-d',
                'before_or_equal:' . now()->toDateString(),
                'after:1900-01-01',
            ],
            'password'        => 'nullable|string|min:8|confirmed',
        ], [
            'email.unique'    => 'Questa e-mail è già registrata.',
            'username.unique' => 'Questo username è già utilizzato.',
            'data_di_nascita.date_format'     => 'Inserisci una data di nascita valida (formato AAAA-MM-GG).',
            'data_di_nascita.before_or_equal' => 'La data di nascita deve essere nel passato.',
            'data_di_nascita.after'           => 'La data di nascita non può essere precedente al 1900.',
        ]);


        $user->name            = $data['name'];
        $user->username        = $data['username'];
        $user->email           = $data['email'];
        $user->data_di_nascita = $data['data_di_nascita'] ?? null;

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return redirect()
            ->route('dipendenti.index') 
            ->with('success', 'Dipendente aggiornato correttamente.');
    }


    



}
