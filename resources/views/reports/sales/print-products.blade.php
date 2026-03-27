<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تقرير مبيعات الأصناف</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Arial, sans-serif; font-size: 12px; line-height: 1.5; color: #333; background: #fff; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #333; }
        .header h1 { font-size: 24px; margin-bottom: 10px; }
        .header .date-range { font-size: 14px; color: #666; }
        .header .print-date { font-size: 11px; color: #999; margin-top: 5px; }
        .summary { display: flex; justify-content: center; gap: 40px; margin-bottom: 30px; }
        .summary-item { text-align: center; padding: 15px 30px; background: #f5f5f5; border-radius: 8px; }
        .summary-item .value { font-size: 20px; font-weight: bold; }
        .summary-item .label { font-size: 12px; color: #666; margin-top: 5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 10px 8px; border-bottom: 1px solid #ddd; text-align: right; }
        th { background: #f5f5f5; font-weight: bold; font-size: 11px; text-transform: uppercase; }
        tr:nth-child(even) { background: #fafafa; }
        .text-left { text-align: left; }
        .text-center { text-align: center; }
        .total-row { font-weight: bold; background: #f0f0f0 !important; }
        @media print { body { padding: 10px; } .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom:20px;text-align:center;">
        <button onclick="window.print()" style="padding:10px 30px;font-size:14px;cursor:pointer;border:1px solid #ccc;border-radius:6px;background:#fff;">طباعة</button>
    </div>

    <div class="header">
        <h1>تقرير مبيعات الأصناف</h1>
        <div class="date-range">
            @if($filters['date_from'] && $filters['date_to'])
                من {{ $filters['date_from'] }} إلى {{ $filters['date_to'] }}
            @elseif($filters['date_from'])
                من {{ $filters['date_from'] }}
            @elseif($filters['date_to'])
                حتى {{ $filters['date_to'] }}
            @else
                جميع الفترات
            @endif
        </div>
        <div class="print-date">تاريخ الطباعة: {{ now()->format('Y-m-d H:i') }}</div>
    </div>

    <div class="summary">
        <div class="summary-item">
            <div class="value">{{ $query->count() }}</div>
            <div class="label">عدد الأصناف</div>
        </div>
        <div class="summary-item">
            <div class="value">{{ number_format($grandQty, 3) }}</div>
            <div class="label">إجمالي الكميات</div>
        </div>
        <div class="summary-item">
            <div class="value">{{ number_format($grandTotal, 3) }} د.ل</div>
            <div class="label">إجمالي المبيعات</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>الصنف</th>
                <th class="text-center">عدد الفواتير</th>
                <th class="text-center">الكمية</th>
                <th class="text-left">الإجمالي</th>
                <th class="text-left">النسبة</th>
            </tr>
        </thead>
        <tbody>
            @foreach($query as $idx => $item)
            <tr>
                <td>{{ $idx + 1 }}</td>
                <td>{{ $item->name }}</td>
                <td class="text-center">{{ $item->orders_count }}</td>
                <td class="text-center">{{ number_format($item->total_quantity, 3) }}</td>
                <td class="text-left">{{ number_format($item->total_sales, 3) }} د.ل</td>
                <td class="text-left">{{ $grandTotal > 0 ? number_format(($item->total_sales / $grandTotal) * 100, 1) : 0 }}%</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="3">الإجمالي</td>
                <td class="text-center">{{ number_format($grandQty, 3) }}</td>
                <td class="text-left">{{ number_format($grandTotal, 3) }} د.ل</td>
                <td class="text-left">100%</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
