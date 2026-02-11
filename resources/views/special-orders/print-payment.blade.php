<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إيصال دفعة #{{ $payment->id }}</title>
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

        .amount-box {
            text-align: center;
            padding: 25px;
            border: 2px solid #000;
            margin-bottom: 30px;
        }

        .amount-label {
            font-size: 14px;
            margin-bottom: 10px;
        }

        .amount-value {
            font-size: 32px;
            font-weight: 700;
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

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .summary-table th,
        .summary-table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #000;
        }

        .summary-table th {
            background: #f0f0f0;
            font-weight: 600;
        }

        .summary-table td {
            font-weight: 700;
        }

        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: #666;
        }

        .signatures {
            display: flex;
            gap: 80px;
        }

        .signature-area {
            width: 150px;
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
                <div class="doc-title">إيصال استلام</div>
                <div class="doc-number">رقم: {{ $payment->id }}</div>
                <div>{{ $payment->created_at->format('Y/m/d H:i') }}</div>
            </div>
        </div>



        <div class="section">
            <div class="section-title">تفاصيل الدفعة</div>
            <table class="info-table">
                <tr>
                    <td class="label">طريقة الدفع</td>
                    <td>{{ $payment->paymentMethod->name ?? '-' }}</td>
                    <td class="label">استلمها</td>
                    <td>{{ $payment->user->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">التاريخ</td>
                    <td colspan="3">{{ $payment->created_at->format('Y/m/d H:i') }}</td>
                </tr>
                @if($payment->notes)
                <tr>
                    <td class="label">ملاحظات</td>
                    <td colspan="3">{{ $payment->notes }}</td>
                </tr>
                @endif
            </table>
        </div>

        <div class="section">
            <div class="section-title">بيانات الطلب</div>
            <table class="info-table">
                <tr>
                    <td class="label">رقم الطلب</td>
                    <td>{{ $payment->specialOrder->id }}</td>
                    <td class="label">العميل</td>
                    <td>{{ $payment->specialOrder->customer_name }}</td>
                </tr>
                <tr>
                    <td class="label">الهاتف</td>
                    <td>{{ $payment->specialOrder->phone ?: '-' }}</td>
                    <td class="label">نوع المناسبة</td>
                    <td>{{ $payment->specialOrder->event_type }}</td>
                </tr>
                <tr>
                    <td class="label">تاريخ التسليم</td>
                    <td colspan="3">{{ $payment->specialOrder->delivery_date->format('Y/m/d') }}</td>
                </tr>
            </table>

            <table class="summary-table">
                <tr>
                    <th>إجمالي الطلب</th>
                    <th>المدفوع</th>
                    <th>المتبقي</th>
                </tr>
                <tr>
                    <td>{{ number_format($payment->specialOrder->total_amount, 2) }} د.ل</td>
                    <td>{{ number_format($payment->specialOrder->paid_amount, 2) }} د.ل</td>
                    <td>{{ number_format($payment->specialOrder->remaining_amount, 2) }} د.ل</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <div>
                تاريخ الطباعة: {{ now()->format('Y/m/d H:i') }}
            </div>
            <div class="signatures">
                <div class="signature-area">
                    <div class="signature-line">المستلم</div>
                </div>
                <div class="signature-area">
                    <div class="signature-line">العميل</div>
                </div>
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
