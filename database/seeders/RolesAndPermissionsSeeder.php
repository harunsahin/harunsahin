<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Services\PermissionService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // İzinleri senkronize et
        PermissionService::syncPermissions();

        // Rolleri oluştur
        $roles = [
            [
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'display_name' => 'Super Admin',
                'description' => 'Sistem yöneticisi - Tüm yetkilere sahip'
            ],
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'display_name' => 'Admin',
                'description' => 'Site yöneticisi - Kısıtlı yetkiler hariç tüm yetkilere sahip'
            ],
            [
                'name' => 'Manager',
                'slug' => 'manager',
                'display_name' => 'Yönetici',
                'description' => 'Departman yöneticisi - Departman ile ilgili tüm yetkilere sahip'
            ],
            [
                'name' => 'Supervisor',
                'slug' => 'supervisor',
                'display_name' => 'Supervisor',
                'description' => 'Takım lideri - Belirli modüller üzerinde tam yetkiye sahip'
            ],
            [
                'name' => 'Editor',
                'slug' => 'editor',
                'display_name' => 'Editör',
                'description' => 'İçerik editörü - Görüntüleme ve düzenleme yetkilerine sahip'
            ],
            [
                'name' => 'User',
                'slug' => 'user',
                'display_name' => 'Kullanıcı',
                'description' => 'Normal kullanıcı - Temel görüntüleme yetkilerine sahip'
            ]
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['slug' => $role['slug']],
                $role
            );
        }

        // Rollere izinleri ata
        $superAdmin = Role::where('slug', 'super-admin')->first();
        $admin = Role::where('slug', 'admin')->first();
        $manager = Role::where('slug', 'manager')->first();
        $supervisor = Role::where('slug', 'supervisor')->first();
        $editor = Role::where('slug', 'editor')->first();
        $user = Role::where('slug', 'user')->first();

        // Super Admin tüm izinlere sahip
        $superAdmin->permissions()->sync(Permission::all());

        // Admin tüm izinlere sahip (süper admin yetkileri hariç)
        $admin->permissions()->sync(
            Permission::where('slug', 'not like', 'roles.%')
                ->where('slug', 'not like', 'permissions.%')
                ->where('slug', 'not like', 'settings.backup.%')
                ->where('slug', 'not like', 'logs.%')
                ->get()
        );

        // Manager departman yetkileri
        $managerPermissions = [
            // Dashboard ve İstatistik
            'dashboard.access',
            'statistics.view',
            
            // Şirket Yönetimi
            'companies.list',
            'companies.create',
            'companies.read',
            'companies.update',
            'companies.delete',
            'companies.export',
            'companies.import',
            
            // Acente Yönetimi
            'agencies.list',
            'agencies.create',
            'agencies.read',
            'agencies.update',
            'agencies.delete',
            'agencies.export',
            'agencies.import',
            
            // Teklif Yönetimi
            'offers.list',
            'offers.create',
            'offers.read',
            'offers.update',
            'offers.delete',
            'offers.export',
            
            // Kullanıcı Yönetimi (Kısıtlı)
            'users.list',
            'users.read',
            
            // Aktivite Takibi
            'activities.view'
        ];
        
        $manager->permissions()->sync(
            Permission::whereIn('slug', $managerPermissions)->get()
        );

        // Supervisor yetkileri
        $supervisorPermissions = [
            // Dashboard
            'dashboard.access',
            'statistics.view',
            
            // Şirket Yönetimi
            'companies.list',
            'companies.read',
            'companies.update',
            'companies.export',
            
            // Acente Yönetimi
            'agencies.list',
            'agencies.read',
            'agencies.update',
            'agencies.export',
            
            // Teklif Yönetimi
            'offers.list',
            'offers.create',
            'offers.read',
            'offers.update',
            'offers.export'
        ];
        
        $supervisor->permissions()->sync(
            Permission::whereIn('slug', $supervisorPermissions)->get()
        );

        // Editor yetkileri
        $editorPermissions = [
            // Dashboard
            'dashboard.access',
            
            // Şirket ve Acente (Sadece görüntüleme)
            'companies.list',
            'companies.read',
            'agencies.list',
            'agencies.read',
            
            // Teklif Yönetimi
            'offers.list',
            'offers.read',
            'offers.update'
        ];
        
        $editor->permissions()->sync(
            Permission::whereIn('slug', $editorPermissions)->get()
        );

        // Normal kullanıcı yetkileri
        $userPermissions = [
            // Dashboard
            'dashboard.access',
            
            // Sadece görüntüleme yetkileri
            'companies.list',
            'companies.read',
            'agencies.list',
            'agencies.read',
            'offers.list',
            'offers.read'
        ];
        
        $user->permissions()->sync(
            Permission::whereIn('slug', $userPermissions)->get()
        );

        // Varsayılan admin kullanıcısını güncelle
        $adminUser = User::where('email', 'admin@example.com')->first();
        if ($adminUser) {
            $adminUser->update(['role_id' => $superAdmin->id]);
        }
    }
}
