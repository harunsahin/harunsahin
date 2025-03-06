<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Http\Traits\ApiResponse;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    use ApiResponse;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,super-admin');
    }

    public function index()
    {
        try {
            $users = User::with('role')->paginate(10);
            $roles = Role::all();
            return view('users.index', compact('users', 'roles'));
        } catch (\Exception $e) {
            \Log::error('Users index error: ' . $e->getMessage());
            return back()->with('error', 'Kullanıcılar listelenirken bir hata oluştu.');
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:6',
                'role_id' => 'required|exists:roles,id',
                'is_active' => 'boolean'
            ], [
                'email.unique' => 'Bu e-posta adresi zaten kullanılıyor.',
                'email.required' => 'E-posta alanı zorunludur.',
                'email.email' => 'Geçerli bir e-posta adresi giriniz.',
                'password.required' => 'Şifre alanı zorunludur.',
                'password.min' => 'Şifre en az 6 karakter olmalıdır.',
                'name.required' => 'İsim alanı zorunludur.',
                'role_id.required' => 'Rol seçimi zorunludur.',
                'role_id.exists' => 'Seçilen rol geçerli değil.'
            ]);

            $validated['password'] = Hash::make($validated['password']);
            $validated['is_active'] = $request->has('is_active');
            
            User::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Kullanıcı başarıyla oluşturuldu.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors()['email'][0] ?? 'Validasyon hatası'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Kullanıcı oluşturulurken bir hata oluştu.'
            ], 500);
        }
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role_id' => 'required|exists:roles,id',
            'is_active' => 'boolean'
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kullanıcı başarıyla güncellendi.'
        ]);
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Kendinizi silemezsiniz!'
            ], 403);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kullanıcı başarıyla silindi.'
        ]);
    }

    public function toggleActive(User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Kendi durumunuzu değiştiremezsiniz!'
            ], 403);
        }

        $user->is_active = !$user->is_active;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Kullanıcı durumu güncellendi.',
            'active' => $user->is_active
        ]);
    }
} 