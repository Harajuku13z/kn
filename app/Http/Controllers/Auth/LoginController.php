<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginHistory;
use App\Notifications\LoginNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    /**
     * Show the application login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
 
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Record login history
            $this->recordLoginHistory($request);
            
            // Send email notification about login
            $this->sendLoginNotification($request);
 
            return redirect()->intended('dashboard');
        }
 
        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    /**
     * Record login history for the user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function recordLoginHistory(Request $request)
    {
        $user = Auth::user();
        $ipAddress = $request->ip();
        $location = $this->getLocationFromIp($ipAddress);
        
        LoginHistory::create([
            'user_id' => $user->id,
            'ip_address' => $ipAddress,
            'user_agent' => $request->userAgent(),
            'device_type' => LoginHistory::determineDeviceType($request->userAgent()),
            'location' => $location,
            'login_at' => now()
        ]);
    }
    
    /**
     * Get location information from IP address
     * 
     * @param string $ip
     * @return string
     */
    protected function getLocationFromIp($ip)
    {
        // Don't attempt to geolocate local/private IPs
        if (in_array($ip, ['127.0.0.1', 'localhost', '::1']) || 
            filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
            return 'Local';
        }
        
        try {
            // Use ip-api.com free service (no API key required)
            $response = Http::get("http://ip-api.com/json/{$ip}");
            
            if ($response->successful()) {
                $data = $response->json();
                if ($data['status'] === 'success') {
                    return $data['country'] . ($data['city'] ? ', ' . $data['city'] : '');
                }
            }
            
            return 'Localisation inconnue';
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la géolocalisation IP: ' . $e->getMessage());
            return 'Localisation inconnue';
        }
    }
    
    /**
     * Send login notification email
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function sendLoginNotification(Request $request)
    {
        try {
            $user = Auth::user();
            $loginHistory = $user->loginHistory()->latest()->first();
            
            if ($loginHistory) {
                $user->notify(new LoginNotification($loginHistory));
            }
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'envoi de la notification de connexion: ' . $e->getMessage());
        }
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();
 
        $request->session()->invalidate();
 
        $request->session()->regenerateToken();
 
        return redirect('/');
    }
}
