<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'color',
        'icon',
        'description',
        'type',
        'order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer'
    ];

    /**
     * Bootstrap color classes
     */
    public static $colors = [
        'primary'   => '#0d6efd', // Mavi
        'secondary' => '#6c757d', // Gri
        'success'   => '#198754', // Yeşil
        'danger'    => '#dc3545', // Kırmızı
        'warning'   => '#ffc107', // Sarı
        'info'      => '#0dcaf0', // Açık Mavi
        'dark'      => '#212529'  // Koyu
    ];

    /**
     * Durum silinmeden önce kontrol
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function($status) {
            // Eğer bu duruma bağlı teklifler varsa silmeyi engelle
            if ($status->offers()->count() > 0) {
                throw new \Exception('Bu durum kullanımda olduğu için silinemez.');
            }
        });
    }

    /**
     * Get the color for the status
     */
    public function getColorAttribute($value)
    {
        return $value ?: '#6c757d'; // Varsayılan renk gri
    }

    /**
     * Bu duruma sahip teklifler
     */
    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public function companies()
    {
        return $this->hasMany(Company::class);
    }

    public function agencies()
    {
        return $this->hasMany(Agency::class);
    }
} 