<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPayment;
use App\Models\OrderMerge;
use App\Models\Product;
use App\Models\PaymentMethod;
use App\Models\Customer;
use App\Models\SpecialOrder;
use App\Models\SpecialOrderItem;
use App\Models\SpecialOrderPayment;
use App\Models\EventType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashierController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::active()->ordered()->get();

        return view('cashier.index', compact('paymentMethods'));
    }

    public function fetchOrder(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string',
        ]);

        $order = Order::with('items')
            ->where('order_number', $request->order_number)
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'الطلب غير موجود',
            ], 404);
        }

        if ($order->status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'هذا الطلب مدفوع مسبقاً',
            ], 400);
        }

        if ($order->status === 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'هذا الطلب ملغي',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'total' => $order->total,
                'status' => $order->status,
                'created_at' => $order->created_at->format('Y-m-d H:i'),
                'items' => $order->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'product_name' => $item->product_name,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'total' => $item->total,
                        'is_weight' => $item->is_weight,
                    ];
                }),
            ],
        ]);
    }

    public function newInvoice(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.001',
            'gross_total' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:5',
            'total' => 'required|numeric|min:0',
            'merged_order_ids' => 'nullable|array',
            'merged_order_ids.*' => 'exists:orders,id',
        ]);

        try {
            $order = DB::transaction(function () use ($validated) {
                $order = Order::create([
                    'order_number' => Order::generateOrderNumber(),
                    'user_id' => auth()->id(),
                    'total' => $validated['total'],
                    'discount' => $validated['discount'] ?? 0,
                    'status' => 'pending',
                ]);

                foreach ($validated['items'] as $item) {
                    $product = Product::find($item['product_id']);
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'price' => $product->price,
                        'quantity' => $item['quantity'],
                        'is_weight' => $product->type === 'weight',
                        'total' => $product->price * $item['quantity'],
                    ]);
                }

                if (!empty($validated['merged_order_ids'])) {
                    foreach ($validated['merged_order_ids'] as $mergedOrderId) {
                        $mergedOrder = Order::find($mergedOrderId);
                        if ($mergedOrder && !$mergedOrder->isMerged() && $mergedOrder->status === 'pending') {
                            $mergedOrder->update(['merged_into' => $order->id]);

                            OrderMerge::create([
                                'parent_order_id' => $order->id,
                                'child_order_id' => $mergedOrderId,
                                'merged_by' => auth()->id(),
                            ]);
                        }
                    }
                }

                return $order;
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'total' => $order->total,
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('newInvoice error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء الفاتورة: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function addWeightBarcode(Request $request)
    {
        $validated = $request->validate([
            'barcode' => 'required|string|min:13|max:13',
            'order_id' => 'nullable|exists:orders,id',
        ]);

        $barcode = $validated['barcode'];

        $prefix = substr($barcode, 0, 2);
        if ($prefix !== '99') {
            return response()->json([
                'success' => false,
                'message' => 'باركود غير صالح',
            ], 400);
        }

        $productCode = substr($barcode, 2, 5);
        $weightRaw = substr($barcode, 7, 5);
        $weight = (float) $weightRaw / 1000;

        $product = Product::where('type', 'weight')
            ->where(function($query) use ($productCode) {
                $query->where('barcode', $productCode)
                      ->orWhere('barcode', 'LIKE', '%' . $productCode);
            })
            ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'المنتج غير موجود',
            ], 404);
        }

        $itemTotal = $product->price * $weight;

        return response()->json([
            'success' => true,
            'data' => [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'price' => $product->price,
                'quantity' => $weight,
                'total' => $itemTotal,
                'is_weight' => true,
            ],
        ]);
    }

    public function addWeightManual(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0.001',
        ]);

        $product = Product::find($validated['product_id']);

        if ($product->type !== 'weight') {
            return response()->json([
                'success' => false,
                'message' => 'هذا المنتج ليس منتج وزن',
            ], 400);
        }

        $itemTotal = $product->price * $validated['quantity'];

        return response()->json([
            'success' => true,
            'data' => [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'price' => $product->price,
                'quantity' => $validated['quantity'],
                'total' => $itemTotal,
                'is_weight' => true,
            ],
        ]);
    }

    public function addWeightItem(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0.001',
        ]);

        $order = Order::find($validated['order_id']);

        if ($order->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن التعديل على هذا الطلب',
            ], 400);
        }

        $product = Product::find($validated['product_id']);

        if ($product->type !== 'weight') {
            return response()->json([
                'success' => false,
                'message' => 'هذا المنتج ليس منتج وزن',
            ], 400);
        }

        try {
            DB::transaction(function () use ($order, $product, $validated) {
                $itemTotal = $product->price * $validated['quantity'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $validated['quantity'],
                    'is_weight' => true,
                    'total' => $itemTotal,
                ]);

                $order->update([
                    'total' => $order->items()->sum('total'),
                ]);
            });

            $order->refresh();
            $order->load('items');

            return response()->json([
                'success' => true,
                'data' => [
                    'order' => [
                        'id' => $order->id,
                        'order_number' => $order->order_number,
                        'total' => $order->total,
                        'items' => $order->items->map(function ($item) {
                            return [
                                'id' => $item->id,
                                'product_name' => $item->product_name,
                                'quantity' => $item->quantity,
                                'price' => $item->price,
                                'total' => $item->total,
                                'is_weight' => $item->is_weight,
                            ];
                        }),
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إضافة الصنف',
            ], 500);
        }
    }

    public function pay(Request $request)
    {
        $isCredit = $request->boolean('is_credit');

        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'discount' => 'nullable|numeric|min:0|max:5',
            'payments' => $isCredit ? 'nullable|array' : 'required|array|min:1',
            'payments.*.payment_method_id' => 'required|exists:payment_methods,id',
            'payments.*.amount' => 'required|numeric|min:0',
            'is_credit' => 'nullable|boolean',
            'customer_id' => 'required_if:is_credit,true|nullable|exists:customers,id',
            'paid_amount' => 'nullable|numeric|min:0',
            'delivery_type' => 'nullable|in:pickup,delivery',
            'delivery_phone' => 'required_if:delivery_type,delivery|nullable|string|max:20',
        ]);

        $order = Order::with('items')->find($validated['order_id']);

        if ($order->status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'هذا الطلب مدفوع مسبقاً',
            ], 400);
        }

        if ($order->status === 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'هذا الطلب ملغي',
            ], 400);
        }

        $discount = $validated['discount'] ?? 0;
        $grossTotal = $order->items->sum('total');
        $expectedTotal = $grossTotal - $discount;

        $payments = $validated['payments'] ?? [];
        $totalPayments = array_sum(array_column($payments, 'amount'));

        if (!$isCredit) {
            if (empty($payments) || $totalPayments <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'يجب إدخال مبلغ الدفع',
                ], 400);
            }

       
        }

        if ($isCredit && $totalPayments > $expectedTotal) {
            return response()->json([
                'success' => false,
                'message' => 'المبلغ المدفوع أكبر من الإجمالي',
            ], 400);
        }

        $creditAmount = $isCredit ? ($expectedTotal - $totalPayments) : 0;
        $customerId = $isCredit ? $validated['customer_id'] : null;

        try {
            DB::transaction(function () use ($order, $payments, $totalPayments, $discount, $expectedTotal, $isCredit, $creditAmount, $customerId, $validated) {
                foreach ($payments as $payment) {
                    if ($payment['amount'] > 0) {
                        OrderPayment::create([
                            'order_id' => $order->id,
                            'payment_method_id' => $payment['payment_method_id'],
                            'amount' => $payment['amount'],
                        ]);
                    }
                }

                $order->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                    'paid_by' => auth()->id(),
                    'amount_received' => $totalPayments,
                    'discount' => $discount,
                    'total' => $expectedTotal,
                    'customer_id' => $customerId,
                    'credit_amount' => $creditAmount,
                    'delivery_type' => $validated['delivery_type'] ?? 'pickup',
                    'delivery_phone' => $validated['delivery_phone'] ?? null,
                ]);

                if ($isCredit && $creditAmount > 0 && $customerId) {
                    $customer = Customer::find($customerId);
                    $customer->addCreditOrderTransaction($creditAmount, $order->id, auth()->id());
                }
            });

            $order->refresh();
            $order->load(['payments.paymentMethod', 'items', 'customer']);

            $paymentsData = $order->payments->map(function ($payment) {
                return [
                    'method' => $payment->paymentMethod->name,
                    'amount' => $payment->amount,
                ];
            });

            $grossTotal = $order->items->sum('total');
            $discount = $order->discount ?? 0;

            return response()->json([
                'success' => true,
                'data' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'gross_total' => $grossTotal,
                    'discount' => $discount,
                    'total' => $order->total,
                    'credit_amount' => $order->credit_amount,
                    'customer_name' => $order->customer ? $order->customer->name : null,
                    'delivery_type' => $order->delivery_type,
                    'delivery_phone' => $order->delivery_phone,
                    'payments' => $paymentsData,
                    'paid_at' => $order->paid_at->format('Y-m-d H:i'),
                    'cashier_name' => auth()->user()->name,
                    'items' => $order->items->map(function ($item) {
                        return [
                            'product_name' => $item->product_name,
                            'quantity' => $item->quantity,
                            'price' => $item->price,
                            'total' => $item->total,
                            'is_weight' => $item->is_weight,
                        ];
                    }),
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('pay error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تسجيل الدفع',
            ], 500);
        }
    }

    public function weightProducts()
    {
        $products = Product::active()
            ->where('type', 'weight')
            ->orderBy('name')
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'barcode' => $product->barcode,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    public function removeItem(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'item_id' => 'required|exists:order_items,id',
        ]);

        $order = Order::find($validated['order_id']);

        if ($order->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن التعديل على هذا الطلب',
            ], 400);
        }

        $item = OrderItem::where('id', $validated['item_id'])
            ->where('order_id', $order->id)
            ->first();

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'الصنف غير موجود',
            ], 404);
        }

        try {
            DB::transaction(function () use ($order, $item) {
                $item->delete();

                $order->update([
                    'total' => $order->items()->sum('total'),
                ]);
            });

            $order->refresh();
            $order->load('items');

            return response()->json([
                'success' => true,
                'data' => [
                    'order' => [
                        'id' => $order->id,
                        'order_number' => $order->order_number,
                        'total' => $order->total,
                        'items' => $order->items->map(function ($item) {
                            return [
                                'id' => $item->id,
                                'product_name' => $item->product_name,
                                'quantity' => $item->quantity,
                                'price' => $item->price,
                                'total' => $item->total,
                                'is_weight' => $item->is_weight,
                            ];
                        }),
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف الصنف',
            ], 500);
        }
    }

    public function specialOrders()
    {
        $paymentMethods = PaymentMethod::where('is_active', true)->orderBy('sort_order')->get();
        $eventTypes = EventType::where('is_active', true)->orderBy('name')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('cashier.special-orders', compact('paymentMethods', 'eventTypes', 'products'));
    }

    public function specialOrderProducts()
    {
        $products = Product::where('is_active', true)
            ->orderBy('name')
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'type' => $product->type,
                    'barcode' => $product->barcode,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    public function specialOrderCustomers(Request $request)
    {
        $search = $request->get('q', '');

        $customers = Customer::where('is_active', true)
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->limit(20)
            ->get()
            ->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'phone' => $customer->phone,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $customers,
        ]);
    }

    public function storeSpecialOrder(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'event_type' => 'required|string',
            'delivery_date' => 'required|date',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.001',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.total' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'initial_payment' => 'nullable|numeric|min:0',
            'payment_method_id' => 'required_with:initial_payment|nullable|exists:payment_methods,id',
        ]);

        try {
            $specialOrder = DB::transaction(function () use ($validated) {
                $order = SpecialOrder::create([
                    'customer_id' => $validated['customer_id'],
                    'customer_name' => $validated['customer_name'],
                    'phone' => $validated['phone'],
                    'event_type' => $validated['event_type'],
                    'delivery_date' => $validated['delivery_date'],
                    'description' => $validated['description'] ?? null,
                    'notes' => $validated['notes'] ?? null,
                    'total_amount' => $validated['total_amount'],
                    'paid_amount' => $validated['initial_payment'] ?? 0,
                    'remaining_amount' => $validated['total_amount'] - ($validated['initial_payment'] ?? 0),
                    'status' => SpecialOrder::STATUS_IN_PROGRESS,
                    'user_id' => auth()->id(),
                ]);

                foreach ($validated['items'] as $item) {
                    $product = Product::find($item['product_id']);
                    SpecialOrderItem::create([
                        'special_order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['price'],
                        'total_price' => $item['total'],
                        'is_weight' => $product->type === 'weight',
                    ]);
                }

                if (!empty($validated['initial_payment']) && $validated['initial_payment'] > 0) {
                    SpecialOrderPayment::create([
                        'special_order_id' => $order->id,
                        'payment_method_id' => $validated['payment_method_id'],
                        'amount' => $validated['initial_payment'],
                        'user_id' => auth()->id(),
                        'notes' => 'عربون',
                    ]);
                }

                return $order;
            });

            $specialOrder->load(['items', 'payments.paymentMethod']);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $specialOrder->id,
                    'customer_name' => $specialOrder->customer_name,
                    'phone' => $specialOrder->phone,
                    'event_type' => $specialOrder->event_type_name,
                    'delivery_date' => $specialOrder->delivery_date->format('Y-m-d'),
                    'total_amount' => $specialOrder->total_amount,
                    'paid_amount' => $specialOrder->paid_amount,
                    'remaining_amount' => $specialOrder->remaining_amount,
                    'status' => $specialOrder->status_name,
                    'cashier_name' => auth()->user()->name,
                    'created_at' => $specialOrder->created_at->format('Y-m-d H:i'),
                    'items' => $specialOrder->items->map(function ($item) {
                        return [
                            'product_name' => $item->product_name,
                            'quantity' => $item->quantity,
                            'price' => $item->unit_price,
                            'total' => $item->total_price,
                            'is_weight' => $item->is_weight,
                        ];
                    }),
                    'payments' => $specialOrder->payments->map(function ($payment) {
                        return [
                            'method' => $payment->paymentMethod->name ?? '-',
                            'amount' => $payment->amount,
                            'notes' => $payment->notes,
                        ];
                    }),
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('storeSpecialOrder error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء الطلبية: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function fetchSpecialOrder(Request $request)
    {
        $validated = $request->validate([
            'search' => 'required|string',
        ]);

        $search = trim($validated['search']);

        $query = SpecialOrder::with(['items', 'payments.paymentMethod', 'customer']);

        if (is_numeric($search)) {
            $query->where('id', (int) $search);
        } else {
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $order = $query->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'الطلبية غير موجودة',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $order->id,
                'customer_name' => $order->display_name,
                'phone' => $order->display_phone,
                'event_type' => $order->event_type_name,
                'delivery_date' => $order->delivery_date->format('Y-m-d'),
                'total_amount' => $order->total_amount,
                'paid_amount' => $order->paid_amount,
                'remaining_amount' => $order->remaining_amount,
                'status' => $order->status,
                'status_name' => $order->status_name,
                'description' => $order->description,
                'notes' => $order->notes,
                'created_at' => $order->created_at->format('Y-m-d H:i'),
                'items' => $order->items->map(function ($item) {
                    return [
                        'product_name' => $item->product_name,
                        'quantity' => $item->quantity,
                        'price' => $item->unit_price,
                        'total' => $item->total_price,
                        'is_weight' => $item->is_weight,
                    ];
                }),
                'payments' => $order->payments->map(function ($payment) {
                    return [
                        'id' => $payment->id,
                        'method' => $payment->paymentMethod->name ?? '-',
                        'amount' => $payment->amount,
                        'notes' => $payment->notes,
                        'created_at' => $payment->created_at->format('Y-m-d H:i'),
                    ];
                }),
            ],
        ]);
    }

    public function addSpecialOrderPayment(Request $request)
    {
        $validated = $request->validate([
            'special_order_id' => 'required|exists:special_orders,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'amount' => 'required|numeric|min:0.001',
            'notes' => 'nullable|string',
        ]);

        $order = SpecialOrder::find($validated['special_order_id']);

        if ($order->status === SpecialOrder::STATUS_CANCELLED) {
            return response()->json([
                'success' => false,
                'message' => 'هذه الطلبية ملغية',
            ], 400);
        }

        if ($order->status === SpecialOrder::STATUS_DELIVERED) {
            return response()->json([
                'success' => false,
                'message' => 'هذه الطلبية تم تسليمها',
            ], 400);
        }

        if ($validated['amount'] > $order->remaining_amount + 0.001) {
            return response()->json([
                'success' => false,
                'message' => 'المبلغ أكبر من المتبقي',
            ], 400);
        }

        try {
            DB::transaction(function () use ($order, $validated) {
                SpecialOrderPayment::create([
                    'special_order_id' => $order->id,
                    'payment_method_id' => $validated['payment_method_id'],
                    'amount' => $validated['amount'],
                    'user_id' => auth()->id(),
                    'notes' => $validated['notes'] ?? null,
                ]);

                $order->paid_amount += $validated['amount'];
                $order->remaining_amount = $order->total_amount - $order->paid_amount;

                if ($order->remaining_amount <= 0) {
                    $order->status = SpecialOrder::STATUS_DELIVERED;
                }

                $order->save();
            });

            $order->refresh();
            $order->load(['items', 'payments.paymentMethod']);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $order->id,
                    'customer_name' => $order->display_name,
                    'phone' => $order->display_phone,
                    'event_type' => $order->event_type_name,
                    'delivery_date' => $order->delivery_date->format('Y-m-d'),
                    'total_amount' => $order->total_amount,
                    'paid_amount' => $order->paid_amount,
                    'remaining_amount' => $order->remaining_amount,
                    'status' => $order->status,
                    'status_name' => $order->status_name,
                    'items' => $order->items->map(function ($item) {
                        return [
                            'product_name' => $item->product_name,
                            'quantity' => $item->quantity,
                            'price' => $item->unit_price,
                            'total' => $item->total_price,
                            'is_weight' => $item->is_weight,
                        ];
                    }),
                    'payments' => $order->payments->map(function ($payment) {
                        return [
                            'id' => $payment->id,
                            'method' => $payment->paymentMethod->name ?? '-',
                            'amount' => $payment->amount,
                            'notes' => $payment->notes,
                            'created_at' => $payment->created_at->format('Y-m-d H:i'),
                        ];
                    }),
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('addSpecialOrderPayment error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إضافة الدفعة',
            ], 500);
        }
    }

    public function printSpecialOrder($id)
    {
        $order = SpecialOrder::with(['items', 'payments.paymentMethod', 'user'])
            ->findOrFail($id);

        return view('cashier.special-order-print', compact('order'));
    }

    public function cancelSpecialOrder($id)
    {
        $order = SpecialOrder::findOrFail($id);

        if ($order->status === SpecialOrder::STATUS_CANCELLED) {
            return response()->json([
                'success' => false,
                'message' => 'هذه الطلبية ملغية مسبقاً'
            ]);
        }

        if ($order->status === SpecialOrder::STATUS_COMPLETED) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن إلغاء طلبية مكتملة'
            ]);
        }

        $order->status = SpecialOrder::STATUS_CANCELLED;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'تم إلغاء الطلبية بنجاح'
        ]);
    }

    public function findInvoice(Request $request)
    {
        $orderNumber = $request->input('order_number');

        $order = Order::with('items')
            ->where('order_number', $orderNumber)
            ->orWhere('id', $orderNumber)
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'لم يتم العثور على الفاتورة'
            ]);
        }

        if ($order->status === 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'هذه الفاتورة ملغية مسبقاً'
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'total' => $order->total,
                'paid_at' => $order->paid_at ? $order->paid_at->format('Y-m-d H:i') : $order->created_at->format('Y-m-d H:i'),
                'items' => $order->items->map(function ($item) {
                    return [
                        'product_name' => $item->product_name,
                        'quantity' => $item->is_weight ? number_format($item->quantity, 3) : $item->quantity,
                    ];
                })
            ]
        ]);
    }

    public function deleteInvoice($id)
    {
        $order = Order::with(['items', 'payments'])->find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'لم يتم العثور على الفاتورة'
            ]);
        }

        if ($order->status === 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'هذه الفاتورة ملغية مسبقاً'
            ]);
        }

        $order->status = 'cancelled';
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'تم إلغاء الفاتورة بنجاح'
        ]);
    }

    public function searchCustomers(Request $request)
    {
        $search = $request->get('q', '');

        $customers = Customer::where('is_active', true)
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->limit(20)
            ->get()
            ->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'phone' => $customer->phone,
                    'balance' => $customer->balance,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $customers,
        ]);
    }

    public function createQuickCustomer(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $customer = Customer::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'phone' => $customer->phone,
                'balance' => 0,
            ],
        ]);
    }

    public function customersPage()
    {
        $paymentMethods = PaymentMethod::active()->ordered()->get();

        return view('cashier.customers', compact('paymentMethods'));
    }

    public function customersData(Request $request)
    {
        $search = $request->get('search', '');

        $customers = Customer::where('is_active', true)
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->get()
            ->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'phone' => $customer->phone,
                    'balance' => $customer->balance,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $customers,
        ]);
    }

    public function customerDetails(Customer $customer)
    {
        $creditOrders = $customer->orders()
            ->where('credit_amount', '>', 0)
            ->with('items')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'total' => $order->total,
                    'credit_amount' => $order->credit_amount,
                    'created_at' => $order->created_at->format('Y-m-d H:i'),
                ];
            });

        $transactions = $customer->transactions()
            ->with('paymentMethod')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'type' => $transaction->type,
                    'type_name' => $transaction->type === 'payment' ? 'تسديد' : 'دين',
                    'amount' => $transaction->amount,
                    'balance_after' => $transaction->balance_after,
                    'payment_method' => $transaction->paymentMethod ? $transaction->paymentMethod->name : null,
                    'created_at' => $transaction->created_at->format('Y-m-d H:i'),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'phone' => $customer->phone,
                'balance' => $customer->balance,
                'credit_orders' => $creditOrders,
                'transactions' => $transactions,
            ],
        ]);
    }

    public function payDebt(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.001',
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        $currentBalance = $customer->balance;

        if ($currentBalance >= 0) {
            return response()->json([
                'success' => false,
                'message' => 'لا يوجد دين على هذا الزبون',
            ], 400);
        }

        $debtAmount = abs($currentBalance);
        if ($validated['amount'] > $debtAmount + 0.001) {
            return response()->json([
                'success' => false,
                'message' => 'المبلغ أكبر من الدين المتبقي',
            ], 400);
        }

        try {
            $customer->addPayment(
                $validated['amount'],
                $validated['payment_method_id'],
                auth()->id()
            );

            $customer->refresh();

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'new_balance' => $customer->balance,
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('payDebt error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تسجيل الدفعة',
            ], 500);
        }
    }

    public function fetchOrderForMerge(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string',
        ]);

        $orderNumber = trim($request->order_number);

        if (strlen($orderNumber) === 8 && is_numeric($orderNumber)) {
            $orderId = (int) ltrim($orderNumber, '0');
            $order = Order::with('items')->find($orderId);
        } else {
            $order = Order::with('items')
                ->where('order_number', $orderNumber)
                ->first();
        }

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'الفاتورة غير موجودة',
            ], 404);
        }

        if ($order->status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'هذه الفاتورة مدفوعة مسبقاً',
            ], 400);
        }

        if ($order->status === 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'هذه الفاتورة ملغية',
            ], 400);
        }

        if ($order->isMerged()) {
            return response()->json([
                'success' => false,
                'message' => 'هذه الفاتورة مدمجة مسبقاً في فاتورة أخرى',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'total' => $order->total,
                'discount' => $order->discount,
                'status' => $order->status,
                'pos_point' => $order->posPoint ? $order->posPoint->name : null,
                'created_at' => $order->created_at->format('Y-m-d H:i'),
                'items' => $order->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'product_id' => $item->product_id,
                        'product_name' => $item->product_name,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'total' => $item->total,
                        'is_weight' => $item->is_weight,
                    ];
                }),
            ],
        ]);
    }

    public function mergeOrders(Request $request)
    {
        $validated = $request->validate([
            'order_ids' => 'required|array|min:2',
            'order_ids.*' => 'required|exists:orders,id',
        ]);

        $orderIds = $validated['order_ids'];

        $orders = Order::with('items')->whereIn('id', $orderIds)->get();

        foreach ($orders as $order) {
            if ($order->status === 'paid') {
                return response()->json([
                    'success' => false,
                    'message' => "الفاتورة رقم {$order->order_number} مدفوعة مسبقاً",
                ], 400);
            }

            if ($order->status === 'cancelled') {
                return response()->json([
                    'success' => false,
                    'message' => "الفاتورة رقم {$order->order_number} ملغية",
                ], 400);
            }

            if ($order->isMerged()) {
                return response()->json([
                    'success' => false,
                    'message' => "الفاتورة رقم {$order->order_number} مدمجة مسبقاً",
                ], 400);
            }
        }

        try {
            $mergedOrder = DB::transaction(function () use ($orders) {
                $totalItems = collect();
                $totalDiscount = 0;

                foreach ($orders as $order) {
                    foreach ($order->items as $item) {
                        $totalItems->push($item);
                    }
                    $totalDiscount += $order->discount ?? 0;
                }

                $grossTotal = $totalItems->sum('total');
                $netTotal = $grossTotal - $totalDiscount;

                $newOrder = Order::create([
                    'order_number' => Order::generateOrderNumber(),
                    'user_id' => auth()->id(),
                    'total' => $netTotal,
                    'discount' => $totalDiscount,
                    'status' => 'pending',
                ]);

                foreach ($totalItems as $item) {
                    OrderItem::create([
                        'order_id' => $newOrder->id,
                        'product_id' => $item->product_id,
                        'product_name' => $item->product_name,
                        'price' => $item->price,
                        'quantity' => $item->quantity,
                        'is_weight' => $item->is_weight,
                        'total' => $item->total,
                    ]);
                }

                foreach ($orders as $order) {
                    $order->update(['merged_into' => $newOrder->id]);

                    OrderMerge::create([
                        'parent_order_id' => $newOrder->id,
                        'child_order_id' => $order->id,
                        'merged_by' => auth()->id(),
                    ]);
                }

                return $newOrder;
            });

            $mergedOrder->load('items');

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $mergedOrder->id,
                    'order_number' => $mergedOrder->order_number,
                    'total' => $mergedOrder->total,
                    'discount' => $mergedOrder->discount,
                    'status' => $mergedOrder->status,
                    'created_at' => $mergedOrder->created_at->format('Y-m-d H:i'),
                    'items' => $mergedOrder->items->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'product_name' => $item->product_name,
                            'quantity' => $item->quantity,
                            'price' => $item->price,
                            'total' => $item->total,
                            'is_weight' => $item->is_weight,
                        ];
                    }),
                    'merged_orders_count' => count($orders),
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('mergeOrders error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء دمج الفواتير',
            ], 500);
        }
    }
}
