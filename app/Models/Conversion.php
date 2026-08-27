<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Conversion extends Model
{
    use HasFactory;

    protected $fillable = [
        'click_log_id', 'order_amount', 'commission_amount',
        'status', 'external_reference', 'confirmed_at',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
    ];

    public function clickLog(): BelongsTo
    {
        return $this->belongsTo(ClickLog::class);
    }
}
