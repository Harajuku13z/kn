<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class ProfileUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    private $changedFields;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $changedFields = [])
    {
        $this->changedFields = $changedFields;
        Log::info('ProfileUpdated notification created', ['fields' => $changedFields]);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Traduire les noms des champs en français pour l'affichage
        $fieldTranslations = [
            'name' => 'Nom complet',
            'phone' => 'Numéro de téléphone',
            'address' => 'Adresse',
            'password' => 'Mot de passe',
            'profile_image' => 'Photo de profil',
            'avatar' => 'Avatar',
            'email' => 'Adresse email'
        ];
        
        // Préparer le tableau des champs modifiés avec les traductions
        $formattedChangedFields = [];
        foreach ($this->changedFields as $field => $value) {
            $translatedField = $fieldTranslations[$field] ?? ucfirst($field);
            $displayValue = ($field === 'password') ? '••••••••' : $value;
            $formattedChangedFields[$translatedField] = $displayValue;
        }
        
        return (new MailMessage)
            ->subject('Votre profil KLINKLIN a été modifié')
            ->view('emails.profile-updated', [
                'user' => $notifiable, 
                'changedFields' => $formattedChangedFields
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        Log::info('ProfileUpdated toArray called', ['fields' => $this->changedFields]);
        
        // Créer un titre et un message basés sur les champs modifiés
        $title = 'Mise à jour du profil';
        $message = 'Votre profil a été mis à jour';
        
        if (count($this->changedFields) === 1) {
            // Message spécifique pour un seul champ modifié
            $field = array_keys($this->changedFields)[0];
            
            if ($field === 'name') {
                $title = 'Nom mis à jour';
                $message = 'Votre nom a été modifié en ' . $this->changedFields[$field];
            } else if ($field === 'email') {
                $title = 'Email mis à jour';
                $message = 'Votre adresse email a été modifiée en ' . $this->changedFields[$field];
            } else if ($field === 'password') {
                $title = 'Mot de passe modifié';
                $message = 'Votre mot de passe a été modifié avec succès';
            } else if ($field === 'phone') {
                $title = 'Téléphone mis à jour';
                $message = 'Votre numéro de téléphone a été modifié';
            } else if ($field === 'avatar') {
                $title = 'Avatar mis à jour';
                $message = 'Votre avatar a été mis à jour';
            }
        }
        
        // Retourner les données formatées pour l'affichage dans les notifications
        $data = [
            'title' => $title,
            'message' => $message,
            'changed_fields' => $this->changedFields,
            'icon' => 'bi-person-check-fill',
            'color' => 'primary',
            'type' => 'profile',
            'action_url' => route('profile.index'),
            'action_text' => 'Voir mon profil'
        ];
        
        Log::info('ProfileUpdated toArray returning', $data);
        
        return $data;
    }
}