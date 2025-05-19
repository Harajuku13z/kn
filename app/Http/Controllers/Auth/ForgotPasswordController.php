<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\View\View
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        $user = \App\Models\User::where('email', $request->email)->first();
        
        if ($user) {
            $token = Password::createToken($user);
            
            $resetUrl = URL::to(config('app.url').'/password/reset/'.$token.'?email='.urlencode($user->email));
            
            try {
                Mail::to($user->email)->send(new PasswordReset($resetUrl, $user->email));
                \Log::info('Email de réinitialisation de mot de passe envoyé', ['email' => $user->email]);
            } catch (\Exception $e) {
                \Log::error('Erreur lors de l\'envoi de l\'email de réinitialisation: ' . $e->getMessage());
            }
        }

        return back()->with('status', 'Si cette adresse e-mail existe dans notre système, un lien de réinitialisation vous a été envoyé.');
    }
} 