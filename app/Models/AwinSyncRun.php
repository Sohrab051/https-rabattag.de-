<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AwinSyncRun extends Model
{
    protected $fillable = [
        'started_at',
        'finished_at',
        'status',
        'merchants_created',
        'merchants_updated',
        'offers_created',
        'offers_updated',
        'offers_skipped',
        'errors_count',
        'error_message',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
            'status' => 'string',
            'merchants_created' => 'integer',
            'merchants_updated' => 'integer',
            'offers_created' => 'integer',
            'offers_updated' => 'integer',
            'offers_skipped' => 'integer',
            'errors_count' => 'integer',
        ];
    }

    public function isActive(): bool
    {
        return in_array($this->status, ['pending', 'running'], true);
    }
}
