<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلبية خاصة #{{ $order->id }}</title>
    <link href="{{ asset('assets/fonts/cairo/cairo.css') }}" rel="stylesheet">
    <script src="{{ asset('js/barcode/jsbarcode.min.js') }}"></script>
    <style>
        @page{margin:0;size:72mm auto}
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Cairo',sans-serif;font-size:8px;line-height:1;padding:1mm;width:72mm;color:#000;direction:rtl}
        .receipt{page-break-after:always}
        .receipt:last-child{page-break-after:auto}
        .header{text-align:center;border-bottom:1px dashed #000}
        .header .title{font-size:10px;font-weight:700}
        .barcode-section{text-align:center;border-bottom:1px dashed #000}
        .barcode-svg{display:block;margin:0 auto}
        .order-id{font-size:9px;font-weight:700}
        .order-info{border-bottom:1px dashed #000;font-size:7px}
        .info-row{display:flex;justify-content:space-between}
        .info-row .label{font-weight:700}
        .status-box{text-align:center;padding:1px;border:1px solid #000;font-weight:700;font-size:9px}
        .items-table{width:100%;border-collapse:collapse}
        .items-table th,.items-table td{padding:1px;text-align:right;font-size:7px;border-bottom:1px dotted #ccc}
        .items-table th{border-bottom:1px solid #000}
        .items-table .qty{text-align:center;width:35px}
        .items-table .price{text-align:left;width:40px}
        .totals{border-top:1px dashed #000}
        .total-row{display:flex;justify-content:space-between;font-size:8px}
        .total-row.grand{font-size:10px;font-weight:700;border:1px solid #000;padding:2px}
        .total-row.remaining{font-weight:700;border:1px dashed #000;padding:1px}
        .payments{font-size:7px;border-top:1px dashed #000}
        .payment-item{display:flex;justify-content:space-between}
        .notes{padding:1px;border:1px dashed #000;font-size:7px}
        .footer{text-align:center;font-size:8px;border-top:1px dashed #000}
        .brand{text-align:center;display:flex;align-items:center;justify-content:center;gap:3px;font-size:7px;color:#333}
        .brand img{height:14px;width:auto;filter:grayscale(100%)}
        @media print{body{padding:1mm;width:72mm}}
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header"><div class="title">تاج السلطان - طلبية خاصة</div></div>
        <div class="barcode-section"><svg class="barcode-svg barcode1"></svg><div class="order-id">#{{ $order->id }}</div></div>
        <div class="order-info">
            <div class="info-row"><span class="label">العميل:</span><span>{{ $order->display_name }}</span></div>
            @if($order->display_phone)<div class="info-row"><span class="label">الهاتف:</span><span>{{ $order->display_phone }}</span></div>@endif
            <div class="info-row"><span class="label">المناسبة:</span><span>{{ $order->event_type_name }}</span></div>
            <div class="info-row"><span class="label">التسليم:</span><span>{{ $order->delivery_date->format('Y-m-d') }}</span></div>
        </div>
        <div class="status-box">{{ $order->status_name }}</div>
        @if($order->description)<div class="notes">{{ $order->description }}</div>@endif
        <table class="items-table">
            <thead><tr><th>الصنف</th><th class="qty">الكمية</th><th class="price">السعر</th><th class="price">المجموع</th></tr></thead>
            <tbody>
                @foreach($order->items as $item)
                <tr><td>{{ $item->product_name }}</td><td class="qty">{{ $item->is_weight ? number_format($item->quantity, 3) : number_format($item->quantity) }}</td><td class="price">{{ number_format($item->unit_price, 2) }}</td><td class="price">{{ number_format($item->total_price, 2) }}</td></tr>
                @endforeach
            </tbody>
        </table>
        <div class="totals">
            <div class="total-row grand"><span>الإجمالي:</span><span>{{ number_format($order->total_amount, 3) }} د.ل</span></div>
            <div class="total-row"><span>المدفوع:</span><span>{{ number_format($order->paid_amount, 3) }} د.ل</span></div>
            @if($order->remaining_amount > 0)<div class="total-row remaining"><span>المتبقي:</span><span>{{ number_format($order->remaining_amount, 3) }} د.ل</span></div>@endif
        </div>
        @if($order->payments->count() > 0)
        <div class="payments">@foreach($order->payments as $payment)<div class="payment-item"><span>{{ $payment->paymentMethod->name ?? '-' }}</span><span>{{ number_format($payment->amount, 3) }}</span></div>@endforeach</div>
        @endif
        @if($order->notes)<div class="notes">{{ $order->notes }}</div>@endif
        <div class="footer">شكراً لتعاملكم معنا</div>
        <div class="brand"><img src="{{ asset('hulul.jpg') }}"><span>حلول لتقنية المعلومات</span></div>
    </div>

    <div class="receipt">
        <div class="header"><div class="title">تاج السلطان - طلبية خاصة</div></div>
        <div class="barcode-section"><svg class="barcode-svg barcode2"></svg><div class="order-id">#{{ $order->id }}</div></div>
        <div class="order-info">
            <div class="info-row"><span class="label">العميل:</span><span>{{ $order->display_name }}</span></div>
            @if($order->display_phone)<div class="info-row"><span class="label">الهاتف:</span><span>{{ $order->display_phone }}</span></div>@endif
            <div class="info-row"><span class="label">المناسبة:</span><span>{{ $order->event_type_name }}</span></div>
            <div class="info-row"><span class="label">التسليم:</span><span>{{ $order->delivery_date->format('Y-m-d') }}</span></div>
        </div>
        <div class="status-box">{{ $order->status_name }}</div>
        @if($order->description)<div class="notes">{{ $order->description }}</div>@endif
        <table class="items-table">
            <thead><tr><th>الصنف</th><th class="qty">الكمية</th><th class="price">السعر</th><th class="price">المجموع</th></tr></thead>
            <tbody>
                @foreach($order->items as $item)
                <tr><td>{{ $item->product_name }}</td><td class="qty">{{ $item->is_weight ? number_format($item->quantity, 3) : number_format($item->quantity) }}</td><td class="price">{{ number_format($item->unit_price, 2) }}</td><td class="price">{{ number_format($item->total_price, 2) }}</td></tr>
                @endforeach
            </tbody>
        </table>
        <div class="totals">
            <div class="total-row grand"><span>الإجمالي:</span><span>{{ number_format($order->total_amount, 3) }} د.ل</span></div>
            <div class="total-row"><span>المدفوع:</span><span>{{ number_format($order->paid_amount, 3) }} د.ل</span></div>
            @if($order->remaining_amount > 0)<div class="total-row remaining"><span>المتبقي:</span><span>{{ number_format($order->remaining_amount, 3) }} د.ل</span></div>@endif
        </div>
        @if($order->payments->count() > 0)
        <div class="payments">@foreach($order->payments as $payment)<div class="payment-item"><span>{{ $payment->paymentMethod->name ?? '-' }}</span><span>{{ number_format($payment->amount, 3) }}</span></div>@endforeach</div>
        @endif
        @if($order->notes)<div class="notes">{{ $order->notes }}</div>@endif
        <div class="footer">شكراً لتعاملكم معنا</div>
        <div class="brand"><img src="{{ asset('hulul.jpg') }}"><span>حلول لتقنية المعلومات</span></div>
    </div>

    <script>
        var barcodeValue = "{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}";
        var opts = {format:"CODE128",width:1.2,height:25,displayValue:false,margin:0};
        JsBarcode(".barcode1", barcodeValue, opts);
        JsBarcode(".barcode2", barcodeValue, opts);
        window.onload = function(){window.printer&&window.printer.print?window.printer.print():window.print();};
    </script>
</body>
</html>
