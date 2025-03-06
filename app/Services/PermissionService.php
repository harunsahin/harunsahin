<?php

namespace App\Services;

use App\Interfaces\PermissionRepositoryInterface;
use App\Interfaces\PermissionServiceInterface;
use App\Models\Permission;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PermissionService implements PermissionServiceInterface
{
    protected $repository;

    public function __construct(PermissionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(): LengthAwarePaginator
    {
        return $this->repository->getAll();
    }

    public function create(array $data): Permission
    {
        try {
            return $this->repository->create($data);
        } catch (\Exception $e) {
            Log::error('İzin oluşturma hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function update(int $id, array $data): Permission
    {
        try {
            return $this->repository->update($id, $data);
        } catch (\Exception $e) {
            Log::error('İzin güncelleme hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        try {
            return $this->repository->delete($id);
        } catch (\Exception $e) {
            Log::error('İzin silme hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function bulkDelete(array $ids): bool
    {
        try {
            return $this->repository->bulkDelete($ids);
        } catch (\Exception $e) {
            Log::error('Toplu izin silme hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function findById(int $id): ?Permission
    {
        return $this->repository->findById($id);
    }

    public function updateStatus(int $id, bool $isActive): Permission
    {
        try {
            return $this->repository->updateStatus($id, $isActive);
        } catch (\Exception $e) {
            Log::error('İzin durum güncelleme hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function updateRoles(int $id, array $roleIds): Permission
    {
        try {
            return $this->repository->updateRoles($id, $roleIds);
        } catch (\Exception $e) {
            Log::error('İzin rol güncelleme hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function getRoles(int $id): array
    {
        try {
            return $this->repository->getRoles($id);
        } catch (\Exception $e) {
            Log::error('İzin rol getirme hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function getActive(): array
    {
        try {
            return $this->repository->getActive();
        } catch (\Exception $e) {
            Log::error('Aktif izin getirme hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function getByModule(string $module): array
    {
        try {
            return $this->repository->getByModule($module);
        } catch (\Exception $e) {
            Log::error('Modül izin getirme hatası:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Yeni bir modül için izinleri oluşturur
     */
    public static function createModulePermissions(string $moduleName, string $moduleDescription = null): array
    {
        $actions = [
            'list' => 'Listeleme',
            'create' => 'Oluşturma',
            'read' => 'Görüntüleme',
            'update' => 'Güncelleme',
            'delete' => 'Silme',
            'export' => 'Dışa Aktarma',
            'import' => 'İçe Aktarma',
            'manage' => 'Yönetme'
        ];
        
        $permissions = [];

        foreach ($actions as $action => $displayName) {
            $slug = Str::slug($moduleName) . '.' . $action;
            $name = ucfirst($moduleName) . ' ' . $displayName;
            $description = $moduleDescription ? "$name yetkisi" : null;

            $permission = Permission::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'display_name' => $name,
                    'description' => $description
                ]
            );

            $permissions[] = $permission;
        }

        return $permissions;
    }

    /**
     * Yeni bir özel izin oluşturur
     */
    public static function createCustomPermission(
        string $name,
        string $slug,
        string $description = null
    ): Permission {
        return Permission::firstOrCreate(
            ['slug' => $slug],
            [
                'name' => $name,
                'display_name' => $name,
                'description' => $description
            ]
        );
    }

    /**
     * Özel izinleri senkronize eder
     */
    public static function syncCustomPermissions(): void
    {
        $customPermissions = [
            // Dashboard İzinleri
            [
                'name' => 'Dashboard Erişimi',
                'slug' => 'dashboard.access',
                'description' => 'Dashboard\'a erişim yetkisi'
            ],
            [
                'name' => 'İstatistik Görüntüleme',
                'slug' => 'statistics.view',
                'description' => 'İstatistikleri görüntüleme yetkisi'
            ],
            
            // Ayarlar İzinleri
            [
                'name' => 'Genel Ayarlar Yönetimi',
                'slug' => 'settings.general.manage',
                'description' => 'Genel ayarları yönetme yetkisi'
            ],
            [
                'name' => 'E-posta Ayarları Yönetimi',
                'slug' => 'settings.email.manage',
                'description' => 'E-posta ayarlarını yönetme yetkisi'
            ],
            [
                'name' => 'Yedekleme Yönetimi',
                'slug' => 'settings.backup.manage',
                'description' => 'Yedekleme işlemlerini yönetme yetkisi'
            ],
            
            // Kullanıcı ve Rol İzinleri
            [
                'name' => 'Kullanıcı Yönetimi',
                'slug' => 'users.manage',
                'description' => 'Kullanıcıları yönetme yetkisi'
            ],
            [
                'name' => 'Rol Yönetimi',
                'slug' => 'roles.manage',
                'description' => 'Rolleri yönetme yetkisi'
            ],
            [
                'name' => 'İzin Yönetimi',
                'slug' => 'permissions.manage',
                'description' => 'İzinleri yönetme yetkisi'
            ],
            
            // Modül İzinleri
            [
                'name' => 'Modül Oluşturma',
                'slug' => 'modules.create',
                'description' => 'Yeni modül oluşturma yetkisi'
            ],
            [
                'name' => 'Modül Düzenleme',
                'slug' => 'modules.edit',
                'description' => 'Mevcut modülleri düzenleme yetkisi'
            ],
            
            // Log ve Aktivite İzinleri
            [
                'name' => 'Log Görüntüleme',
                'slug' => 'logs.view',
                'description' => 'Sistem loglarını görüntüleme yetkisi'
            ],
            [
                'name' => 'Aktivite Takibi',
                'slug' => 'activities.view',
                'description' => 'Kullanıcı aktivitelerini görüntüleme yetkisi'
            ]
        ];

        foreach ($customPermissions as $permission) {
            self::createCustomPermission(
                $permission['name'],
                $permission['slug'],
                $permission['description']
            );
        }
    }

    /**
     * Tüm izinleri yeniden senkronize eder
     */
    public static function syncPermissions(): void
    {
        // Özel izinleri senkronize et
        self::syncCustomPermissions();
        
        // Varsayılan modül izinlerini oluştur
        $defaultModules = [
            'companies' => 'Şirketler',
            'agencies' => 'Acenteler',
            'offers' => 'Teklifler',
            'users' => 'Kullanıcılar',
            'roles' => 'Roller',
            'settings' => 'Ayarlar'
        ];
        
        foreach ($defaultModules as $module => $description) {
            self::createModulePermissions($module, $description);
        }
    }
} 