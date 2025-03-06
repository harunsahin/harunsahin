<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agency extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'name',
        'tax_number',
        'tax_office',
        'address',
        'phone',
        'email',
        'website',
        'status_id',
        'created_by',
        'updated_by',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }
} 