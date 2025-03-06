# CRUD Uygulaması

## Aktivite Loglama Sistemi

Bu uygulamada kullanıcı aktivitelerini loglamak için özel bir sistem kullanılmaktadır. Sistem, tüm CRUD işlemlerini ve önemli kullanıcı aktivitelerini kaydeder.

### Kullanım

Aktivite loglamak için `ActivityService` kullanılır:

```php
$this->activityService->create([
    'user_id' => auth()->id(),
    'action' => 'create|update|delete',
    'module' => 'module_name',
    'comment_id' => $comment->id, // İlgili modül ID'si
    'old_values' => [], // Eski değerler (varsa)
    'new_values' => $data // Yeni değerler
]);
```

### Önemli Notlar

1. Her aktivite kaydı için zorunlu alanlar:
   - user_id: İşlemi yapan kullanıcı ID'si
   - action: Yapılan işlem türü (create, update, delete)
   - module: İşlemin yapıldığı modül adı
   - comment_id: İlgili kayıt ID'si

2. Opsiyonel alanlar:
   - old_values: Değişiklik öncesi değerler
   - new_values: Değişiklik sonrası değerler

### Örnek Kullanım

```php
// Yeni kayıt oluşturma
$this->activityService->create([
    'user_id' => auth()->id(),
    'action' => 'create',
    'module' => 'comments',
    'comment_id' => $comment->id,
    'old_values' => [],
    'new_values' => $comment->toArray()
]);

// Kayıt güncelleme
$this->activityService->create([
    'user_id' => auth()->id(),
    'action' => 'update',
    'module' => 'comments',
    'comment_id' => $comment->id,
    'old_values' => $oldValues,
    'new_values' => $comment->toArray()
]);

// Kayıt silme
$this->activityService->create([
    'user_id' => auth()->id(),
    'action' => 'delete',
    'module' => 'comments',
    'comment_id' => $comment->id,
    'old_values' => $comment->toArray(),
    'new_values' => null
]);
```

## Kurulum

1. Projeyi klonlayın
2. Composer bağımlılıklarını yükleyin: `composer install`
3. .env dosyasını oluşturun: `cp .env.example .env`
4. Uygulama anahtarını oluşturun: `php artisan key:generate`
5. Veritabanını oluşturun ve migrate edin: `php artisan migrate`
6. Uygulamayı çalıştırın: `php artisan serve`

## Geliştirme

- Yeni özellikler için branch oluşturun
- Değişikliklerinizi test edin
- Pull request açın
- Code review sonrası merge edin

## Test

```bash
php artisan test
```

## Lisans

Bu proje MIT lisansı altında lisanslanmıştır.

---

## 👋 Merhaba

Bu proje, Laravel tabanlı bir CRUD uygulamasıdır. Aktivite loglama sistemi ile kullanıcı işlemlerini takip edebilirsiniz.

### 🔭 Şu anda üzerinde çalışıyorum
- Laravel CRUD uygulaması geliştirme
- Aktivite loglama sistemi entegrasyonu
- Test senaryoları yazımı

### 🌱 Öğreniyorum
- Laravel best practices
- Test driven development
- Git workflow

### 💬 İletişim
- GitHub: [@harunsahin](https://github.com/harunsahin)
- Email: [your.email@example.com](mailto:your.email@example.com)
