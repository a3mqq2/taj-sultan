<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class OrderController extends Controller
{
    public function index()
    {
        $statuses = [
            'pending' => 'قيد الانتظار',
            'paid' => 'مدفوع',
            'cancelled' => 'ملغي',
        ];

        $paymentMethods = PaymentMethod::where('is_active', true)->get();

        return view('orders.index', compact('statuses', 'paymentMethods'));
    }

    public function data(Request $request)
    {
        $query = Order::with(['items', 'payments.paymentMethod', 'customer', 'paidByUser', 'posPoint'])
            ->whereNull('merged_into');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($cq) use ($search) {
                        $cq->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('delivery_type')) {
            $query->where('delivery_type', $request->delivery_type);
        }

        if ($request->filled('payment_method')) {
            $query->whereHas('payments', function ($q) use ($request) {
                $q->where('payment_method_id', $request->payment_method);
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('paid_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('paid_at', '<=', $request->date_to);
        }

        if ($request->filled('has_credit')) {
            if ($request->has_credit === '1') {
                $query->where('credit_amount', '>', 0);
            } else {
                $query->where(function ($q) {
                    $q->whereNull('credit_amount')->orWhere('credit_amount', '<=', 0);
                });
            }
        }

        $sortField = $request->get('sort', 'paid_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $orders = $query->paginate(20);

        $data = $orders->map(function ($order) {
            return [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'customer_name' => $order->customer ? $order->customer->name : '-',
                'customer_phone' => $order->customer ? $order->customer->phone : null,
                'total' => $order->total,
                'discount' => $order->discount,
                'credit_amount' => $order->credit_amount,
                'delivery_type' => $order->delivery_type,
                'delivery_phone' => $order->delivery_phone,
                'status' => $order->status,
                'paid_at' => $order->paid_at ? $order->paid_at->format('Y-m-d H:i') : null,
                'cashier_name' => $order->paidByUser ? $order->paidByUser->name : '-',
                'pos_point' => $order->posPoint ? $order->posPoint->name : '-',
                'items_count' => $order->items->count(),
                'payments' => $order->payments->map(function ($p) {
                    return [
                        'method' => $p->paymentMethod ? $p->paymentMethod->name : '-',
                        'amount' => $p->amount,
                    ];
                }),
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
            ],
        ]);
    }

    public function show($id)
    {
        $order = Order::with(['items.product', 'payments.paymentMethod', 'customer', 'paidByUser', 'posPoint'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $order,
        ]);
    }

    public function export(Request $request)
    {
        $query = Order::with(['items', 'payments.paymentMethod', 'customer', 'paidByUser', 'posPoint'])
            ->whereNull('merged_into');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($cq) use ($search) {
                        $cq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('paid_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('paid_at', '<=', $request->date_to);
        }

        $orders = $query->orderBy('paid_at', 'desc')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setRightToLeft(true);

        $headers = ['#', 'رقم الفاتورة', 'الزبون', 'الإجمالي', 'الخصم', 'الآجل', 'نوع الاستلام', 'الحالة', 'الكاشير', 'التاريخ'];
        $col = 1;
        foreach ($headers as $header) {
            $sheet->setCellValueByColumnAndRow($col++, 1, $header);
        }

        $row = 2;
        foreach ($orders as $index => $order) {
            $sheet->setCellValueByColumnAndRow(1, $row, $index + 1);
            $sheet->setCellValueByColumnAndRow(2, $row, $order->order_number);
            $sheet->setCellValueByColumnAndRow(3, $row, $order->customer ? $order->customer->name : '-');
            $sheet->setCellValueByColumnAndRow(4, $row, $order->total);
            $sheet->setCellValueByColumnAndRow(5, $row, $order->discount ?? 0);
            $sheet->setCellValueByColumnAndRow(6, $row, $order->credit_amount ?? 0);
            $sheet->setCellValueByColumnAndRow(7, $row, $order->delivery_type === 'delivery' ? 'توصيل' : 'استلام');
            $sheet->setCellValueByColumnAndRow(8, $row, $order->status === 'paid' ? 'مدفوع' : ($order->status === 'cancelled' ? 'ملغي' : 'قيد الانتظار'));
            $sheet->setCellValueByColumnAndRow(9, $row, $order->paidByUser ? $order->paidByUser->name : '-');
            $sheet->setCellValueByColumnAndRow(10, $row, $order->paid_at ? $order->paid_at->format('Y-m-d H:i') : '-');
            $row++;
        }

        $filename = 'orders_' . now()->format('Y-m-d_H-i') . '.xlsx';
        $path = storage_path('app/public/' . $filename);

        $writer = new Xlsx($spreadsheet);
        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend();
    }
}
