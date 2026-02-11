<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaymentMethodController extends Controller
{
    public function index()
    {
        return view('payment-methods.index');
    }

    public function data(Request $request)
    {
        $query = PaymentMethod::query();

        // البحث
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // الترتيب
        $sortField = $request->get('sort', 'sort_order');
        $sortDirection = $request->get('direction', 'asc');

        $allowedSorts = ['name', 'sort_order', 'created_at'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection);
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $paymentMethods = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $paymentMethods->items(),
            'pagination' => [
                'current_page' => $paymentMethods->currentPage(),
                'last_page' => $paymentMethods->lastPage(),
                'per_page' => $paymentMethods->perPage(),
                'total' => $paymentMethods->total(),
                'from' => $paymentMethods->firstItem(),
                'to' => $paymentMethods->lastItem(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:payment_methods,name',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ], [
            'name.required' => 'اسم طريقة الدفع مطلوب',
            'name.max' => 'اسم طريقة الدفع يجب ألا يتجاوز 255 حرف',
            'name.unique' => 'طريقة الدفع موجودة مسبقاً',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? PaymentMethod::max('sort_order') + 1;

        $paymentMethod = PaymentMethod::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة طريقة الدفع بنجاح',
            'data' => $paymentMethod
        ], 201);
    }

    public function show(PaymentMethod $paymentMethod)
    {
        return response()->json([
            'success' => true,
            'data' => $paymentMethod
        ]);
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('payment_methods', 'name')->ignore($paymentMethod->id)
            ],
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ], [
            'name.required' => 'اسم طريقة الدفع مطلوب',
            'name.max' => 'اسم طريقة الدفع يجب ألا يتجاوز 255 حرف',
            'name.unique' => 'طريقة الدفع موجودة مسبقاً',
        ]);

        $validated['is_active'] = $request->boolean('is_active', $paymentMethod->is_active);

        $paymentMethod->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث طريقة الدفع بنجاح',
            'data' => $paymentMethod
        ]);
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $name = $paymentMethod->name;
        $paymentMethod->delete();

        return response()->json([
            'success' => true,
            'message' => "تم حذف طريقة الدفع ({$name}) بنجاح"
        ]);
    }

    public function toggleStatus(PaymentMethod $paymentMethod)
    {
        $paymentMethod->is_active = !$paymentMethod->is_active;
        $paymentMethod->save();

        $status = $paymentMethod->is_active ? 'تم تفعيل' : 'تم إيقاف';

        return response()->json([
            'success' => true,
            'message' => "{$status} طريقة الدفع بنجاح",
            'data' => [
                'is_active' => $paymentMethod->is_active
            ]
        ]);
    }
}
