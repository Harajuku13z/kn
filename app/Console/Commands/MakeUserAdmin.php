<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeUserAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:make-admin {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Définit un utilisateur comme administrateur';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("L'utilisateur avec l'email {$email} n'existe pas.");
            return 1;
        }

        $user->is_admin = true;
        $user->save();

        $this->info("L'utilisateur {$email} est maintenant un administrateur.");
        return 0;
    }
} 