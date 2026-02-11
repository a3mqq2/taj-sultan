<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Permission;
use App\Models\PosPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $permissions = Permission::grouped();
        $posPoints = PosPoint::active()->orderBy('name')->get();

        return view('users.index', compact('permissions', 'posPoints'));
    }

    public function data(Request $request)
    {
        $query = User::with('posPoint');

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('username', 'like', "%{$term}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        $allowedSorts = ['name', 'username', 'created_at'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection);
        }

        $perPage = $request->get('per_page', 15);
        $users = $query->paginate($perPage);

        $roleNames = [
            'admin' => 'مدير',
            'sales' => 'موظف مبيعات',
            'cashier' => 'كاشير',
        ];

        $data = $users->getCollection()->map(function ($user) use ($roleNames) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'role' => $user->role,
                'role_name' => $roleNames[$user->role] ?? $user->role,
                'is_active' => $user->is_active,
                'pos_point_id' => $user->pos_point_id,
                'pos_point_name' => $user->posPoint?->name,
                'permissions' => $user->isAdmin() ? $user->getPermissionNames() : [],
                'created_at' => $user->created_at->format('Y-m-d H:i'),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'pagination' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
                'from' => $users->firstItem(),
                'to' => $users->lastItem(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:4',
            'role' => ['required', Rule::in(['admin', 'sales', 'cashier'])],
            'is_active' => 'boolean',
            'pos_point_id' => 'nullable|exists:pos_points,id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ], [
            'name.required' => 'الاسم مطلوب',
            'username.required' => 'اسم المستخدم مطلوب',
            'username.unique' => 'اسم المستخدم مستخدم مسبقاً',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون 4 أحرف على الأقل',
            'role.required' => 'الدور مطلوب',
            'pos_point_id.exists' => 'نقطة البيع غير موجودة',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'is_active' => $request->boolean('is_active', true),
            'pos_point_id' => in_array($validated['role'], ['sales', 'cashier']) ? ($validated['pos_point_id'] ?? null) : null,
        ]);

        if ($validated['role'] === 'admin' && !empty($validated['permissions'])) {
            $user->syncPermissions($validated['permissions']);
        }

        $user->load('posPoint');

        $roleNames = [
            'admin' => 'مدير',
            'sales' => 'موظف مبيعات',
            'cashier' => 'كاشير',
        ];

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة المستخدم بنجاح',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'role' => $user->role,
                'role_name' => $roleNames[$user->role] ?? $user->role,
                'is_active' => $user->is_active,
                'pos_point_id' => $user->pos_point_id,
                'pos_point_name' => $user->posPoint?->name,
                'permissions' => $user->getPermissionNames(),
                'created_at' => $user->created_at->format('Y-m-d H:i'),
            ]
        ], 201);
    }

    public function show(User $user)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'role' => $user->role,
                'is_active' => $user->is_active,
                'pos_point_id' => $user->pos_point_id,
                'permissions' => $user->permissions()->pluck('permissions.id')->toArray(),
            ]
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($user->id)],
            'password' => 'nullable|string|min:4',
            'role' => ['required', Rule::in(['admin', 'sales', 'cashier'])],
            'is_active' => 'boolean',
            'pos_point_id' => 'nullable|exists:pos_points,id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ], [
            'name.required' => 'الاسم مطلوب',
            'username.required' => 'اسم المستخدم مطلوب',
            'username.unique' => 'اسم المستخدم مستخدم مسبقاً',
            'password.min' => 'كلمة المرور يجب أن تكون 4 أحرف على الأقل',
            'role.required' => 'الدور مطلوب',
            'pos_point_id.exists' => 'نقطة البيع غير موجودة',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'role' => $validated['role'],
            'is_active' => $request->boolean('is_active', $user->is_active),
            'pos_point_id' => in_array($validated['role'], ['sales', 'cashier']) ? ($validated['pos_point_id'] ?? null) : null,
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        if ($validated['role'] === 'admin') {
            $user->syncPermissions($validated['permissions'] ?? []);
        } else {
            $user->syncPermissions([]);
        }

        $user->load('posPoint');

        $roleNames = [
            'admin' => 'مدير',
            'sales' => 'موظف مبيعات',
            'cashier' => 'كاشير',
        ];

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث المستخدم بنجاح',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'role' => $user->role,
                'role_name' => $roleNames[$user->role] ?? $user->role,
                'is_active' => $user->is_active,
                'pos_point_id' => $user->pos_point_id,
                'pos_point_name' => $user->posPoint?->name,
                'permissions' => $user->getPermissionNames(),
                'created_at' => $user->created_at->format('Y-m-d H:i'),
            ]
        ]);
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكنك حذف حسابك الخاص'
            ], 422);
        }

        $adminCount = User::where('role', 'admin')->where('is_active', true)->count();
        if ($user->isAdmin() && $adminCount <= 1) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن حذف آخر مدير نظام'
            ], 422);
        }

        $name = $user->name;
        $user->permissions()->detach();
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => "تم حذف المستخدم ({$name}) بنجاح"
        ]);
    }

    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكنك تعطيل حسابك الخاص'
            ], 422);
        }

        $adminCount = User::where('role', 'admin')->where('is_active', true)->count();
        if ($user->isAdmin() && $user->is_active && $adminCount <= 1) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن تعطيل آخر مدير نظام نشط'
            ], 422);
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'تم تفعيل' : 'تم تعطيل';

        return response()->json([
            'success' => true,
            'message' => "{$status} المستخدم بنجاح",
            'data' => [
                'is_active' => $user->is_active
            ]
        ]);
    }
}
