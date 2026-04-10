<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>فاتورة #{{ $order->order_number }}</title>
    <link href="{{ asset('assets/fonts/cairo/cairo.css') }}" rel="stylesheet">
    <script src="{{ asset('js/barcode/jsbarcode.min.js') }}"></script>
    <style>
        @page{margin:0;size:72mm auto}
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Cairo',sans-serif;font-size:11px;width:72mm;margin:0 auto;padding:3mm;direction:rtl;text-align:right}
        .header{text-align:center;font-size:15px;font-weight:800;padding:4px 0;border-bottom:1px dashed #000;margin-bottom:4px}
        .barcode-section{text-align:center;padding:3px 0;border-bottom:1px dashed #000;margin-bottom:4px}
        .barcode-svg{display:block;margin:0 auto}
        .info{font-size:10px;display:flex;justify-content:space-between;padding:1px 0}
        table{width:100%;border-collapse:collapse;margin:4px 0}
        th{background:#f0f0f0;padding:3px;font-size:10px;border-bottom:1px solid #000}
        td{padding:3px;font-size:10px;border-bottom:1px dashed #ccc}
        .subtotal{padding:4px 6px;display:flex;justify-content:space-between;font-size:11px;font-weight:600;border-top:1px dashed #000}
        .discount-box{padding:5px;margin:3px 0;display:flex;justify-content:space-between;font-size:12px;font-weight:800;border:2px dashed #000;background:#f5f5f5}
        .total{background:#000;color:#fff;padding:6px;margin:4px 0;display:flex;justify-content:space-between;font-size:13px;font-weight:800}
        .section{margin:4px 0;padding:4px 0;border-top:1px dashed #000}
        .section-title{font-size:10px;font-weight:700;margin-bottom:3px}
        .thanks{text-align:center;font-size:11px;font-weight:700;padding:5px 0;border-top:1px dashed #000}
        .hulul-footer{display:flex;align-items:center;justify-content:center;gap:6px;padding:4px 0;border-top:1px solid #000;margin-top:4px}
        .hulul-footer img{height:20px;filter:grayscale(100%) contrast(1.3)}
        .hulul-footer p{font-size:9px;font-weight:700;color:#000}
        .reprint-badge{text-align:center;font-size:10px;font-weight:700;color:#666;padding:3px 0;border-bottom:1px dashed #000;margin-bottom:4px}
    </style>
</head>
<body>
    <div class="header">تاج السلطان</div>
    <div class="barcode-section"><svg class="barcode-svg"></svg></div>
    <div class="info"><span>#{{ $order->order_number }}</span><span>{{ $order->paid_at->format('Y-m-d H:i') }}</span></div>
    <div class="info"><span>الكاشير:</span><span>{{ $order->paidByUser->name ?? '-' }}</span></div>
    @if($order->delivery_type === 'delivery')
    <div class="info"><span>نوع الطلب:</span><span>توصيل</span></div>
    @if($order->delivery_phone)<div class="info"><span>الهاتف:</span><span>{{ $order->delivery_phone }}</span></div>@endif
    @endif
    <table>
        <thead><tr><th>الصنف</th><th>الكمية</th><th>السعر</th><th>الإجمالي</th></tr></thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product_name }}</td>
                <td style="text-align:center">{{ $item->is_weight ? number_format($item->quantity, 3) . ' كجم' : intval($item->quantity) }}</td>
                <td style="text-align:center">{{ number_format($item->price, 3) }}</td>
                <td style="text-align:left">{{ $item->is_weight ? floor($item->total) : number_format($item->total, 3) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @php $discountVal = $order->discount ?? 0; $grossTotal = $order->total + $discountVal; @endphp
    @if($discountVal > 0)
    <div class="subtotal"><span>المجموع</span><span>{{ number_format($grossTotal, 3) }} د.ل</span></div>
    <div class="discount-box"><span>الخصم</span><span>- {{ number_format($discountVal, 3) }} د.ل</span></div>
    <div class="total"><span>الصافي</span><span>{{ number_format($order->total, 3) }} د.ل</span></div>
    @else
    <div class="total"><span>الإجمالي</span><span>{{ number_format($order->total, 3) }} د.ل</span></div>
    @endif
    @if($order->payments->count() > 0)
    <div class="section">
        <div class="section-title">طرق الدفع</div>
        @foreach($order->payments as $payment)
        <div class="info"><span>{{ $payment->paymentMethod->name ?? '-' }}</span><span>{{ number_format($payment->amount, 3) }} د.ل</span></div>
        @endforeach
    </div>
    @endif
    <div class="thanks">شكراً لزيارتكم</div>
    <div class="hulul-footer">
        <img src="{{ asset('hulul.jpg') }}">
        <p>حلول لتقنية المعلومات</p>
    </div>
    <script>
        JsBarcode(".barcode-svg", "{{ str_pad($order->order_number, 8, '0', STR_PAD_LEFT) }}", {format:"CODE128",width:1.5,height:30,displayValue:false,margin:2});
        window.onload = function() {
            setTimeout(function() {
                if (window.printer && window.printer.print) { window.printer.print(); }
                else { window.print(); }
                setTimeout(function() { window.close(); }, 500);
            }, 300);
        };
    </script>
</body>
</html>
