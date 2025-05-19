<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SupportTicket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'subject',
        'status',
        'priority',
        'category',
        'reference_number',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    // Constantes pour les statuts
    const STATUS_OPEN = 'open';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_WAITING_USER = 'waiting_user';
    const STATUS_CLOSED = 'closed';

    // Constantes pour les priorités
    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';

    // Constantes pour les catégories
    const CATEGORY_GENERAL = 'general';
    const CATEGORY_ACCOUNT = 'account';
    const CATEGORY_ORDERS = 'orders';
    const CATEGORY_PAYMENT = 'payment';
    const CATEGORY_SUBSCRIPTION = 'subscription';
    const CATEGORY_TECHNICAL = 'technical';

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec les messages
     */
    public function messages()
    {
        return $this->hasMany(SupportMessage::class);
    }

    /**
     * Relation avec le dernier message
     */
    public function latestMessage()
    {
        return $this->hasOne(SupportMessage::class)->latest();
    }

    /**
     * Génère un numéro de référence unique
     */
    public static function generateReferenceNumber()
    {
        $prefix = 'TKT-';
        $random = strtoupper(Str::random(8));
        
        // Vérifier que le numéro de référence est unique
        while (self::where('reference_number', $prefix . $random)->exists()) {
            $random = strtoupper(Str::random(8));
        }
        
        return $prefix . $random;
    }

    /**
     * Vérifie si le ticket est ouvert
     */
    public function isOpen()
    {
        return $this->status !== self::STATUS_CLOSED;
    }

    /**
     * Vérifie si le ticket est fermé
     */
    public function isClosed()
    {
        return $this->status === self::STATUS_CLOSED;
    }

    /**
     * Ferme le ticket
     */
    public function close()
    {
        $this->status = self::STATUS_CLOSED;
        $this->closed_at = now();
        $this->save();
    }

    /**
     * Rouvre le ticket
     */
    public function reopen()
    {
        $this->status = self::STATUS_OPEN;
        $this->closed_at = null;
        $this->save();
    }

    /**
     * Retourne le libellé du statut
     */
    public function getStatusLabel()
    {
        $labels = [
            self::STATUS_OPEN => 'Ouvert',
            self::STATUS_IN_PROGRESS => 'En traitement',
            self::STATUS_WAITING_USER => 'En attente de réponse',
            self::STATUS_CLOSED => 'Fermé',
        ];
        
        return $labels[$this->status] ?? $this->status;
    }

    /**
     * Retourne le libellé de la priorité
     */
    public function getPriorityLabel()
    {
        $labels = [
            self::PRIORITY_LOW => 'Basse',
            self::PRIORITY_MEDIUM => 'Moyenne',
            self::PRIORITY_HIGH => 'Haute',
            self::PRIORITY_URGENT => 'Urgente',
        ];
        
        return $labels[$this->priority] ?? $this->priority;
    }

    /**
     * Retourne le libellé de la catégorie
     */
    public function getCategoryLabel()
    {
        $labels = [
            self::CATEGORY_GENERAL => 'Question générale',
            self::CATEGORY_ACCOUNT => 'Compte',
            self::CATEGORY_ORDERS => 'Commandes',
            self::CATEGORY_PAYMENT => 'Paiement',
            self::CATEGORY_SUBSCRIPTION => 'Abonnement',
            self::CATEGORY_TECHNICAL => 'Problème technique',
        ];
        
        return $labels[$this->category] ?? $this->category;
    }
} 