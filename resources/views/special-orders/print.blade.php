<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلب خاص #{{ $specialOrder->id }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4;
            margin: 20mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            font-size: 13px;
            line-height: 1.5;
            direction: rtl;
            color: #000;
            background: #fff;
        }

        .container {
            max-width: 210mm;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #000;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo {
            width: 260px;
            height: auto;
        }

        .company-name {
            font-size: 24px;
            font-weight: 700;
        }

        .doc-info {
            text-align: left;
            font-size: 12px;
        }

        .doc-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .doc-number {
            font-weight: 600;
        }

        .section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #000;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 8px 10px;
            border: 1px solid #ccc;
            vertical-align: top;
        }

        .info-table .label {
            background: #f5f5f5;
            font-weight: 600;
            width: 25%;
        }

        .amounts-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .amounts-table th,
        .amounts-table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #000;
        }

        .amounts-table th {
            background: #f0f0f0;
            font-weight: 600;
        }

        .amounts-table td {
            font-size: 16px;
            font-weight: 700;
        }

        .payments-table {
            width: 100%;
            border-collapse: collapse;
        }

        .payments-table th,
        .payments-table td {
            padding: 8px 10px;
            border: 1px solid #ccc;
            text-align: right;
        }

        .payments-table th {
            background: #f5f5f5;
            font-weight: 600;
            font-size: 12px;
        }

        .description-box {
            padding: 10px;
            border: 1px solid #ccc;
            background: #fafafa;
            margin-top: 10px;
        }

        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: #666;
        }

        .signature-area {
            width: 200px;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #000;
            margin-top: 50px;
            padding-top: 5px;
        }

        .no-print {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
        }

        .no-print button {
            padding: 10px 25px;
            font-size: 14px;
            cursor: pointer;
            border: 1px solid #000;
            background: #fff;
        }

        .no-print button:hover {
            background: #f0f0f0;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo-section">
                <img src="{{ asset('logo-dark.png') }}" alt="Logo" class="logo">
            </div>
            <div class="doc-info">
                <div class="doc-title">طلب خاص</div>
                <div class="doc-number">رقم: {{ $specialOrder->id }}</div>
                <div>{{ $specialOrder->created_at->format('Y/m/d H:i') }}</div>
            </div>
        </div>

        <div class="section">
            <table class="amounts-table">
                <tr>
                    <th>الإجمالي</th>
                    <th>المدفوع</th>
                    <th>المتبقي</th>
                </tr>
                <tr>
                    <td>{{ number_format($specialOrder->total_amount, 2) }} د.ل</td>
                    <td>{{ number_format($specialOrder->paid_amount, 2) }} د.ل</td>
                    <td>{{ number_format($specialOrder->remaining_amount, 2) }} د.ل</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">بيانات العميل والطلب</div>
            <table class="info-table">
                <tr>
                    <td class="label">اسم العميل</td>
                    <td>{{ $specialOrder->customer_name }}</td>
                    <td class="label">الهاتف</td>
                    <td>{{ $specialOrder->phone ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="label">نوع المناسبة</td>
                    <td>{{ $specialOrder->event_type }}</td>
                    <td class="label">تاريخ التسليم</td>
                    <td>{{ $specialOrder->delivery_date->format('Y/m/d') }}</td>
                </tr>
                <tr>
                    <td class="label">الحالة</td>
                    <td>{{ $specialOrder->status_name }}</td>
                    <td class="label">أنشئ بواسطة</td>
                    <td>{{ $specialOrder->user->name ?? '-' }}</td>
                </tr>
            </table>

            @if($specialOrder->description)
            <div class="description-box">
                <strong>وصف الطلب:</strong> {{ $specialOrder->description }}
            </div>
            @endif

            @if($specialOrder->notes)
            <div class="description-box">
                <strong>ملاحظات:</strong> {{ $specialOrder->notes }}
            </div>
            @endif
        </div>

        @if($specialOrder->payments->count() > 0)
        <div class="section">
            <div class="section-title">سجل الدفعات</div>
            <table class="payments-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>التاريخ</th>
                        <th>طريقة الدفع</th>
                        <th>المبلغ</th>
                        <th>ملاحظات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($specialOrder->payments as $index => $payment)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $payment->created_at->format('Y/m/d H:i') }}</td>
                        <td>{{ $payment->paymentMethod->name ?? '-' }}</td>
                        <td>{{ number_format($payment->amount, 2) }} د.ل</td>
                        <td>{{ $payment->notes ?: '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <div class="footer">
            <div>
                تاريخ الطباعة: {{ now()->format('Y/m/d H:i') }}
            </div>
            <div class="signature-area">
                <div class="signature-line">توقيع المستلم</div>
            </div>
        </div>
    </div>

    <div class="no-print">
        <button onclick="window.print()">طباعة</button>
        <button onclick="window.close()">إغلاق</button>
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 300);
        };
    </script>
</body>
</html>
