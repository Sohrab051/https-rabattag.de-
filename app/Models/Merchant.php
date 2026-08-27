<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Merchant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en', 'name_de', 'slug', 'logo', 'category_id',
        'description_en', 'description_de', 'website_url', 'affiliate_link',
        'commission_rate', 'api_provider', 'api_credentials',
        'status', 'is_featured',
        'awin_merchant_id', 'source', 'last_synced_at',
    ];

    protected $casts = [
        'api_credentials' => 'encrypted',
        'is_featured' => 'boolean',
        'commission_rate' => 'decimal:2',
        'last_synced_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }

    public function publishedOffers(): HasMany
    {
        return $this->offers()
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
            });
    }

    public function clickLogs(): HasMany
    {
        return $this->hasMany(ClickLog::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function name(): string
    {
        return $this->{'name_' . app()->getLocale()} ?? $this->name_en;
    }

    public function description(): ?string
    {
        return $this->{'description_' . app()->getLocale()} ?? $this->description_en;
    }
}
