<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    protected $fillable = ['business_id', 'reason', 'details', 'status'];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
}
