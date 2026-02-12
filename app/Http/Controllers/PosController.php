<?php

namespace App\Http\Controllers;

use App\Models\PosPoint;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function show(string $slug)
    {
        $posPoint = PosPoint::where('slug', $slug)->firstOrFail();

        if (!$posPoint->isActive()) {
            return view('pos.inactive', compact('posPoint'));
        }

        if ($posPoint->requiresLogin() && !auth()->check()) {
            return redirect()->route('pos.login', $slug);
        }

        $categories = Category::orderBy('name')->get();
        $products = Product::active()->where('type', 'piece')->orderBy('name')->get();

        return view('pos.terminal', [
            'posPoint' => $posPoint,
            'categories' => $categories,
            'products' => $products,
        ]);
    }

    public function loginForm(string $slug)
    {
        $posPoint = PosPoint::where('slug', $slug)->firstOrFail();

        if (!$posPoint->isActive()) {
            return view('pos.inactive', compact('posPoint'));
        }

        if (!$posPoint->requiresLogin() || auth()->check()) {
            return redirect()->route('pos.show', $slug);
        }

        return view('pos.login', compact('posPoint'));
    }

    public function login(Request $request, string $slug)
    {
        $posPoint = PosPoint::where('slug', $slug)->firstOrFail();

        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'اسم المستخدم مطلوب',
            'password.required' => 'كلمة المرور مطلوبة',
        ]);

        if (auth()->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('pos.show', $slug);
        }

        return back()->withErrors([
            'username' => 'بيانات الدخول غير صحيحة',
        ])->withInput($request->only('username'));
    }

    public function logout(Request $request, string $slug)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function products(string $slug, Request $request)
    {
        $query = Product::active()->where('type', 'piece');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $products = $query->orderBy('name')->get()->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'type' => $product->type,
                'barcode' => $product->barcode,
                'category_id' => $product->category_id,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    public function categories(string $slug)
    {
        $categories = Category::orderBy('name')->get()->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }

    public function createOrder(Request $request, string $slug)
    {
        $posPoint = PosPoint::where('slug', $slug)->firstOrFail();
        $userId = auth()->id();

        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:products,id',
            'items.*.name' => 'required|string',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.qty' => 'required|numeric|min:0.001',
            'items.*.isWeight' => 'required|boolean',
            'total' => 'required|numeric|min:0',
        ]);

        try {
            $order = DB::transaction(function () use ($validated, $posPoint, $userId) {
                $order = Order::create([
                    'order_number' => Order::generateOrderNumber(),
                    'pos_point_id' => $posPoint->id,
                    'user_id' => $userId,
                    'total' => $validated['total'],
                    'status' => 'pending',
                ]);

                foreach ($validated['items'] as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['id'],
                        'product_name' => $item['name'],
                        'price' => $item['price'],
                        'quantity' => $item['qty'],
                        'is_weight' => $item['isWeight'],
                        'total' => $item['price'] * $item['qty'],
                    ]);
                }

                return $order;
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'total' => number_format($order->total, 3),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ الطلب',
            ], 500);
        }
    }

    public function sticker(string $slug, string $barcode)
    {
        return view('pos.sticker', compact('barcode'));
    }
}
