<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

class OfferFile extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'offer_id',
        'file_path',
        'original_name',
        'mime_type',
        'file_size',
        'created_by',
        'updated_by',
        'is_active'
    ];

    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }

    public function getIconAttribute()
    {
        return match ($this->mime_type) {
            'application/pdf' => 'fas fa-file-pdf',
            'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'fas fa-file-word',
            'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'fas fa-file-excel',
            default => 'fas fa-file'
        };
    }
} 