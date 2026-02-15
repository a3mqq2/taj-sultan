<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PosPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $posPoints = PosPoint::orderBy('name')->get();

        return view('products.index', compact('posPoints'));
    }

    public function data(Request $request)
    {
        $query = Product::with('posPoint');

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('pos_point_id')) {
            $query->forPosPoint($request->pos_point_id);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->inactive();
            }
        }

        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        $allowedSorts = ['name', 'price', 'created_at'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection);
        }

        $perPage = $request->get('per_page', 15);
        $products = $query->paginate($perPage);

        $data = $products->getCollection()->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $product->price,
                'pos_point_id' => $product->pos_point_id,
                'pos_point' => $product->posPoint,
                'type' => $product->type,
                'barcode' => $product->barcode,
                'is_active' => $product->is_active,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'pos_point_id' => 'required|exists:pos_points,id',
            'type' => 'required|in:piece,weight',
            'barcode' => 'nullable|string|max:100|unique:products,barcode',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'اسم الصنف مطلوب',
            'name.max' => 'اسم الصنف يجب ألا يتجاوز 255 حرف',
            'price.required' => 'السعر مطلوب',
            'price.numeric' => 'السعر يجب أن يكون رقماً',
            'price.min' => 'السعر يجب أن يكون 0 أو أكثر',
            'pos_point_id.required' => 'نقطة البيع مطلوبة',
            'pos_point_id.exists' => 'نقطة البيع غير موجودة',
            'type.required' => 'نوع الصنف مطلوب',
            'type.in' => 'نوع الصنف غير صحيح',
            'barcode.unique' => 'الباركود مستخدم مسبقاً',
        ]);

        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(5);
        $validated['is_active'] = $request->boolean('is_active', true);

        $product = Product::create($validated);
        $product->load('posPoint');

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة الصنف بنجاح',
            'data' => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'pos_point_id' => $product->pos_point_id,
                'pos_point' => $product->posPoint,
                'type' => $product->type,
                'barcode' => $product->barcode,
                'is_active' => $product->is_active,
            ]
        ], 201);
    }

    public function show(Product $product)
    {
        $product->load('posPoint');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'pos_point_id' => $product->pos_point_id,
                'type' => $product->type,
                'barcode' => $product->barcode,
                'is_active' => $product->is_active,
            ]
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'pos_point_id' => 'required|exists:pos_points,id',
            'type' => 'required|in:piece,weight',
            'barcode' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('products', 'barcode')->ignore($product->id)
            ],
            'is_active' => 'boolean',
        ], [
            'name.required' => 'اسم الصنف مطلوب',
            'name.max' => 'اسم الصنف يجب ألا يتجاوز 255 حرف',
            'price.required' => 'السعر مطلوب',
            'price.numeric' => 'السعر يجب أن يكون رقماً',
            'price.min' => 'السعر يجب أن يكون 0 أو أكثر',
            'pos_point_id.required' => 'نقطة البيع مطلوبة',
            'pos_point_id.exists' => 'نقطة البيع غير موجودة',
            'type.required' => 'نوع الصنف مطلوب',
            'type.in' => 'نوع الصنف غير صحيح',
            'barcode.unique' => 'الباركود مستخدم مسبقاً',
        ]);

        $validated['is_active'] = $request->boolean('is_active', $product->is_active);

        $product->update($validated);
        $product->load('posPoint');

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الصنف بنجاح',
            'data' => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'pos_point_id' => $product->pos_point_id,
                'pos_point' => $product->posPoint,
                'type' => $product->type,
                'barcode' => $product->barcode,
                'is_active' => $product->is_active,
            ]
        ]);
    }

    public function destroy(Product $product)
    {
        $productName = $product->name;
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => "تم حذف الصنف ({$productName}) بنجاح"
        ]);
    }

    public function toggleStatus(Product $product)
    {
        $product->is_active = !$product->is_active;
        $product->save();

        $status = $product->is_active ? 'تم تفعيل' : 'تم إيقاف';

        return response()->json([
            'success' => true,
            'message' => "{$status} الصنف بنجاح",
            'data' => [
                'is_active' => $product->is_active
            ]
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:products,id'
        ]);

        $count = Product::whereIn('id', $validated['ids'])->delete();

        return response()->json([
            'success' => true,
            'message' => "تم حذف {$count} صنف بنجاح"
        ]);
    }

    public function bulkToggle(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:products,id',
            'is_active' => 'required|boolean'
        ]);

        $count = Product::whereIn('id', $validated['ids'])
            ->update(['is_active' => $validated['is_active']]);

        $status = $validated['is_active'] ? 'تفعيل' : 'إيقاف';

        return response()->json([
            'success' => true,
            'message' => "تم {$status} {$count} صنف بنجاح"
        ]);
    }

    public function exportExcel(Request $request)
    {
        $products = Product::where('type', 'weight')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $filename = 'weight_products_' . now()->format('Y-m-d_H-i') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');

            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                'الاسم',
                'السعر'
            ]);

            foreach ($products as $product) {
                fputcsv($file, [
                    $product->name,
                    number_format($product->price, 3)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
