<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Alan Tipleri
    |--------------------------------------------------------------------------
    |
    | Modül oluşturucuda kullanılabilecek alan tipleri
    |
    */
    'field_types' => [
        'string' => 'Metin (String)',
        'text' => 'Uzun Metin (Text)',
        'integer' => 'Tam Sayı (Integer)',
        'decimal' => 'Ondalıklı Sayı (Decimal)',
        'boolean' => 'Evet/Hayır (Boolean)',
        'date' => 'Tarih (Date)',
        'datetime' => 'Tarih/Saat (DateTime)',
        'email' => 'E-posta (Email)',
        'password' => 'Şifre (Password)',
        'file' => 'Dosya (File)',
        'image' => 'Resim (Image)',
    ],

    /*
    |--------------------------------------------------------------------------
    | Doğrulama Kuralları
    |--------------------------------------------------------------------------
    |
    | Modül oluşturucuda kullanılabilecek doğrulama kuralları
    |
    */
    'validation_rules' => [
        'required' => 'Zorunlu',
        'nullable' => 'Boş Olabilir',
        'unique' => 'Benzersiz',
        'email' => 'E-posta',
        'min' => 'Minimum Değer',
        'max' => 'Maksimum Değer',
        'numeric' => 'Sayısal',
        'alpha' => 'Sadece Harf',
        'alpha_num' => 'Harf ve Sayı',
        'alpha_dash' => 'Harf, Sayı ve Tire',
        'url' => 'URL',
        'date' => 'Tarih',
        'boolean' => 'Evet/Hayır',
    ],
]; 