<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pressing;
use App\Models\PressingService;
use Illuminate\Http\Request;

class PressingServiceController extends Controller
{
    /**
     * Récupère tous les services actifs pour un pressing donné.
     *
     * @param int $pressingId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getServices($pressingId)
    {
        try {
            $pressing = Pressing::findOrFail($pressingId);
            
            // Récupère uniquement les services disponibles
            $services = $pressing->services()
                ->where('is_available', true)
                ->orderBy('category')
                ->orderBy('name')
                ->get();
            
            // Ajouter des attributs formatés pour l'affichage
            $services->each(function ($service) {
                $service->formatted_price = number_format($service->price, 0, ',', ' ') . ' FCFA';
                
                // Formatage du temps estimé
                if ($service->estimated_time) {
                    if ($service->estimated_time < 24) {
                        $service->formatted_time = $service->estimated_time . ' heure(s)';
                    } else {
                        $days = floor($service->estimated_time / 24);
                        $hours = $service->estimated_time % 24;
                        
                        if ($hours == 0) {
                            $service->formatted_time = $days . ' jour(s)';
                        } else {
                            $service->formatted_time = $days . ' jour(s) et ' . $hours . ' heure(s)';
                        }
                    }
                } else {
                    $service->formatted_time = null;
                }
            });
            
            return response()->json([
                'success' => true,
                'pressing' => [
                    'id' => $pressing->id,
                    'name' => $pressing->name,
                    'address' => $pressing->address
                ],
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
