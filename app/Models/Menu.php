<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $fillable = [
        'name',
    ];

    protected $casts = [
    ];

    public function recipes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class, 'menu_recipes')
                    ->withTimestamps();
    }

    public function meals(): HasMany
    {
        return $this->hasMany(Meal::class);
    }
}
