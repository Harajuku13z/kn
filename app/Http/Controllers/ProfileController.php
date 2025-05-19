<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Notifications\ProfileUpdated;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;
use App\Mail\EmailChangeVerification;
use Carbon\Carbon;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(Request $request): View
    {
        return view('profile.index', [
            'user' => $request->user(),
            'loginHistory' => $request->user()->getRecentLoginHistory(5)
        ]);
    }
    
    /**
     * Display the user's profile form - pour compatibilité arrière.
     */
    public function edit(Request $request): View
    {
        return $this->index($request);
    }

    /**
     * Display form to edit name
     */
    public function editName(Request $request): View
    {
        return view('profile.edit-name', [
            'user' => $request->user()
        ]);
    }
    
    /**
     * Display form to edit email
     */
    public function editEmail(Request $request): View
    {
        return view('profile.edit-email', [
            'user' => $request->user()
        ]);
    }
    
    /**
     * Display form to edit phone
     */
    public function editPhone(Request $request): View
    {
        return view('profile.edit-phone', [
            'user' => $request->user()
        ]);
    }
    
    /**
     * Display form to edit password
     */
    public function editPassword(Request $request): View
    {
        return view('profile.edit-password', [
            'user' => $request->user()
        ]);
    }
    
    /**
     * Display form to edit avatar
     */
    public function editAvatar(Request $request): View
    {
        return view('profile.edit-avatar', [
            'user' => $request->user()
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request)
    {
        try {
            $user = $request->user();
            $changedFields = [];
            
            // Update basic info
            if ($request->has('name') && $user->name !== $request->name) {
                $user->name = $request->name;
                $changedFields['name'] = $request->name;
                \Log::info('Champ name modifié:', ['old' => $user->getOriginal('name'), 'new' => $request->name]);
            }
            
            if ($request->has('email') && $user->email !== $request->email) {
                $oldEmail = $user->email; // Stocker l'ancien email
                $user->email = $request->email;
                $user->email_verified_at = null;
                $changedFields['email'] = $request->email;
                
                // Send verification email
                try {
                    $verificationUrl = URL::temporarySignedRoute(
                        'verification.verify',
                        Carbon::now()->addMinutes(60),
                        [
                            'id' => $user->getKey(),
                            'hash' => sha1($user->getEmailForVerification()),
                        ]
                    );
                    
                    // Utiliser la nouvelle notification pour le changement d'email
                    $user->notify(new \App\Notifications\EmailChangeRequested($oldEmail, $request->email, $verificationUrl));
                    \Log::info('Notification de changement d\'email envoyée', ['old' => $oldEmail, 'new' => $request->email]);
                } catch (\Exception $e) {
                    \Log::error('Erreur lors de l\'envoi de la notification de changement d\'email: ' . $e->getMessage());
                }
            }
            
            if ($request->has('phone') && $user->phone !== $request->phone) {
                $user->phone = $request->phone;
                $changedFields['phone'] = $request->phone;
            }
            
            if ($request->has('address') && $user->address !== $request->address) {
                $user->address = $request->address;
                $changedFields['address'] = $request->address;
            }
            
            // Handle avatar selection
            if ($request->has('avatar_type')) {
                $avatarType = $request->input('avatar_type');
                $avatarData = [
                    'avatar_type' => $avatarType
                ];
                
                // Add specific avatar data based on type
                if ($avatarType === 'gravatar') {
                    $avatarData['gravatar_style'] = $request->input('gravatar_style', 'retro');
                } elseif ($avatarType === 'icon') {
                    $avatarData['icon_type'] = $request->input('icon_type', 'person');
                }
                
                // Store avatar settings
                $user->avatar_settings = $avatarData;
                $changedFields['avatar'] = 'Paramètres de l\'avatar';
                
                // Save changes immediately
                $user->save();
                
                // Send notification if fields have changed
                if (count($changedFields) > 0) {
                    try {
                        $this->createProfileNotification($user, $changedFields);
                    } catch (\Exception $e) {
                        \Log::error('Erreur lors de l\'envoi de la notification de mise à jour du profil: ' . $e->getMessage());
                    }
                }
                
                // If this is AJAX request (avatar update via form), return JSON response
                if ($request->ajax() || $request->wantsJson()) {
                    // Recalculate avatar URL after saving
                    $avatarUrl = null;
                    if ($avatarType === 'gravatar') {
                        $style = $request->input('gravatar_style', 'retro');
                        $avatarUrl = 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($user->email))) . '?s=200&d=' . $style;
                    }
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Avatar mis à jour avec succès.',
                        'avatar_settings' => $avatarData,
                        'avatar_url' => $avatarUrl
                    ]);
                }
                
                return Redirect::route('profile.index')->with('status', 'profile-updated');
            }
            
            // Handle password update
            if ($request->filled('password')) {
                if (!$request->filled('current_password') || !Hash::check($request->current_password, $user->password)) {
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Le mot de passe actuel est incorrect.',
                            'errors' => ['current_password' => ['Le mot de passe actuel est incorrect.']]
                        ], 422);
                    }
                    
                    return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
                }
                
                $user->password = Hash::make($request->password);
                $changedFields['password'] = '*******';
                
                // Envoyer un email de notification de changement de mot de passe
                try {
                    \Mail::to($user->email)->send(new \App\Mail\PasswordChanged($user));
                    \Log::info('Email de notification de changement de mot de passe envoyé', ['email' => $user->email]);
                } catch (\Exception $e) {
                    \Log::error('Erreur lors de l\'envoi de l\'email de notification de changement de mot de passe: ' . $e->getMessage());
                }
            }
            
            // Save the user model with all changes
            $user->save();
            
            // Send notification if fields have changed
            if (count($changedFields) > 0) {
                try {
                    \Log::info('Envoi de la notification ProfileUpdated', ['fields' => $changedFields]);
                    // Créer directement la notification dans la base de données
                    $this->createProfileNotification($user, $changedFields);
                    
                    // Préparation du message de succès
                    $changedFieldsCount = count($changedFields);
                    $message = $changedFieldsCount > 1 
                        ? 'Vos informations de profil ont été mises à jour avec succès.' 
                        : $this->getUpdateSuccessMessage($changedFields);
                    
                    if ($request->ajax() || $request->wantsJson()) {
                        session()->flash('toast', [
                            'type' => 'success',
                            'title' => 'Profil mis à jour',
                            'message' => $message
                        ]);
                    }
                } catch (\Exception $e) {
                    \Log::error('Erreur lors de l\'envoi de la notification de mise à jour du profil: ' . $e->getMessage());
                }
            }
            
            // If the request is AJAX, return a JSON response
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Profil mis à jour avec succès.',
                    'user' => $user,
                    'changedFields' => $changedFields
                ]);
            }

            return Redirect::route('profile.index')->with('status', 'profile-updated');
        } catch (\Exception $e) {
            \Log::error('Erreur de mise à jour du profil: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur: ' . $e->getMessage()
                ], 500);
            }
            
            return Redirect::route('profile.index')->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Get a descriptive success message based on what was updated
     * 
     * @param array $changedFields Array of changed fields
     * @return string Success message
     */
    private function getUpdateSuccessMessage(array $changedFields) 
    {
        $field = array_keys($changedFields)[0];
        
        switch ($field) {
            case 'name':
                return 'Votre nom a été mis à jour avec succès.';
            case 'email':
                return 'Votre adresse email a été modifiée. Veuillez vérifier votre boîte de réception pour confirmer ce changement.';
            case 'password':
                return 'Votre mot de passe a été modifié avec succès.';
            case 'phone':
                return 'Votre numéro de téléphone a été mis à jour.';
            case 'avatar':
                return 'Votre avatar a été mis à jour.';
            default:
                return 'Votre profil a été mis à jour avec succès.';
        }
    }

    /**
     * Create a profile update notification directly in the database
     * and send email notification
     * 
     * @param \App\Models\User $user User to notify
     * @param array $changedFields Changed fields
     * @return void
     */
    private function createProfileNotification($user, array $changedFields): void
    {
        try {
            // Créer un titre et un message basés sur les champs modifiés
            $title = 'Mise à jour du profil';
            $message = 'Votre profil a été mis à jour';
            
            if (count($changedFields) === 1) {
                // Message spécifique pour un seul champ modifié
                $field = array_keys($changedFields)[0];
                
                if ($field === 'name') {
                    $title = 'Nom mis à jour';
                    $message = 'Votre nom a été modifié en ' . $changedFields[$field];
                } else if ($field === 'email') {
                    $title = 'Email mis à jour';
                    $message = 'Votre adresse email a été modifiée en ' . $changedFields[$field];
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
            
            // Données pour la notification
            $notificationData = [
                'title' => $title,
                'message' => $message,
                'changed_fields' => $changedFields,
                'icon' => 'bi-person-check-fill',
                'color' => 'primary',
                'type' => 'profile',
                'action_url' => route('profile.index'),
                'action_text' => 'Voir mon profil'
            ];
            
            // Insérer directement dans la base de données
            \DB::table('notifications')->insert([
                'id' => \Illuminate\Support\Str::uuid(),
                'type' => 'App\\Notifications\\ProfileUpdated',
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id' => $user->id,
                'data' => json_encode($notificationData),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            \Log::info('Notification de profil créée en base de données', ['data' => $notificationData]);
            
            // Envoyer l'email de notification
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
            foreach ($changedFields as $field => $value) {
                $translatedField = $fieldTranslations[$field] ?? ucfirst($field);
                $displayValue = ($field === 'password') ? '••••••••' : $value;
                $formattedChangedFields[$translatedField] = $displayValue;
            }
            
            \Mail::send('emails.profile-updated', [
                'user' => $user, 
                'changedFields' => $formattedChangedFields
            ], function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Votre profil KLINKLIN a été modifié');
            });
            
            \Log::info('Email de notification de profil envoyé', ['email' => $user->email]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création de la notification en base de données: ' . $e->getMessage());
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Display the login history page.
     */
    public function loginHistory(Request $request): View
    {
        $loginHistory = $request->user()->loginHistory()
            ->orderBy('login_at', 'desc')
            ->paginate(15);
            
        return view('profile.login-history', [
            'loginHistory' => $loginHistory
        ]);
    }
}
