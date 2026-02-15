<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPayment;
use App\Models\PosPoint;
use App\Models\PaymentMethod;
use App\Models\SpecialOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesReportController extends Controller
{
    public function index()
    {
        $posPoints = PosPoint::where('active', true)->get();
        $paymentMethods = PaymentMethod::where('is_active', true)->orderBy('sort_order')->get();

        return view('reports.sales.index', compact('posPoints', 'paymentMethods'));
    }

    public function data(Request $request)
    {
        $query = Order::with(['posPoint', 'payments.paymentMethod'])
            ->whereIn('status', ['paid', 'delivering']);

        $this->applyFilters($query, $request);

        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        $allowedSorts = ['created_at', 'total', 'discount'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection);
        }

        $perPage = $request->get('per_page', 20);
        $orders = $query->paginate($perPage);

        $data = $orders->map(function ($order) {
            $netTotal = $order->total - ($order->discount ?? 0);
            $paymentMethodNames = $order->payments->map(fn($p) => $p->paymentMethod->name ?? '-')->join('، ');

            return [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'created_at' => $order->created_at->format('Y-m-d H:i'),
                'pos_point' => $order->posPoint->name ?? '-',
                'total' => number_format($order->total, 3),
                'discount' => number_format($order->discount ?? 0, 3),
                'net_total' => number_format($netTotal, 3),
                'payment_methods' => $paymentMethodNames ?: '-',
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

    public function summary(Request $request)
    {
        $query = Order::where('status', 'paid');
        $this->applyFilters($query, $request);

        $stats = $query->selectRaw('
            COUNT(*) as orders_count,
            COALESCE(SUM(total), 0) as total_sales,
            COALESCE(SUM(COALESCE(discount, 0)), 0) as total_discount,
            COALESCE(SUM(total - COALESCE(discount, 0)), 0) as net_sales
        ')->first();

        $ordersCount = $stats->orders_count ?? 0;
        $averageOrder = $ordersCount > 0 ? ($stats->net_sales / $ordersCount) : 0;

        return response()->json([
            'success' => true,
            'data' => [
                'total_sales' => number_format($stats->net_sales, 3),
                'orders_count' => $ordersCount,
                'average_order' => number_format($averageOrder, 3),
                'total_discount' => number_format($stats->total_discount, 3),
            ]
        ]);
    }

    public function chartData(Request $request)
    {
        $query = Order::where('status', 'paid');
        $this->applyFilters($query, $request);

        $dailySales = $query->clone()
            ->selectRaw('DATE(created_at) as date, SUM(total - COALESCE(discount, 0)) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(fn($item) => [
                'date' => $item->date,
                'total' => round($item->total, 3)
            ]);

        $paymentQuery = OrderPayment::query()
            ->whereHas('order', function ($q) use ($request) {
                $q->whereIn('status', ['paid', 'delivering']);
                $this->applyFilters($q, $request);
            })
            ->join('payment_methods', 'order_payments.payment_method_id', '=', 'payment_methods.id')
            ->selectRaw('payment_methods.name, SUM(order_payments.amount) as total')
            ->groupBy('payment_methods.id', 'payment_methods.name')
            ->get();

        $paymentDistribution = $paymentQuery->map(fn($item) => [
            'name' => $item->name,
            'total' => round($item->total, 3)
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'daily_sales' => $dailySales,
                'payment_distribution' => $paymentDistribution,
            ]
        ]);
    }

    public function productsData(Request $request)
    {
        $query = OrderItem::query()
            ->whereHas('order', function ($q) use ($request) {
                $q->whereIn('status', ['paid', 'delivering']);
                $this->applyFilters($q, $request);
            })
            ->selectRaw('
                COALESCE(products.name, order_items.product_name) as name,
                SUM(order_items.quantity) as total_quantity,
                SUM(order_items.total) as total_sales
            ')
            ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
            ->groupBy(DB::raw('COALESCE(products.name, order_items.product_name)'))
            ->orderByDesc('total_sales')
            ->limit(10)
            ->get();

        $data = $query->map(fn($item) => [
            'name' => $item->name,
            'quantity' => number_format($item->total_quantity, 3),
            'total' => number_format($item->total_sales, 3),
        ]);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function specialOrdersData(Request $request)
    {
        $query = SpecialOrder::with(['customer', 'payments.paymentMethod'])
            ->where('status', 'delivered');

        $this->applySpecialOrderFilters($query, $request);

        $sortField = $request->get('sort', 'delivery_date');
        $sortDirection = $request->get('direction', 'desc');

        $allowedSorts = ['delivery_date', 'total_amount', 'created_at'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection);
        }

        $perPage = $request->get('per_page', 20);
        $orders = $query->paginate($perPage);

        $data = $orders->map(function ($order) {
            $paymentMethodNames = $order->payments->map(fn($p) => $p->paymentMethod->name ?? '-')->unique()->join('، ');

            return [
                'id' => $order->id,
                'customer' => $order->display_name,
                'event_type' => $order->event_type_name,
                'delivery_date' => $order->delivery_date->format('Y-m-d'),
                'total' => number_format($order->total_amount, 3),
                'paid' => number_format($order->paid_amount, 3),
                'payment_methods' => $paymentMethodNames ?: '-',
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

    public function specialOrdersSummary(Request $request)
    {
        $query = SpecialOrder::where('status', 'delivered');
        $this->applySpecialOrderFilters($query, $request);

        $stats = $query->selectRaw('
            COUNT(*) as orders_count,
            COALESCE(SUM(total_amount), 0) as total_sales,
            COALESCE(SUM(paid_amount), 0) as total_paid
        ')->first();

        $ordersCount = $stats->orders_count ?? 0;
        $averageOrder = $ordersCount > 0 ? ($stats->total_sales / $ordersCount) : 0;

        return response()->json([
            'success' => true,
            'data' => [
                'total_sales' => number_format($stats->total_sales, 3),
                'orders_count' => $ordersCount,
                'average_order' => number_format($averageOrder, 3),
                'total_paid' => number_format($stats->total_paid, 3),
            ]
        ]);
    }

    public function exportProductsExcel(Request $request)
    {
        $products = OrderItem::query()
            ->whereHas('order', function ($q) use ($request) {
                $q->whereIn('status', ['paid', 'delivering']);
                $this->applyFilters($q, $request);
            })
            ->selectRaw('
                COALESCE(products.name, order_items.product_name) as name,
                SUM(order_items.quantity) as total_quantity,
                SUM(order_items.total) as total_sales
            ')
            ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
            ->groupBy(DB::raw('COALESCE(products.name, order_items.product_name)'))
            ->orderByDesc('total_sales')
            ->get();

        $filename = 'products_report_' . now()->format('Y-m-d_H-i') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');

            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                'الصنف',
                'الكمية المباعة',
                'إجمالي المبيعات'
            ]);

            foreach ($products as $product) {
                fputcsv($file, [
                    $product->name,
                    number_format($product->total_quantity, 3),
                    number_format($product->total_sales, 3)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportExcel(Request $request)
    {
        $query = Order::with(['posPoint', 'payments.paymentMethod'])
            ->whereIn('status', ['paid', 'delivering']);

        $this->applyFilters($query, $request);
        $query->orderBy('created_at', 'desc');

        $orders = $query->get();

        $filename = 'sales_report_' . now()->format('Y-m-d_H-i') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');

            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                'رقم الطلب',
                'التاريخ',
                'نقطة البيع',
                'الإجمالي',
                'الخصم',
                'الصافي',
                'طرق الدفع'
            ]);

            foreach ($orders as $order) {
                $netTotal = $order->total - ($order->discount ?? 0);
                $paymentMethodNames = $order->payments->map(fn($p) => $p->paymentMethod->name ?? '-')->join('، ');

                fputcsv($file, [
                    $order->order_number,
                    $order->created_at->format('Y-m-d H:i'),
                    $order->posPoint->name ?? '-',
                    number_format($order->total, 3),
                    number_format($order->discount ?? 0, 3),
                    number_format($netTotal, 3),
                    $paymentMethodNames ?: '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportSpecialOrdersExcel(Request $request)
    {
        $query = SpecialOrder::with(['customer', 'payments.paymentMethod'])
            ->where('status', 'delivered');

        $this->applySpecialOrderFilters($query, $request);
        $query->orderBy('delivery_date', 'desc');

        $orders = $query->get();

        $filename = 'special_orders_report_' . now()->format('Y-m-d_H-i') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');

            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                'العميل',
                'المناسبة',
                'تاريخ التسليم',
                'الإجمالي',
                'المدفوع',
                'طرق الدفع'
            ]);

            foreach ($orders as $order) {
                $paymentMethodNames = $order->payments->map(fn($p) => $p->paymentMethod->name ?? '-')->unique()->join('، ');

                fputcsv($file, [
                    $order->display_name,
                    $order->event_type_name,
                    $order->delivery_date->format('Y-m-d'),
                    number_format($order->total_amount, 3),
                    number_format($order->paid_amount, 3),
                    $paymentMethodNames ?: '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function print(Request $request)
    {
        $query = Order::with(['posPoint', 'payments.paymentMethod'])
            ->whereIn('status', ['paid', 'delivering']);

        $this->applyFilters($query, $request);
        $query->orderBy('created_at', 'desc');

        $orders = $query->get();

        $statsQuery = Order::where('status', 'paid');
        $this->applyFilters($statsQuery, $request);

        $stats = $statsQuery->selectRaw('
            COUNT(*) as orders_count,
            COALESCE(SUM(total), 0) as total_sales,
            COALESCE(SUM(COALESCE(discount, 0)), 0) as total_discount,
            COALESCE(SUM(total - COALESCE(discount, 0)), 0) as net_sales
        ')->first();

        $ordersCount = $stats->orders_count ?? 0;
        $averageOrder = $ordersCount > 0 ? ($stats->net_sales / $ordersCount) : 0;

        $summary = [
            'total_sales' => number_format($stats->net_sales, 3),
            'orders_count' => $ordersCount,
            'average_order' => number_format($averageOrder, 3),
            'total_discount' => number_format($stats->total_discount, 3),
        ];

        $specialOrdersQuery = SpecialOrder::with(['customer', 'payments.paymentMethod'])
            ->where('status', 'delivered');
        $this->applySpecialOrderFilters($specialOrdersQuery, $request);
        $specialOrdersQuery->orderBy('delivery_date', 'desc');
        $specialOrders = $specialOrdersQuery->get();

        $specialStatsQuery = SpecialOrder::where('status', 'delivered');
        $this->applySpecialOrderFilters($specialStatsQuery, $request);
        $specialStats = $specialStatsQuery->selectRaw('
            COUNT(*) as orders_count,
            COALESCE(SUM(total_amount), 0) as total_sales,
            COALESCE(SUM(paid_amount), 0) as total_paid
        ')->first();

        $specialOrdersCount = $specialStats->orders_count ?? 0;
        $specialAverageOrder = $specialOrdersCount > 0 ? ($specialStats->total_sales / $specialOrdersCount) : 0;

        $specialSummary = [
            'total_sales' => number_format($specialStats->total_sales, 3),
            'orders_count' => $specialOrdersCount,
            'average_order' => number_format($specialAverageOrder, 3),
            'total_paid' => number_format($specialStats->total_paid, 3),
        ];

        $filters = [
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
        ];

        return view('reports.sales.print', compact('orders', 'summary', 'specialOrders', 'specialSummary', 'filters'));
    }

    protected function applyFilters($query, Request $request)
    {
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('pos_point_id')) {
            $query->where('pos_point_id', $request->pos_point_id);
        }

        if ($request->filled('payment_method_id')) {
            $query->whereHas('payments', function ($q) use ($request) {
                $q->where('payment_method_id', $request->payment_method_id);
            });
        }

        return $query;
    }

    protected function applySpecialOrderFilters($query, Request $request)
    {
        if ($request->filled('date_from')) {
            $query->whereDate('delivery_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('delivery_date', '<=', $request->date_to);
        }

        if ($request->filled('payment_method_id')) {
            $query->whereHas('payments', function ($q) use ($request) {
                $q->where('payment_method_id', $request->payment_method_id);
            });
        }

        return $query;
    }
}
