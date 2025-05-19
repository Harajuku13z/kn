<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pressing;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Récupère tous les services actifs pour un pressing donné.
     *
     * @param int $pressingId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPressingServices($pressingId)
    {
        try {
            $pressing = Pressing::findOrFail($pressingId);
            
            // Récupère uniquement les services disponibles
            $services = Service::where('pressing_id', $pressingId)
                ->where('is_active', true)
                ->with('category')
                ->orderBy('category_id')
                ->orderBy('name')
                ->get();
            
            // Ajouter des attributs formatés pour l'affichage
            $services->each(function ($service) {
                $service->formatted_price = number_format($service->price, 0, ',', ' ') . ' FCFA';
                $service->category = $service->category ? $service->category->name : 'Non catégorisé';
                
                // Formatage du temps estimé
                if ($service->estimated_time) {
                    $service->formatted_time = $service->estimated_time;
                } else {
                    $service->formatted_time = '48h-72h';
                }
            });
            
            // Récupérer les catégories pour l'affichage
            $categories = ServiceCategory::orderBy('name')->get();
            
            return response()->json([
                'success' => true,
                'pressing' => [
                    'id' => $pressing->id,
                    'name' => $pressing->name,
                    'address' => $pressing->address,
                    'neighborhood' => $pressing->neighborhood,
                ],
                'categories' => $categories,
                'services' => $services
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de récupérer les services: ' . $e->getMessage()
            ], 404);
        }
    }
} 