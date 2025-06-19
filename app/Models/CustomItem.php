<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'quantity',
        'unit',
        'is_saved',
        'usage_count'
    ];

    protected $casts = [
        'is_saved' => 'boolean',
        'usage_count' => 'integer'
    ];

    /**
     * Incrémente le compteur d'utilisation
     */
    public function incrementUsage()
    {
        $this->increment('usage_count');
    }

    /**
     * Récupère les éléments les plus utilisés
     */
    public static function getMostUsed($limit = 10)
    {
        return static::where('is_saved', true)
                    ->orderBy('usage_count', 'desc')
                    ->limit($limit)
                    ->get();
    }

    /**
     * Récupère les éléments suggérés basés sur l'usage
     */
    public static function getSuggestedItems($limit = 5)
    {
        return static::where('is_saved', true)
                    ->where('usage_count', '>', 0)
                    ->orderBy('usage_count', 'desc')
                    ->limit($limit)
                    ->get();
    }
}
