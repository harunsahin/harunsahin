<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Doğrulama Mesajları
    |--------------------------------------------------------------------------
    |
    | Aşağıdaki öğeler doğrulama sınıfı tarafından kullanılan varsayılan hata
    | mesajlarını içermektedir. `size` gibi bazı kuralların birden çok çeşidi
    | bulunmaktadır. Her biri ayrı ayrı düzenlenebilir.
    |
    */

    'accepted' => ':attribute kabul edilmelidir.',
    'accepted_if' => ':other :value olduğunda :attribute kabul edilmelidir.',
    'active_url' => ':attribute geçerli bir URL olmalıdır.',
    'after' => ':attribute değeri :date tarihinden sonra olmalıdır.',
    'after_or_equal' => ':attribute değeri :date tarihinden sonra veya eşit olmalıdır.',
    'alpha' => ':attribute sadece harflerden oluşmalıdır.',
    'alpha_dash' => ':attribute sadece harfler, rakamlar ve tirelerden oluşmalıdır.',
    'alpha_num' => ':attribute sadece harfler ve rakamlar içermelidir.',
    'array' => ':attribute dizi olmalıdır.',
    'before' => ':attribute değeri :date tarihinden önce olmalıdır.',
    'before_or_equal' => ':attribute değeri :date tarihinden önce veya eşit olmalıdır.',
    'between' => [
        'numeric' => ':attribute :min - :max arasında olmalıdır.',
        'file' => ':attribute :min - :max arasındaki kilobayt değeri olmalıdır.',
        'string' => ':attribute :min - :max arasında karakterden oluşmalıdır.',
        'array' => ':attribute :min - :max arasında nesneye sahip olmalıdır.',
    ],
    'boolean' => ':attribute sadece doğru veya yanlış olmalıdır.',
    'confirmed' => ':attribute tekrarı eşleşmiyor.',
    'current_password' => 'Parola hatalı.',
    'date' => ':attribute geçerli bir tarih olmalıdır.',
    'date_equals' => ':attribute ile :date aynı tarihler olmalıdır.',
    'date_format' => ':attribute :format biçimi ile eşleşmiyor.',
    'declined' => ':attribute kabul edilmemelidir.',
    'declined_if' => ':other :value olduğunda :attribute kabul edilmemelidir.',
    'different' => ':attribute ile :other birbirinden farklı olmalıdır.',
    'digits' => ':attribute :digits haneden oluşmalıdır.',
    'digits_between' => ':attribute :min ile :max arasında haneden oluşmalıdır.',
    'dimensions' => ':attribute görsel ölçüleri geçersiz.',
    'distinct' => ':attribute alanı yinelenen bir değere sahip.',
    'email' => ':attribute alanına girilen e-posta adresi geçersiz.',
    'ends_with' => ':attribute, şunlardan biriyle bitmelidir: :values',
    'enum' => 'Seçili :attribute geçersiz.',
    'exists' => 'Seçili :attribute geçersiz.',
    'file' => ':attribute dosya olmalıdır.',
    'filled' => ':attribute alanının doldurulması zorunludur.',
    'gt' => [
        'numeric' => ':attribute, :value değerinden büyük olmalıdır.',
        'file'    => ':attribute, :value kilobayt boyutundan büyük olmalıdır.',
        'string'  => ':attribute, :value karakterden uzun olmalıdır.',
        'array'   => ':attribute, :value taneden fazla olmalıdır.',
    ],
    'gte' => [
        'numeric' => ':attribute, :value kadar veya daha fazla olmalıdır.',
        'file'    => ':attribute, :value kilobayt boyutu kadar veya daha büyük olmalıdır.',
        'string'  => ':attribute, :value karakter kadar veya daha uzun olmalıdır.',
        'array'   => ':attribute, :value tane veya daha fazla olmalıdır.',
    ],
    'image' => ':attribute alanı resim dosyası olmalıdır.',
    'in' => ':attribute değeri geçersiz.',
    'in_array' => ':attribute alanı :other içinde mevcut değil.',
    'integer' => ':attribute tamsayı olmalıdır.',
    'ip' => ':attribute geçerli bir IP adresi olmalıdır.',
    'ipv4' => ':attribute geçerli bir IPv4 adresi olmalıdır.',
    'ipv6' => ':attribute geçerli bir IPv6 adresi olmalıdır.',
    'json' => ':attribute geçerli bir JSON değişkeni olmalıdır.',
    'lt' => [
        'numeric' => ':attribute, :value değerinden küçük olmalıdır.',
        'file'    => ':attribute, :value kilobayt boyutundan küçük olmalıdır.',
        'string'  => ':attribute, :value karakterden kısa olmalıdır.',
        'array'   => ':attribute, :value taneden az olmalıdır.',
    ],
    'lte' => [
        'numeric' => ':attribute, :value kadar veya daha küçük olmalıdır.',
        'file'    => ':attribute, :value kilobayt boyutu kadar veya daha küçük olmalıdır.',
        'string'  => ':attribute, :value karakter kadar veya daha kısa olmalıdır.',
        'array'   => ':attribute, :value tane veya daha az olmalıdır.',
    ],
    'mac_address' => ':attribute geçerli bir MAC adresi olmalıdır.',
    'max' => [
        'numeric' => ':attribute değeri en fazla :max olmalıdır.',
        'file' => ':attribute boyutu en fazla :max kilobayt olmalıdır.',
        'string' => ':attribute uzunluğu en fazla :max karakter olmalıdır.',
        'array' => ':attribute en fazla :max nesneye sahip olmalıdır.',
    ],
    'mimes' => ':attribute dosya biçimi :values olmalıdır.',
    'mimetypes' => ':attribute dosya biçimi :values olmalıdır.',
    'min' => [
        'numeric' => ':attribute değeri en az :min olmalıdır.',
        'file' => ':attribute boyutu en az :min kilobayt olmalıdır.',
        'string' => ':attribute uzunluğu en az :min karakter olmalıdır.',
        'array' => ':attribute en az :min nesneye sahip olmalıdır.',
    ],
    'multiple_of' => ':attribute, :value değerinin katları olmalıdır.',
    'not_in' => 'Seçili :attribute geçersiz.',
    'not_regex' => ':attribute biçimi geçersiz.',
    'numeric' => ':attribute sayı olmalıdır.',
    'password' => 'Parola geçersiz.',
    'present' => ':attribute alanı mevcut olmalıdır.',
    'prohibited' => ':attribute alanını gönderemezsiniz.',
    'prohibited_if' => ':other alanı :value değerini aldığında :attribute alanını gönderemezsiniz.',
    'prohibited_unless' => ':other alanı :values değerlerinden birini almadığında :attribute alanını gönderemezsiniz.',
    'prohibits' => ':attribute alanı, :other alanının mevcut olmasını yasaklar.',
    'regex' => ':attribute biçimi geçersiz.',
    'required' => ':attribute alanı gereklidir.',
    'required_array_keys' => ':attribute alanı şu anahtarlara sahip olmalıdır: :values.',
    'required_if' => ':attribute alanı, :other :value değerine sahip olduğunda zorunludur.',
    'required_unless' => ':attribute alanı, :other alanı :values değerlerinden birine sahip olmadığında zorunludur.',
    'required_with' => ':attribute alanı :values varken zorunludur.',
    'required_with_all' => ':attribute alanı herhangi bir :values değeri varken zorunludur.',
    'required_without' => ':attribute alanı :values yokken zorunludur.',
    'required_without_all' => ':attribute alanı :values değerlerinden herhangi biri yokken zorunludur.',
    'same' => ':attribute ile :other eşleşmelidir.',
    'size' => [
        'numeric' => ':attribute :size olmalıdır.',
        'file' => ':attribute :size kilobyte olmalıdır.',
        'string' => ':attribute :size karakter olmalıdır.',
        'array' => ':attribute :size nesneye sahip olmalıdır.',
    ],
    'starts_with' => ':attribute şunlardan biri ile başlamalıdır: :values',
    'string' => ':attribute dizge olmalıdır.',
    'timezone' => ':attribute geçerli bir saat dilimi olmalıdır.',
    'unique' => ':attribute daha önceden kayıt edilmiş.',
    'uploaded' => ':attribute yüklemesi başarısız.',
    'url' => ':attribute biçimi geçersiz.',
    'uuid' => ':attribute bir UUID formatına uygun olmalıdır.',

    /*
    |--------------------------------------------------------------------------
    | Özelleştirilmiş Doğrulama Mesajları
    |--------------------------------------------------------------------------
    |
    | Bu alanda her alan ve kural ikilisine özel hata mesajları tanımlayabilirsiniz.
    | Örnek olarak 'email' alanı 'unique' kuralına özel hata mesajı tanımlamak için
    | "email.unique" şeklinde bir tane özel mesaj girişi yapabilirsiniz.
    |
    */

    'custom' => [
        'agency_id' => [
            'required_without' => 'Acenta veya firma alanlarından en az birini seçmelisiniz.',
        ],
        'company_id' => [
            'required_without' => 'Acenta veya firma alanlarından en az birini seçmelisiniz.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Özelleştirilmiş Öznitelik İsimleri
    |--------------------------------------------------------------------------
    |
    | Bu alandaki bilgiler "email" gibi öznitelik isimlerinin "E-Posta adres"
    | gibi daha okunabilir metinlere çevrilmesini sağlar. Bu bilgiler
    | hata mesajlarının daha temiz olmasını sağlar.
    |
    */

    'attributes' => [
        'full_name' => 'Yetkili kişi',
        'phone' => 'Telefon',
        'email' => 'E-posta',
        'room_count' => 'Oda sayısı',
        'pax_count' => 'Kişi sayısı',
        'checkin_date' => 'Giriş tarihi',
        'checkout_date' => 'Çıkış tarihi',
        'option_date' => 'Opsiyon tarihi',
        'status_id' => 'Durum',
        'agency_id' => 'Acenta',
        'company_id' => 'Firma',
        'notes' => 'Notlar',
        'files' => 'Dosyalar',
    ],
]; 