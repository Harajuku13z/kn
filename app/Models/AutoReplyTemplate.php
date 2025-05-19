<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoReplyTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'subject',
        'content',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Retourne les templates actifs
     */
    public static function active()
    {
        return self::where('is_active', true);
    }

    /**
     * Retourne les templates pour une catégorie spécifique
     */
    public static function forCategory($category)
    {
        return self::where('category', $category)->where('is_active', true);
    }

    /**
     * Parse le contenu du template avec les variables fournies
     */
    public function parseContent(array $variables = [])
    {
        $content = $this->content;
        
        foreach ($variables as $key => $value) {
            $content = str_replace('{{' . $key . '}}', $value, $content);
        }
        
        return $content;
    }

    /**
     * Remplace les variables dans le sujet du template
     * 
     * @param array $variables Les variables à remplacer
     * @return string
     */
    public function parseSubject($variables = [])
    {
        $subject = $this->subject;
        
        // Variables par défaut
        $defaultVars = [
            'date' => now()->format('d/m/Y'),
            'app_name' => config('app.name'),
        ];
        
        $variables = array_merge($defaultVars, $variables);
        
        foreach ($variables as $key => $value) {
            $subject = str_replace('{' . $key . '}', $value, $subject);
        }
        
        return $subject;
    }
} 