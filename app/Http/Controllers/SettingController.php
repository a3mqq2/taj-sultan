<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Product;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $cancelCode = Setting::getValue('cancel_invoice_code', '');

        return view('settings.general', compact('cancelCode'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'cancel_invoice_code' => 'required|string|min:4|max:20',
        ], [
            'cancel_invoice_code.required' => 'كود إلغاء الفاتورة مطلوب',
            'cancel_invoice_code.min' => 'كود الإلغاء يجب أن يكون 4 أحرف على الأقل',
            'cancel_invoice_code.max' => 'كود الإلغاء يجب ألا يتجاوز 20 حرف',
        ]);

        Setting::setValue('cancel_invoice_code', $request->cancel_invoice_code);

        return response()->json([
            'success' => true,
            'message' => 'تم حفظ الإعدادات بنجاح',
        ]);
    }

    public function shortcuts()
    {
        $shortcuts = json_decode(Setting::getValue('product_shortcuts', '{}'), true) ?: [];

        $products = [];
        foreach ($shortcuts as $slot => $productId) {
            $product = Product::find($productId);
            if ($product) {
                $products[$slot] = [
                    'id' => $product->id,
                    'name' => $product->name,
                ];
            }
        }

        return view('settings.shortcuts', compact('products'));
    }

    public function updateShortcuts(Request $request)
    {
        $request->validate([
            'shortcuts' => 'required|array',
            'shortcuts.*' => 'nullable|exists:products,id',
        ]);

        $shortcuts = [];
        foreach ($request->shortcuts as $slot => $productId) {
            if ($productId) {
                $shortcuts[$slot] = (int) $productId;
            }
        }

        Setting::setValue('product_shortcuts', json_encode($shortcuts));

        return response()->json([
            'success' => true,
            'message' => 'تم حفظ الاختصارات بنجاح',
        ]);
    }

    public function searchProducts(Request $request)
    {
        $query = $request->get('q', '');

        $products = Product::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('barcode', 'like', "%{$query}%");
            })
            ->limit(20)
            ->get(['id', 'name', 'barcode', 'price']);

        return response()->json($products);
    }

    public function verifyCancelCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $storedCode = Setting::getValue('cancel_invoice_code');

        if (!$storedCode) {
            return response()->json([
                'success' => false,
                'message' => 'لم يتم تعيين كود الإلغاء. تواصل مع المدير',
            ]);
        }

        if ($request->code !== $storedCode) {
            return response()->json([
                'success' => false,
                'message' => 'كود الإلغاء غير صحيح',
            ]);
        }

        return response()->json([
            'success' => true,
        ]);
    }
}
