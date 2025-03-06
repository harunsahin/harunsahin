<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KaynakTanim extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kaynak_tanimlari';

    protected $fillable = [
        'kaynak',
        'is_active',
        'position'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    protected $labels = [
        'kaynak' => 'Kaynak',
        'is_active' => 'Aktif',
        'position' => 'Sıra'
    ];

    protected $validations = [
        'kaynak' => 'required|string|max:255|unique:kaynak_tanimlari,kaynak',
        'is_active' => 'boolean',
        'position' => 'nullable|integer'
    ];

    public static function getValidationRules()
    {
        return (new static)->validations;
    }

    public function getLabel($field)
    {
        return $this->labels[$field] ?? $field;
    }
}
