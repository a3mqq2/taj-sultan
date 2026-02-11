<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\SpecialOrder;
use App\Models\PosPoint;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();

        $todaySales = Order::paid()->whereDate('paid_at', $today)->sum('total');
        $todayOrders = Order::paid()->whereDate('paid_at', $today)->count();

        $weekSales = Order::paid()->where('paid_at', '>=', $startOfWeek)->sum('total');
        $monthSales = Order::paid()->where('paid_at', '>=', $startOfMonth)->sum('total');

        $productsCount = Product::count();
        $activeProducts = Product::active()->count();

        $customersCount = Customer::count();
        $activeCustomers = Customer::active()->count();

        $pendingSpecialOrders = SpecialOrder::pending()->count();
        $todayDeliveries = SpecialOrder::whereDate('delivery_date', $today)
            ->whereNotIn('status', ['delivered', 'cancelled'])
            ->count();

        $activePosPoints = PosPoint::where('active', true)->count();
        $usersCount = User::where('is_active', true)->count();

        $pendingOrders = Order::pending()->count();

        return view('dashboard', compact(
            'todaySales',
            'todayOrders',
            'weekSales',
            'monthSales',
            'productsCount',
            'activeProducts',
            'customersCount',
            'activeCustomers',
            'pendingSpecialOrders',
            'todayDeliveries',
            'activePosPoints',
            'usersCount',
            'pendingOrders'
        ));
    }

    public function chartData()
    {
        $days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $days->push([
                'date' => $date->format('Y-m-d'),
                'label' => $date->format('D'),
                'arabic_label' => $this->getArabicDay($date->dayOfWeek),
            ]);
        }

        $salesData = Order::paid()
            ->where('paid_at', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->select(DB::raw('DATE(paid_at) as date'), DB::raw('SUM(total) as total'))
            ->groupBy('date')
            ->pluck('total', 'date');

        $chartData = $days->map(function ($day) use ($salesData) {
            return [
                'date' => $day['arabic_label'],
                'sales' => (float) ($salesData[$day['date']] ?? 0),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $chartData,
        ]);
    }

    public function recentOrders()
    {
        $orders = Order::with(['posPoint', 'paidByUser'])
            ->paid()
            ->orderBy('paid_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'pos_point' => $order->posPoint?->name ?? '-',
                    'total' => number_format($order->total, 3),
                    'paid_by' => $order->paidByUser?->name ?? '-',
                    'paid_at' => $order->paid_at?->format('H:i'),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }

    public function upcomingDeliveries()
    {
        $orders = SpecialOrder::with('customer')
            ->whereNotIn('status', ['delivered', 'cancelled'])
            ->where('delivery_date', '>=', Carbon::today())
            ->orderBy('delivery_date')
            ->limit(5)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'customer' => $order->display_name,
                    'event_type' => $order->event_type_name,
                    'delivery_date' => $order->delivery_date->format('Y-m-d'),
                    'total' => number_format($order->total_amount, 2),
                    'remaining' => number_format($order->remaining_amount, 2),
                    'status' => $order->status_name,
                    'status_class' => $order->status_badge_class,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }

    private function getArabicDay($dayOfWeek)
    {
        $days = [
            0 => 'أحد',
            1 => 'إثنين',
            2 => 'ثلاثاء',
            3 => 'أربعاء',
            4 => 'خميس',
            5 => 'جمعة',
            6 => 'سبت',
        ];
        return $days[$dayOfWeek] ?? '';
    }
}
