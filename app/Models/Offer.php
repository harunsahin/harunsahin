<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Offer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'agency_id',
        'company_id',
        'full_name',
        'phone',
        'email',
        'checkin_date',
        'checkout_date',
        'room_count',
        'pax_count',
        'option_date',
        'notes',
        'status_id',
        'created_by',
        'updated_by',
        'is_active'
    ];

    // Tarihleri Carbon instance'larına dönüştür
    protected $dates = [
        'checkin_date',
        'checkout_date',
        'option_date',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Tarih alanlarını Carbon nesnesine dönüştürme
    protected $casts = [
        'room_count' => 'integer',
        'pax_count' => 'integer',
        'is_active' => 'boolean',
        'checkin_date' => 'date',
        'checkout_date' => 'date',
        'option_date' => 'date'
    ];

    /**
     * Teklif durumu ilişkisi
     */
    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * Varsayılan durum ID'sini al
     */
    public static function getDefaultStatusId()
    {
        return Status::where('name', 'Devam Ediyor')->first()->id ?? null;
    }

    public function files()
    {
        return $this->hasMany(OfferFile::class);
    }

    // User ilişkisini ekleyelim
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Mutator'lar - Veri kaydedilirken çalışır
    public function setFullNameAttribute($value)
    {
        $this->attributes['full_name'] = Str::title(strtolower($value));
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
