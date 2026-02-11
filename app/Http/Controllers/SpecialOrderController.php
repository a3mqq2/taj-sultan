<?php

namespace App\Http\Controllers;

use App\Models\SpecialOrder;
use App\Models\SpecialOrderPayment;
use App\Models\SpecialOrderItem;
use App\Models\Customer;
use App\Models\CustomerTransaction;
use App\Models\Product;
use App\Models\PaymentMethod;
use App\Models\EventType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class SpecialOrderController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::active()->ordered()->get();
        $statuses = SpecialOrder::getStatuses();
        $eventTypes = EventType::active()->ordered()->get();
        $customers = Customer::active()->orderBy('name')->get();
        $products = Product::active()->orderBy('name')->get();

        return view('special-orders.index', compact('paymentMethods', 'statuses', 'eventTypes', 'customers', 'products'));
    }

    public function data(Request $request)
    {
        $query = SpecialOrder::with(['user:id,name', 'customer:id,name,phone'])
            ->withCount('payments');

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('status')) {
            $query->status($request->status);
        }

        if ($request->filled('date_from')) {
            $query->where('delivery_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('delivery_date', '<=', $request->date_to);
        }

        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        $allowedSorts = ['created_at', 'delivery_date', 'total_amount', 'customer_name'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection);
        }

        $perPage = $request->get('per_page', 15);
        $orders = $query->paginate($perPage);

        $data = $orders->getCollection()->map(function ($order) {
            return [
                'id' => $order->id,
                'customer_id' => $order->customer_id,
                'customer_name' => $order->customer ? $order->customer->name : $order->customer_name,
                'phone' => $order->customer ? $order->customer->phone : $order->phone,
                'event_type' => $order->event_type,
                'description' => $order->description,
                'delivery_date' => $order->delivery_date->format('Y-m-d'),
                'total_amount' => $order->total_amount,
                'paid_amount' => $order->paid_amount,
                'remaining_amount' => $order->remaining_amount,
                'status' => $order->status,
                'status_name' => $order->status_name,
                'status_badge_class' => $order->status_badge_class,
                'notes' => $order->notes,
                'payments_count' => $order->payments_count,
                'user' => $order->user,
                'created_at' => $order->created_at->format('Y-m-d H:i'),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
                'from' => $orders->firstItem(),
                'to' => $orders->lastItem(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'required_without:customer_id|nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'event_type' => 'required|string|max:100',
            'description' => 'nullable|string',
            'delivery_date' => 'required|date|after_or_equal:today',
            'total_amount' => 'required_without:items|nullable|numeric|min:0.01',
            'items' => 'nullable|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.notes' => 'nullable|string',
            'deposit_amount' => 'nullable|numeric|min:0',
            'deposit_payment_method_id' => 'required_with:deposit_amount|nullable|exists:payment_methods,id',
            'notes' => 'nullable|string',
        ], [
            'customer_name.required_without' => 'اسم العميل مطلوب',
            'event_type.required' => 'نوع المناسبة مطلوب',
            'delivery_date.required' => 'تاريخ التسليم مطلوب',
            'delivery_date.after_or_equal' => 'تاريخ التسليم يجب أن يكون اليوم أو بعده',
            'total_amount.required_without' => 'المبلغ الإجمالي مطلوب',
            'total_amount.min' => 'المبلغ الإجمالي يجب أن يكون أكبر من صفر',
        ]);

        DB::beginTransaction();
        try {
            $totalAmount = $validated['total_amount'] ?? 0;

            if (!empty($validated['items'])) {
                $totalAmount = 0;
                foreach ($validated['items'] as $item) {
                    $totalAmount += $item['quantity'] * $item['unit_price'];
                }
            }

            $customerName = $validated['customer_name'] ?? null;
            $phone = $validated['phone'] ?? null;

            if (!empty($validated['customer_id'])) {
                $customer = Customer::find($validated['customer_id']);
                $customerName = $customer->name;
                $phone = $customer->phone;
            }

            $order = SpecialOrder::create([
                'customer_id' => $validated['customer_id'] ?? null,
                'customer_name' => $customerName,
                'phone' => $phone,
                'event_type' => $validated['event_type'],
                'description' => $validated['description'] ?? null,
                'delivery_date' => $validated['delivery_date'],
                'total_amount' => $totalAmount,
                'paid_amount' => 0,
                'remaining_amount' => $totalAmount,
                'notes' => $validated['notes'] ?? null,
                'user_id' => auth()->id(),
            ]);

            if (!empty($validated['items'])) {
                foreach ($validated['items'] as $itemData) {
                    $order->items()->create([
                        'product_id' => $itemData['product_id'],
                        'quantity' => $itemData['quantity'],
                        'unit_price' => $itemData['unit_price'],
                        'total_price' => $itemData['quantity'] * $itemData['unit_price'],
                        'notes' => $itemData['notes'] ?? null,
                    ]);
                }
            }

            if ($order->customer_id) {
                $order->customer->addOrderTransaction($totalAmount, $order->id, auth()->id());
            }

            if (!empty($validated['deposit_amount']) && $validated['deposit_amount'] > 0) {
                $order->addPayment(
                    $validated['deposit_amount'],
                    $validated['deposit_payment_method_id'],
                    auth()->id(),
                    'عربون'
                );
            }

            DB::commit();

            $order->load(['user:id,name', 'customer:id,name,phone']);
            $order->loadCount('payments');

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة الطلب بنجاح',
                'data' => $order
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إضافة الطلب'
            ], 500);
        }
    }

    public function show(SpecialOrder $specialOrder)
    {
        $specialOrder->load([
            'user:id,name',
            'customer:id,name,phone',
            'payments.paymentMethod',
            'payments.user:id,name',
            'items.product'
        ]);

        return response()->json([
            'success' => true,
            'data' => $specialOrder
        ]);
    }

    public function update(Request $request, SpecialOrder $specialOrder)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'required_without:customer_id|nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'event_type' => 'required|string|max:100',
            'description' => 'nullable|string',
            'delivery_date' => 'required|date',
            'total_amount' => 'required_without:items|nullable|numeric|min:0.01',
            'items' => 'nullable|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.notes' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => ['sometimes', Rule::in(array_keys(SpecialOrder::getStatuses()))],
        ], [
            'customer_name.required_without' => 'اسم العميل مطلوب',
            'event_type.required' => 'نوع المناسبة مطلوب',
            'delivery_date.required' => 'تاريخ التسليم مطلوب',
            'total_amount.required_without' => 'المبلغ الإجمالي مطلوب',
            'total_amount.min' => 'المبلغ الإجمالي يجب أن يكون أكبر من صفر',
        ]);

        $totalAmount = $validated['total_amount'] ?? $specialOrder->total_amount;

        if (!empty($validated['items'])) {
            $totalAmount = 0;
            foreach ($validated['items'] as $item) {
                $totalAmount += $item['quantity'] * $item['unit_price'];
            }
        }

        if ($totalAmount < $specialOrder->paid_amount) {
            return response()->json([
                'success' => false,
                'message' => 'المبلغ الإجمالي لا يمكن أن يكون أقل من المبلغ المدفوع'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $customerName = $validated['customer_name'] ?? $specialOrder->customer_name;
            $phone = $validated['phone'] ?? $specialOrder->phone;

            if (!empty($validated['customer_id'])) {
                $customer = Customer::find($validated['customer_id']);
                $customerName = $customer->name;
                $phone = $customer->phone;
            }

            $specialOrder->update([
                'customer_id' => $validated['customer_id'] ?? $specialOrder->customer_id,
                'customer_name' => $customerName,
                'phone' => $phone,
                'event_type' => $validated['event_type'],
                'description' => $validated['description'] ?? null,
                'delivery_date' => $validated['delivery_date'],
                'total_amount' => $totalAmount,
                'notes' => $validated['notes'] ?? null,
                'status' => $validated['status'] ?? $specialOrder->status,
            ]);

            if (isset($validated['items'])) {
                $specialOrder->items()->delete();

                foreach ($validated['items'] as $itemData) {
                    $specialOrder->items()->create([
                        'product_id' => $itemData['product_id'],
                        'quantity' => $itemData['quantity'],
                        'unit_price' => $itemData['unit_price'],
                        'total_price' => $itemData['quantity'] * $itemData['unit_price'],
                        'notes' => $itemData['notes'] ?? null,
                    ]);
                }
            }

            DB::commit();

            $specialOrder->load(['user:id,name', 'customer:id,name,phone']);
            $specialOrder->loadCount('payments');

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الطلب بنجاح',
                'data' => $specialOrder
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث الطلب'
            ], 500);
        }
    }

    public function destroy(SpecialOrder $specialOrder)
    {
        DB::beginTransaction();
        try {
            $customerName = $specialOrder->display_name;

            if ($specialOrder->customer_id) {
                CustomerTransaction::where('reference_type', SpecialOrder::class)
                    ->where('reference_id', $specialOrder->id)
                    ->delete();

                $lastTransaction = CustomerTransaction::where('customer_id', $specialOrder->customer_id)
                    ->orderBy('id', 'desc')
                    ->first();

                $specialOrder->customer->update([
                    'balance' => $lastTransaction ? $lastTransaction->balance_after : 0
                ]);
            }

            $specialOrder->payments()->delete();
            $specialOrder->items()->delete();
            $specialOrder->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "تم حذف طلب ({$customerName}) بنجاح"
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف الطلب'
            ], 500);
        }
    }

    public function updateStatus(Request $request, SpecialOrder $specialOrder)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys(SpecialOrder::getStatuses()))],
        ]);

        $specialOrder->update(['status' => $validated['status']]);

        $statusName = SpecialOrder::getStatuses()[$validated['status']];

        return response()->json([
            'success' => true,
            'message' => "تم تحديث الحالة إلى ({$statusName})",
            'data' => $specialOrder
        ]);
    }

    public function payments(SpecialOrder $specialOrder)
    {
        $payments = $specialOrder->payments()
            ->with(['paymentMethod', 'user:id,name'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $payments
        ]);
    }

    public function addPayment(Request $request, SpecialOrder $specialOrder)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $specialOrder->remaining_amount,
            'payment_method_id' => 'required|exists:payment_methods,id',
            'notes' => 'nullable|string|max:500',
        ], [
            'amount.required' => 'المبلغ مطلوب',
            'amount.min' => 'المبلغ يجب أن يكون أكبر من صفر',
            'amount.max' => 'المبلغ لا يمكن أن يتجاوز المتبقي (' . number_format($specialOrder->remaining_amount, 2) . ')',
            'payment_method_id.required' => 'طريقة الدفع مطلوبة',
        ]);

        $payment = $specialOrder->addPayment(
            $validated['amount'],
            $validated['payment_method_id'],
            auth()->id(),
            $validated['notes'] ?? null
        );

        $payment->load(['paymentMethod', 'user:id,name']);
        $specialOrder->refresh();

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة الدفعة بنجاح',
            'data' => [
                'payment' => $payment,
                'order' => $specialOrder
            ]
        ], 201);
    }

    public function printReceipt(SpecialOrder $specialOrder)
    {
        $specialOrder->load([
            'user:id,name',
            'customer:id,name,phone',
            'payments.paymentMethod',
            'payments.user:id,name',
            'items.product'
        ]);

        return view('special-orders.print', compact('specialOrder'));
    }

    public function printPaymentReceipt(SpecialOrderPayment $payment)
    {
        $payment->load(['specialOrder.customer', 'paymentMethod', 'user:id,name']);

        return view('special-orders.print-payment', compact('payment'));
    }

    public function storeEventType(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:event_types,name',
        ], [
            'name.required' => 'اسم المناسبة مطلوب',
            'name.unique' => 'نوع المناسبة موجود مسبقاً',
        ]);

        $maxOrder = EventType::max('sort_order') ?? 0;

        $eventType = EventType::create([
            'name' => $validated['name'],
            'sort_order' => $maxOrder + 1,
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة نوع المناسبة بنجاح',
            'data' => $eventType
        ], 201);
    }
}
