<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\ProfileUpdated;
use App\Notifications\LoginNotification;
use App\Models\LoginHistory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TestNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:notification {user_id} {type=profile} {--field=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test sending notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $userId = $this->argument('user_id');
            $type = $this->argument('type');
            
            $user = User::find($userId);
            
            if (!$user) {
                $this->error('Utilisateur non trouvé');
                return 1;
            }
            
            if ($type === 'profile') {
                $this->info('Test de notification de mise à jour de profil...');
                $changedFields = [
                    'name' => $user->name . ' (test)',
                ];
                
                if ($this->option('field')) {
                    $fieldName = $this->option('field');
                    $changedFields = [];
                    
                    switch ($fieldName) {
                        case 'name':
                            $changedFields['name'] = $user->name . ' (modifié)';
                            break;
                        case 'email':
                            $changedFields['email'] = 'new.' . $user->email;
                            break;
                        case 'password':
                            $changedFields['password'] = '*******';
                            break;
                        case 'phone':
                            $changedFields['phone'] = '+242 06 123 45 67';
                            break;
                        case 'avatar':
                            $changedFields['avatar'] = 'Avatar modifié';
                            break;
                        default:
                            $changedFields[$fieldName] = 'Valeur modifiée';
                    }
                }
                
                $this->info('Envoi de notification avec champs: ' . json_encode($changedFields));
                $user->notify(new ProfileUpdated($changedFields));
                $this->info('Notification de profil envoyée avec succès à ' . $user->email);
                
                // Vérifier si la notification a été créée dans la base de données
                $notification = $user->notifications()
                    ->where('type', 'App\\Notifications\\ProfileUpdated')
                    ->latest()
                    ->first();
                    
                if ($notification) {
                    $this->info('Notification créée en base de données avec succès.');
                    $this->info('Données: ' . json_encode(json_decode($notification->data)));
                } else {
                    $this->error('La notification n\'a pas été créée en base de données!');
                }
            } elseif ($type === 'login') {
                $this->info('Test de notification de connexion...');
                
                // Créer un historique de connexion test
                $login = LoginHistory::create([
                    'user_id' => $user->id,
                    'ip_address' => '127.0.0.1',
                    'user_agent' => 'Test User Agent',
                    'device_type' => 'Desktop',
                    'login_at' => now()
                ]);
                
                $user->notify(new LoginNotification($login));
                $this->info('Notification de connexion envoyée avec succès à ' . $user->email);
            } else {
                $this->error('Type de notification inconnu. Utilisez "profile" ou "login"');
                return 1;
            }
            
            return 0;
        } catch (\Exception $e) {
            Log::error('Erreur de notification: ' . $e->getMessage());
            $this->error('Erreur: ' . $e->getMessage());
            return 1;
        }
    }
} 