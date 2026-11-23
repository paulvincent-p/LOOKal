<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmergencyContact extends Model
{
    protected $fillable = ['barangay_id', 'name', 'number', 'type'];

    public function barangay(): BelongsTo
    {
        return $this->belongsTo(Barangay::class);
    }
}
