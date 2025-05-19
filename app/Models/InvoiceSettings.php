<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceSettings extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_name',
        'address',
        'city',
        'phone',
        'email',
        'website',
        'registration_number',
        'tax_id',
        'invoice_notes',
        'payment_instructions',
        'bank_name',
        'bank_account',
        'bank_iban',
        'bank_bic',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Récupère les paramètres de facturation actifs.
     *
     * @return \App\Models\InvoiceSettings|null
     */
    public static function getActive()
    {
        return static::where('is_active', true)->first();
    }

    /**
     * Récupère les paramètres de facturation pour une commande.
     *
     * @return array<string, mixed>
     */
    public static function getInvoiceData()
    {
        $settings = static::getActive();

        if (!$settings) {
            // Valeurs par défaut si aucun paramètre n'est trouvé
            return [
                'name' => 'KLINKLIN',
                'address' => '123 Avenue Principale, Brazzaville, Congo',
                'phone' => '+242 06 123 45 67',
                'email' => 'contact@klinklin.com',
                'website' => 'www.klinklin.com',
                'logo' => null, // On n'utilise plus de logo image
                'registration_number' => 'BZ-ABC-12-2023',
                'tax_id' => 'CG98765432109',
                'payment_info' => [
                    'bank_name' => 'Banque Exemple',
                    'iban' => 'CG123456789012345678901234',
                    'bic' => 'EXAMPLEXXXXX',
                ],
                'notes' => 'TVA non applicable en vertu des dispositions fiscales en vigueur.',
            ];
        }

        // Utiliser uniquement le texte stylisé, pas d'image
        $logo = null;

        // Retourner les paramètres formatés
        return [
            'name' => $settings->company_name,
            'address' => $settings->address . ($settings->city ? ', ' . $settings->city : ''),
            'phone' => $settings->phone,
            'email' => $settings->email,
            'website' => $settings->website,
            'logo' => $logo, // Toujours null pour éviter les problèmes de performance
            'registration_number' => $settings->registration_number,
            'tax_id' => $settings->tax_id,
            'payment_info' => [
                'bank_name' => $settings->bank_name,
                'iban' => $settings->bank_iban,
                'bic' => $settings->bank_bic,
                'account' => $settings->bank_account,
                'instructions' => $settings->payment_instructions,
            ],
            'notes' => $settings->invoice_notes,
        ];
    }
}
