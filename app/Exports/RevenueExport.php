<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RevenueExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $data = [];
        $currentDate = clone $this->startDate;
        $totalOrders = 0;
        $totalSubscriptions = 0;
        $totalCash = 0;
        $grandTotal = 0;
        
        while ($currentDate <= $this->endDate) {
            $dayRevenue = Order::where('status', 'delivered')
                ->whereDate('created_at', $currentDate)
                ->sum(DB::raw('COALESCE(final_price, estimated_price, 0) + COALESCE(pickup_fee, 0) + COALESCE(drop_fee, 0)'));
                
            $subscriptionRevenue = Subscription::where('payment_status', 'paid')
                ->whereDate('created_at', $currentDate)
                ->sum('amount_paid');
                
            $cashRevenue = Order::whereNull('quota_id')
                ->where('payment_method', 'cash')
                ->where('payment_status', 'paid')
                ->whereDate('created_at', $currentDate)
                ->sum(DB::raw('COALESCE(final_price, estimated_price, 0) + COALESCE(pickup_fee, 0) + COALESCE(drop_fee, 0)'));
                
            $total = $dayRevenue + $subscriptionRevenue + $cashRevenue;
            
            $data[] = [
                'date' => $currentDate->format('d/m/Y'),
                'orders' => $dayRevenue,
                'subscriptions' => $subscriptionRevenue,
                'cash' => $cashRevenue,
                'total' => $total
            ];
            
            $totalOrders += $dayRevenue;
            $totalSubscriptions += $subscriptionRevenue;
            $totalCash += $cashRevenue;
            $grandTotal += $total;
            
            $currentDate->addDay();
        }
        
        // Ajouter une ligne pour les totaux
        $data[] = [
            'date' => 'TOTAL',
            'orders' => $totalOrders,
            'subscriptions' => $totalSubscriptions,
            'cash' => $totalCash,
            'total' => $grandTotal
        ];
        
        return collect($data);
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        return [
            $row['date'],
            $row['orders'],
            $row['subscriptions'],
            $row['cash'],
            $row['total']
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Date',
            'Commandes livrées (FCFA)',
            'Abonnements payés (FCFA)',
            'Paiements en cash (FCFA)',
            'Total (FCFA)'
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Chiffre d\'affaires';
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style pour l'en-tête
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'color' => ['rgb' => '4e73df']],
            ],
            // Style pour les lignes de données
            'A2:J' . ($this->collection()->count() + 1) => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => 'DDDDDD'],
                    ],
                ],
            ],
        ];
    }
} 