<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class RegisterController extends Controller
{
    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->firstname . ' ' . $request->lastname,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        // Générer l'URL de vérification
        $verificationUrl = $this->generateVerificationUrl($user);
        
        // Envoyer l'email de vérification
        Mail::to($user->email)->send(new VerifyEmail($user, $verificationUrl));

        event(new Registered($user));

        Auth::login($user);

        return redirect('/dashboard')->with('verification_email_sent', true);
    }

    /**
     * Vérifie l'email de l'utilisateur.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @param  string  $hash
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return redirect('/login')->with('error', 'Lien de vérification invalide.');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect('/dashboard')->with('success', 'Votre adresse email est déjà vérifiée.');
        }

        $user->markEmailAsVerified();

        // Rediriger vers la page de création d'adresse
        return redirect()->route('addresses.create')
                         ->with('success', 'Votre adresse email a été vérifiée avec succès. Veuillez maintenant ajouter une adresse pour la livraison ou la collecte de votre linge.');
    }

    /**
     * Renvoie l'email de vérification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resend(Request $request)
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            return redirect('/dashboard')->with('success', 'Votre adresse email est déjà vérifiée.');
        }

        // Générer l'URL de vérification
        $verificationUrl = $this->generateVerificationUrl($user);
        
        // Envoyer l'email de vérification
        Mail::to($user->email)->send(new VerifyEmail($user, $verificationUrl));

        return redirect('/dashboard')->with('verification_email_sent', true);
    }

    /**
     * Génère l'URL de vérification pour un utilisateur.
     *
     * @param  \App\Models\User  $user
     * @return string
     */
    protected function generateVerificationUrl(User $user)
    {
        $verifyUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            [
                'id' => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );
        
        return $verifyUrl;
    }
}
