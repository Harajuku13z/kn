<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class TestMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test mail configuration by sending a test email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info("Tentative d'envoi d'un email de test à : {$email}");
        
        try {
            Mail::raw('Ceci est un email de test depuis KLINKLIN pour vérifier la configuration du mail.', function($message) use ($email) {
                $message->to($email)
                    ->subject('Test de configuration email KLINKLIN');
            });
            
            $this->info("Email envoyé avec succès!");
            Log::info("Email de test envoyé à {$email}");
        } catch (\Exception $e) {
            $this->error("Erreur lors de l'envoi de l'email : {$e->getMessage()}");
            Log::error("Erreur lors de l'envoi de l'email de test : {$e->getMessage()}");
            Log::error("Trace : " . $e->getTraceAsString());
        }
        
        $this->info("Configuration mail actuelle :");
        $this->table(
            ['Clé', 'Valeur'],
            [
                ['MAIL_MAILER', config('mail.default')],
                ['MAIL_HOST', config('mail.mailers.' . config('mail.default') . '.host')],
                ['MAIL_PORT', config('mail.mailers.' . config('mail.default') . '.port')],
                ['MAIL_USERNAME', config('mail.mailers.' . config('mail.default') . '.username')],
                ['MAIL_ENCRYPTION', config('mail.mailers.' . config('mail.default') . '.encryption')],
                ['MAIL_FROM_ADDRESS', config('mail.from.address')],
                ['MAIL_FROM_NAME', config('mail.from.name')],
            ]
        );
    }
} 