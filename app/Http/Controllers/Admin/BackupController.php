<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class BackupController extends Controller
{
    public function create(Request $request)
    {
        try {
            $exitCode = Artisan::call('db:backup', ['--manual' => true]);
            $output = Artisan::output();

            if ($exitCode === 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم إنشاء النسخة الاحتياطية بنجاح',
                    'output' => $output,
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'فشل إنشاء النسخة الاحتياطية',
                'output' => $output,
            ], 500);

        } catch (\Exception $e) {
            Log::error('Manual backup error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage(),
            ], 500);
        }
    }
}
