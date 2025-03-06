<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:super-admin');
    }

    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id'
        ], [
            'name.required' => 'Rol adı zorunludur.',
            'name.unique' => 'Bu rol adı zaten kullanılıyor.',
            'permissions.required' => 'En az bir yetki seçmelisiniz.',
            'permissions.*.exists' => 'Geçersiz yetki seçimi.'
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description']
        ]);

        $role->permissions()->attach($validated['permissions']);

        return response()->json([
            'success' => true,
            'message' => 'Rol başarıyla oluşturuldu.'
        ]);
    }

    public function update(Request $request, Role $role)
    {
        if ($role->slug === 'super-admin') {
            return response()->json([
                'success' => false,
                'message' => 'Super Admin rolü düzenlenemez.'
            ], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description']
        ]);

        $role->permissions()->sync($validated['permissions']);

        return response()->json([
            'success' => true,
            'message' => 'Rol başarıyla güncellendi.'
        ]);
    }

    public function destroy(Role $role)
    {
        if ($role->slug === 'super-admin' || $role->slug === 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Bu rol silinemez.'
            ], 403);
        }

        if ($role->users()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Bu role sahip kullanıcılar var. Önce kullanıcıların rollerini değiştirmelisiniz.'
            ], 403);
        }

        $role->delete();

        return response()->json([
            'success' => true,
            'message' => 'Rol başarıyla silindi.'
        ]);
    }
} 