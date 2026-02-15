<?php

namespace App\Http\Controllers;

use App\Models\Setting;
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
