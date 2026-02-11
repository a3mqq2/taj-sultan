<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::active()->ordered()->get();

        return view('customers.index', compact('paymentMethods'));
    }

    public function data(Request $request)
    {
        $query = Customer::query();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        $allowedSorts = ['name', 'created_at'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection);
        }

        $perPage = $request->get('per_page', 15);
        $customers = $query->paginate($perPage);

        $data = $customers->getCollection()->map(function ($customer) {
            return [
                'id' => $customer->id,
                'name' => $customer->name,
                'phone' => $customer->phone,
                'address' => $customer->address,
                'notes' => $customer->notes,
                'is_active' => $customer->is_active,
                'balance' => $customer->balance,
                'created_at' => $customer->created_at->format('Y-m-d H:i'),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'pagination' => [
                'current_page' => $customers->currentPage(),
                'last_page' => $customers->lastPage(),
                'per_page' => $customers->perPage(),
                'total' => $customers->total(),
                'from' => $customers->firstItem(),
                'to' => $customers->lastItem(),
            ]
        ]);
    }

    public function search(Request $request)
    {
        $term = $request->get('q', '');

        $customers = Customer::active()
            ->search($term)
            ->limit(20)
            ->get(['id', 'name', 'phone']);

        return response()->json([
            'success' => true,
            'data' => $customers->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'phone' => $customer->phone,
                    'label' => $customer->name . ($customer->phone ? " ({$customer->phone})" : ''),
                ];
            })
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'اسم الزبون مطلوب',
            'name.max' => 'اسم الزبون يجب ألا يتجاوز 255 حرف',
        ]);

        $customer = Customer::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة الزبون بنجاح',
            'data' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'phone' => $customer->phone,
                'address' => $customer->address,
                'notes' => $customer->notes,
                'is_active' => $customer->is_active,
                'balance' => 0,
                'created_at' => $customer->created_at->format('Y-m-d H:i'),
            ]
        ], 201);
    }

    public function show(Customer $customer)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'phone' => $customer->phone,
                'address' => $customer->address,
                'notes' => $customer->notes,
                'is_active' => $customer->is_active,
                'balance' => $customer->balance,
            ]
        ]);
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'اسم الزبون مطلوب',
            'name.max' => 'اسم الزبون يجب ألا يتجاوز 255 حرف',
        ]);

        $customer->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'is_active' => $request->boolean('is_active', $customer->is_active),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث بيانات الزبون بنجاح',
            'data' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'phone' => $customer->phone,
                'address' => $customer->address,
                'notes' => $customer->notes,
                'is_active' => $customer->is_active,
                'balance' => $customer->balance,
            ]
        ]);
    }

    public function destroy(Customer $customer)
    {
        if (!$customer->canDelete()) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن حذف هذا الزبون لوجود طلبات أو حركات مالية مرتبطة به'
            ], 422);
        }

        $name = $customer->name;
        $customer->delete();

        return response()->json([
            'success' => true,
            'message' => "تم حذف الزبون ({$name}) بنجاح"
        ]);
    }

    public function statement(Customer $customer)
    {
        $transactions = $customer->transactions()
            ->with(['paymentMethod', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        $paymentMethods = PaymentMethod::active()->ordered()->get();

        return view('customers.statement', compact('customer', 'transactions', 'paymentMethods'));
    }

    public function addPayment(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'notes' => 'nullable|string',
        ], [
            'amount.required' => 'المبلغ مطلوب',
            'amount.numeric' => 'المبلغ يجب أن يكون رقماً',
            'amount.min' => 'المبلغ يجب أن يكون أكبر من صفر',
            'payment_method_id.required' => 'طريقة الدفع مطلوبة',
        ]);

        try {
            $transaction = $customer->addPayment(
                $validated['amount'],
                $validated['payment_method_id'],
                auth()->id(),
                $validated['notes'] ?? null
            );

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة الدفعة بنجاح',
                'data' => [
                    'transaction_id' => $transaction->id,
                    'balance' => $customer->balance,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إضافة الدفعة'
            ], 500);
        }
    }

    public function toggleStatus(Customer $customer)
    {
        $customer->is_active = !$customer->is_active;
        $customer->save();

        $status = $customer->is_active ? 'تم تفعيل' : 'تم إيقاف';

        return response()->json([
            'success' => true,
            'message' => "{$status} الزبون بنجاح",
            'data' => [
                'is_active' => $customer->is_active
            ]
        ]);
    }

    public function printStatement(Customer $customer)
    {
        $transactions = $customer->transactions()
            ->with(['paymentMethod', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customers.print-statement', compact('customer', 'transactions'));
    }
}
