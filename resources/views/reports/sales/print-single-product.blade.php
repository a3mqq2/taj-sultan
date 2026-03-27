<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تقرير مبيعات - {{ $product->name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Arial, sans-serif; font-size: 12px; line-height: 1.5; color: #333; background: #fff; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #333; }
        .header h1 { font-size: 24px; margin-bottom: 5px; }
        .header h2 { font-size: 18px; color: #555; margin-bottom: 10px; }
        .header .date-range { font-size: 14px; color: #666; }
        .header .print-date { font-size: 11px; color: #999; margin-top: 5px; }
        .summary { display: flex; justify-content: center; gap: 30px; margin-bottom: 30px; }
        .summary-item { text-align: center; padding: 15px 25px; background: #f5f5f5; border-radius: 8px; }
        .summary-item .value { font-size: 18px; font-weight: bold; }
        .summary-item .label { font-size: 12px; color: #666; margin-top: 5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 10px 8px; border-bottom: 1px solid #ddd; text-align: right; }
        th { background: #f5f5f5; font-weight: bold; font-size: 11px; }
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
        <h1>تقرير مبيعات صنف</h1>
        <h2>{{ $product->name }}</h2>
        @if($product->barcode)
            <div style="font-size:12px;color:#888;">باركود: {{ $product->barcode }}</div>
        @endif
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
            <div class="value">{{ $items->count() }}</div>
            <div class="label">عدد الفواتير</div>
        </div>
        <div class="summary-item">
            <div class="value">{{ number_format($totalQty, 3) }}</div>
            <div class="label">إجمالي الكمية</div>
        </div>
        <div class="summary-item">
            <div class="value">{{ number_format($totalAmount, 3) }} د.ل</div>
            <div class="label">إجمالي المبيعات</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>رقم الفاتورة</th>
                <th>التاريخ</th>
                <th class="text-center">الكمية</th>
                <th class="text-left">السعر</th>
                <th class="text-left">الإجمالي</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $idx => $item)
            <tr>
                <td>{{ $idx + 1 }}</td>
                <td>{{ $item->order->order_number }}</td>
                <td>{{ $item->order->created_at->format('Y-m-d H:i') }}</td>
                <td class="text-center">{{ number_format($item->quantity, 3) }}</td>
                <td class="text-left">{{ number_format($item->price, 3) }} د.ل</td>
                <td class="text-left">{{ number_format($item->total, 3) }} د.ل</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="3">الإجمالي</td>
                <td class="text-center">{{ number_format($totalQty, 3) }}</td>
                <td></td>
                <td class="text-left">{{ number_format($totalAmount, 3) }} د.ل</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
