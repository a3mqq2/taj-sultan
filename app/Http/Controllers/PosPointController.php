<?php

namespace App\Http\Controllers;

use App\Models\PosPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PosPointController extends Controller
{
    public function index()
    {
        return view('pos-points.index');
    }

    public function data(Request $request)
    {
        $query = PosPoint::query();

        $sortField = $request->get('sort', 'id');
        $sortDirection = $request->get('direction', 'asc');

        $allowedSorts = ['name', 'id'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection);
        }

        $posPoints = $query->get();

        $data = $posPoints->map(function ($point) {
            return [
                'id' => $point->id,
                'name' => $point->name,
                'slug' => $point->slug,
                'active' => $point->active,
                'require_login' => $point->require_login,
                'is_default' => $point->is_default,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:pos_points,name',
            'active' => 'boolean',
            'require_login' => 'boolean',
        ], [
            'name.required' => 'اسم نقطة البيع مطلوب',
            'name.unique' => 'اسم نقطة البيع موجود مسبقاً',
            'name.max' => 'اسم نقطة البيع يجب ألا يتجاوز 255 حرف',
        ]);

        $slug = Str::slug($validated['name']);
        $originalSlug = $slug;
        $counter = 1;
        while (PosPoint::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $posPoint = PosPoint::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'active' => $request->boolean('active', true),
            'require_login' => $request->boolean('require_login', false),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة نقطة البيع بنجاح',
            'data' => [
                'id' => $posPoint->id,
                'name' => $posPoint->name,
                'slug' => $posPoint->slug,
                'active' => $posPoint->active,
                'require_login' => $posPoint->require_login,
                'is_default' => $posPoint->is_default,
            ]
        ], 201);
    }

    public function show(PosPoint $posPoint)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $posPoint->id,
                'name' => $posPoint->name,
                'slug' => $posPoint->slug,
                'active' => $posPoint->active,
                'require_login' => $posPoint->require_login,
                'is_default' => $posPoint->is_default,
            ]
        ]);
    }

    public function update(Request $request, PosPoint $posPoint)
    {
        $request->validate([
            'active' => 'boolean',
            'require_login' => 'boolean',
        ]);

        $posPoint->update([
            'active' => $request->boolean('active', $posPoint->active),
            'require_login' => $request->boolean('require_login', $posPoint->require_login),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث نقطة البيع بنجاح',
            'data' => [
                'id' => $posPoint->id,
                'name' => $posPoint->name,
                'slug' => $posPoint->slug,
                'active' => $posPoint->active,
                'require_login' => $posPoint->require_login,
                'is_default' => $posPoint->is_default,
            ]
        ]);
    }

    public function toggleActive(PosPoint $posPoint)
    {
        $posPoint->active = !$posPoint->active;
        $posPoint->save();

        $status = $posPoint->active ? 'تم تفعيل' : 'تم إيقاف';

        return response()->json([
            'success' => true,
            'message' => "{$status} نقطة البيع بنجاح",
            'data' => [
                'active' => $posPoint->active
            ]
        ]);
    }

    public function destroy(PosPoint $posPoint)
    {
        if ($posPoint->is_default) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن حذف نقطة البيع الافتراضية'
            ], 422);
        }

        if ($posPoint->users()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن حذف نقطة البيع لوجود مستخدمين مرتبطين بها'
            ], 422);
        }

        $name = $posPoint->name;
        $posPoint->products()->detach();
        $posPoint->delete();

        return response()->json([
            'success' => true,
            'message' => "تم حذف نقطة البيع ({$name}) بنجاح"
        ]);
    }
}
