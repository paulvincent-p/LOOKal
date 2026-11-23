<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['business_id', 'action'];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
}
