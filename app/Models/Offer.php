<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read Category|null $category
 */
class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'merchant_id', 'title_en', 'title_de', 'description_en', 'description_de',
        'discount_value', 'min_purchase_amount',
        'terms_en', 'terms_de', 'starts_at', 'expires_at', 'status',
        'is_featured', 'published_at', 'priority',
        'category_id', 'coupon_code', 'discount_type', 'deal_type', 'affiliate_url',
        'awin_deal_id', 'is_verified', 'needs_review', 'source', 'synced_at',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'is_verified' => 'boolean',
        'needs_review' => 'boolean',
        'synced_at' => 'datetime',
    ];

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * The category to display for this offer: an offer-level override if set,
     * otherwise falls back to the merchant's category.
     */
    public function resolvedCategory(): ?Category
    {
        return $this->category ?? $this->merchant?->category;
    }

    /**
     * The URL to send the visitor to: a deal-specific affiliate URL if set,
     * otherwise the merchant's general affiliate link.
     */
    public function resolvedAffiliateUrl(): ?string
    {
        return $this->affiliate_url ?? $this->merchant?->affiliate_link;
    }

    public function clickLogs(): HasMany
    {
        return $this->hasMany(ClickLog::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now())
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
            });
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function title(): string
    {
        return $this->{'title_' . app()->getLocale()} ?? $this->title_en;
    }

    public function description(): ?string
    {
        return $this->{'description_' . app()->getLocale()} ?? $this->description_en;
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
}
