<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير المبيعات</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            background: #fff;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #333;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .header .date-range {
            font-size: 14px;
            color: #666;
        }

        .header .print-date {
            font-size: 11px;
            color: #999;
            margin-top: 5px;
        }

        .summary {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            gap: 20px;
        }

        .summary-item {
            flex: 1;
            text-align: center;
            padding: 15px;
            background: #f5f5f5;
            border-radius: 8px;
        }

        .summary-item .value {
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }

        .summary-item .label {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 10px 8px;
            text-align: right;
            border: 1px solid #ddd;
        }

        th {
            background: #f0f0f0;
            font-weight: 600;
        }

        tr:nth-child(even) {
            background: #fafafa;
        }

        .text-left {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-success {
            color: #10b981;
        }

        .fw-bold {
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 11px;
            color: #999;
        }

        @media print {
            body {
                padding: 10px;
            }

            .no-print {
                display: none !important;
            }

            .summary-item {
                background: #f5f5f5 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            th {
                background: #f0f0f0 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>تقرير المبيعات</h1>
        @if($filters['date_from'] || $filters['date_to'])
            <div class="date-range">
                @if($filters['date_from'] && $filters['date_to'])
                    من {{ $filters['date_from'] }} إلى {{ $filters['date_to'] }}
                @elseif($filters['date_from'])
                    من {{ $filters['date_from'] }}
                @elseif($filters['date_to'])
                    حتى {{ $filters['date_to'] }}
                @endif
            </div>
        @else
            <div class="date-range">جميع الفترات</div>
        @endif
        <div class="print-date">تاريخ الطباعة: {{ now()->format('Y-m-d H:i') }}</div>
    </div>

    <div class="summary">
        <div class="summary-item">
            <div class="value">{{ $summary['total_sales'] }} د.ل</div>
            <div class="label">إجمالي المبيعات</div>
        </div>
        <div class="summary-item">
            <div class="value">{{ $summary['orders_count'] }}</div>
            <div class="label">عدد الطلبات</div>
        </div>
        <div class="summary-item">
            <div class="value">{{ $summary['average_order'] }} د.ل</div>
            <div class="label">متوسط الطلب</div>
        </div>
        <div class="summary-item">
            <div class="value">{{ $summary['total_discount'] }} د.ل</div>
            <div class="label">إجمالي الخصومات</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>رقم الطلب</th>
                <th>التاريخ</th>
                <th>نقطة البيع</th>
                <th>الإجمالي</th>
                <th>الخصم</th>
                <th>الصافي</th>
                <th>طرق الدفع</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                @php
                    $netTotal = $order->total - $order->discount;
                    $paymentMethods = $order->payments->map(fn($p) => $p->paymentMethod->name)->join('، ');
                @endphp
                <tr>
                    <td class="fw-bold">{{ $order->order_number }}</td>
                    <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                    <td>{{ $order->posPoint->name ?? '-' }}</td>
                    <td>{{ number_format($order->total, 3) }}</td>
                    <td>{{ number_format($order->discount, 3) }}</td>
                    <td class="text-success fw-bold">{{ number_format($netTotal, 3) }}</td>
                    <td>{{ $paymentMethods ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">لا توجد طلبات</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        تم إنشاء هذا التقرير بواسطة نظام تاج السلطان
    </div>

    <script>
        function silentPrint() {
            if (window.printer && window.printer.print) {
                window.printer.print();
            } else {
                window.print();
            }
        }

        window.onload = function() {
            silentPrint();
        };
    </script>
</body>
</html>
