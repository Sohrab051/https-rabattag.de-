<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ClickLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'click_id', 'user_id', 'merchant_id', 'offer_id', 'ip_address', 'user_agent',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }

    public function conversion(): HasOne
    {
        return $this->hasOne(Conversion::class);
    }
}
