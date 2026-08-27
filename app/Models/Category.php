<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en', 'name_de', 'slug', 'icon', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function merchants(): HasMany
    {
        return $this->hasMany(Merchant::class);
    }

    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }

    public function pivotMerchants(): BelongsToMany
    {
        return $this->belongsToMany(Merchant::class);
    }

    public function name(): string
    {
        return $this->{'name_' . app()->getLocale()} ?? $this->name_en;
    }
}
