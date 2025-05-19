<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Log;

class TestMailController extends Controller
{
    public function testMail()
    {
        try {
            Mail::raw('Test email from KLINKLIN', function (Message $message) {
                $message->to(auth()->user()->email)
                        ->subject('Test Mail');
            });
            
            return 'Mail envoyé avec succès à ' . auth()->user()->email;
        } catch (\Exception $e) {
            Log::error('Erreur d\'envoi d\'email: ' . $e->getMessage());
            return 'Erreur lors de l\'envoi du mail: ' . $e->getMessage();
        }
    }
} 