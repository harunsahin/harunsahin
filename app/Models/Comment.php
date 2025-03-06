<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($comment) {
            if (!isset($comment->status)) {
                $comment->status = true;
            }
        });
    }

    protected $table = 'comments';

    protected $attributes = [
        'status' => true
    ];

    protected $fillable = [
        'name',
        'comment',
        'comment_date',
        'position',
        'source',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'name' => 'string',
        'comment' => 'string',
        'comment_date' => 'datetime',
        'position' => 'integer'
    ];

    protected $labels = [
        'name' => 'Ad Soyad',
        'comment' => 'Yorum',
        'comment_date' => 'Yorum Tarihi',
        'position' => 'Pozisyon',
        'source' => 'Kaynak'
    ];

    protected $validations = [
        'name' => 'required|string|max:255',
        'comment' => 'required|string|min:1',
        'comment_date' => 'required|date',
        'position' => 'nullable|integer',
        'source' => 'required|string|in:Tripadvisor,Otelpuan,Google,Booking.com,Hotels.com'
    ];

    public static function getValidationRules()
    {
        return [
            'name' => 'required|string|max:255',
            'comment' => 'required|string|min:1|max:1000',
            'comment_date' => 'required|date',
            'position' => 'nullable|integer',
            'source' => 'required|string|in:Tripadvisor,Otelpuan,Google,Booking.com,Hotels.com'
        ];
    }

    public function getLabel($field)
    {
        return $this->labels[$field] ?? $field;
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class, 'comment_id');
    }
} 