<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::active()->ordered()->get();

        return view('products.index', compact('categories'));
    }

    public function data(Request $request)
    {
        $query = Product::with('category');

        // البحث
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // فلترة حسب القسم
        if ($request->filled('category_id')) {
            $query->byCategory($request->category_id);
        }

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->inactive();
            }
        }

        // الترتيب
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        $allowedSorts = ['name', 'price', 'created_at'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection);
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $products = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $products->items(),
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
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:piece,weight',
            'barcode' => 'nullable|string|max:100|unique:products,barcode',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'اسم الصنف مطلوب',
            'name.max' => 'اسم الصنف يجب ألا يتجاوز 255 حرف',
            'price.required' => 'السعر مطلوب',
            'price.numeric' => 'السعر يجب أن يكون رقماً',
            'price.min' => 'السعر يجب أن يكون 0 أو أكثر',
            'category_id.required' => 'القسم مطلوب',
            'category_id.exists' => 'القسم غير موجود',
            'type.required' => 'نوع الصنف مطلوب',
            'type.in' => 'نوع الصنف غير صحيح',
            'barcode.unique' => 'الباركود مستخدم مسبقاً',
        ]);

        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(5);
        $validated['is_active'] = $request->boolean('is_active', true);

        $product = Product::create($validated);
        $product->load('category');

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة الصنف بنجاح',
            'data' => $product
        ], 201);
    }

    public function show(Product $product)
    {
        $product->load('category');

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:piece,weight',
            'barcode' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('products', 'barcode')->ignore($product->id)
            ],
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'اسم الصنف مطلوب',
            'name.max' => 'اسم الصنف يجب ألا يتجاوز 255 حرف',
            'price.required' => 'السعر مطلوب',
            'price.numeric' => 'السعر يجب أن يكون رقماً',
            'price.min' => 'السعر يجب أن يكون 0 أو أكثر',
            'category_id.required' => 'القسم مطلوب',
            'category_id.exists' => 'القسم غير موجود',
            'type.required' => 'نوع الصنف مطلوب',
            'type.in' => 'نوع الصنف غير صحيح',
            'barcode.unique' => 'الباركود مستخدم مسبقاً',
        ]);

        $validated['is_active'] = $request->boolean('is_active', $product->is_active);

        $product->update($validated);
        $product->load('category');

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الصنف بنجاح',
            'data' => $product
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
        $query = Product::with('category');

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('category_id')) {
            $query->byCategory($request->category_id);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->inactive();
            }
        }

        $products = $query->orderBy('name')->get();

        $filename = 'products_' . now()->format('Y-m-d_H-i') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');

            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                'الاسم',
                'القسم',
                'السعر',
                'النوع',
                'الباركود',
                'الحالة'
            ]);

            foreach ($products as $product) {
                fputcsv($file, [
                    $product->name,
                    $product->category?->name ?? '-',
                    number_format($product->price, 3),
                    $product->type === 'piece' ? 'قطعة' : 'وزن',
                    $product->barcode ?? '-',
                    $product->is_active ? 'مفعل' : 'موقوف'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
