<?php

namespace App\Http\Controllers;

use App\Models\PosPoint;
use Illuminate\Http\Request;

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
        $validated = $request->validate([
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
}
