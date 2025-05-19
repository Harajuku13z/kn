<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->where('is_admin', false);

        // Filtre par type d'utilisateur
        if ($request->has('type')) {
            switch ($request->type) {
                case 'new':
                    $query->where('created_at', '>=', now()->subDays(7));
                    break;
                case 'with_subscription':
                    $query->whereHas('subscriptions', function($q) {
                        $q->where('payment_status', 'paid')
                          ->where(function($q) {
                              $q->whereNull('expiration_date')
                                ->orWhere('expiration_date', '>', now());
                          });
                    });
                    break;
                case 'active':
                    $query->whereHas('orders', function($q) {
                        $q->where('created_at', '>=', now()->subDays(30));
                    });
                    break;
                case 'inactive':
                    $query->whereDoesntHave('orders', function($q) {
                        $q->where('created_at', '>=', now()->subDays(30));
                    });
                    break;
            }
        }

        // Filtre par recherche
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Tri
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $query->orderBy($sort, $direction);

        $users = $query->paginate(15)->withQueryString();

        // Statistiques
        $stats = [
            'total' => User::where('is_admin', false)->count(),
            'new' => User::where('is_admin', false)
                ->where('created_at', '>=', now()->subDays(7))
                ->count(),
            'with_subscription' => User::where('is_admin', false)
                ->whereHas('subscriptions', function($q) {
                    $q->where('payment_status', 'paid')
                      ->where(function($q) {
                          $q->whereNull('expiration_date')
                            ->orWhere('expiration_date', '>', now());
                      });
                })
                ->count(),
            'active' => User::where('is_admin', false)
                ->whereHas('orders', function($q) {
                    $q->where('created_at', '>=', now()->subDays(30));
                })
                ->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function show(User $user)
    {
        $user->load(['orders', 'subscriptions', 'quotaUsages']);
        
        return view('admin.users.show', compact('user'));
    }

    public function toggleStatus(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();

        return redirect()->back()->with('success', 'Statut de l\'utilisateur mis à jour avec succès.');
    }
} 