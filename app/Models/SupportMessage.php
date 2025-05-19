<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupportMessage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'support_ticket_id',
        'user_id',
        'message',
        'is_from_admin',
        'is_auto_reply',
        'attachments',
        'read_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'read_at' => 'datetime',
        'is_from_admin' => 'boolean',
        'is_auto_reply' => 'boolean',
        'attachments' => 'array',
    ];

    /**
     * Relation avec le ticket de support
     */
    public function ticket()
    {
        return $this->belongsTo(SupportTicket::class, 'support_ticket_id');
    }

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Vérifie si le message a été lu
     */
    public function isRead()
    {
        return $this->read_at !== null;
    }

    /**
     * Marque le message comme lu
     */
    public function markAsRead()
    {
        if (!$this->isRead()) {
            $this->read_at = now();
            $this->save();
        }
        
        return $this;
    }

    /**
     * Marque le message comme non lu
     */
    public function markAsUnread()
    {
        $this->read_at = null;
        $this->save();
        
        return $this;
    }

    /**
     * Vérifie si le message est de l'administrateur
     */
    public function isFromAdmin()
    {
        return $this->is_from_admin;
    }

    /**
     * Vérifie si le message est une réponse automatique
     */
    public function isAutoReply()
    {
        return $this->is_auto_reply;
    }

    /**
     * Vérifie si le message a des pièces jointes
     */
    public function hasAttachments()
    {
        return !empty($this->attachments);
    }

    /**
     * Retourne le nombre de pièces jointes
     */
    public function attachmentsCount()
    {
        return count($this->attachments ?? []);
    }

    /**
     * Retourne un extrait du message
     */
    public function excerpt($length = 100)
    {
        return \Str::limit(strip_tags($this->message), $length);
    }
} 