<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Business extends Model
{
    protected $fillable = [
        'owner_id', 'category_id', 'barangay_id', 'name', 'description', 'phone',
        'messenger_link', 'hours', 'address', 'latitude', 'longitude', 'photo_path',
        'status', 'is_verified', 'admin_note',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
    ];

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function barangay(): BelongsTo
    {
        return $this->belongsTo(Barangay::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function photoUrl(): ?string
    {
        return $this->photo_path ? Storage::disk('public')->url($this->photo_path) : null;
    }

    /** Phone number with only digits and a leading plus, for tel: and sms: links. */
    public function dialNumber(): string
    {
        return preg_replace('/[^0-9+]/', '', $this->phone);
    }

    public function mapUrl(): string
    {
        if ($this->latitude !== null && $this->longitude !== null) {
            $query = $this->latitude.','.$this->longitude;
        } else {
            $query = $this->name.' '.($this->barangay->name ?? '').' Lingayen Pangasinan';
        }

        return 'https://www.google.com/maps/search/?api=1&query='.urlencode($query);
    }
}
