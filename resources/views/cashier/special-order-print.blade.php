<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلبية خاصة #{{ $order->id }}</title>
    <link href="{{ asset('assets/fonts/cairo/cairo.css') }}" rel="stylesheet">
    <script src="{{ asset('js/barcode/jsbarcode.min.js') }}"></script>
    <style>
        @page {
            margin: 0;
            size: 72mm auto;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            font-size: 11px;
            line-height: 1.2;
            background: #fff;
            color: #000;
            padding: 3mm 3mm;
            width: 72mm;
            margin: 0 auto;
            direction: rtl;
            text-align: right;
        }

        .receipt {
            width: 100%;
            page-break-after: always;
        }

        .receipt:last-child {
            page-break-after: auto;
        }

        .header {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
            margin-bottom: 5px;
        }

        .header .logo {
            max-width: 140px;
            height: auto;
            margin: 0 auto 5px;
            display: block;
            background: #000;
            padding: 6px 10px;
            filter: invert(1) brightness(2) contrast(1.5);
        }

        .header .subtitle {
            font-size: 12px;
            font-weight: bold;
            border: 1px solid #000;
            display: inline-block;
            padding: 1px 8px;
        }

        .barcode-section {
            text-align: center;
            margin: 3px 0;
            padding: 2px 0;
            border-bottom: 1px dashed #000;
        }

        .barcode-svg {
            display: block;
            margin: 0 auto;
        }

        .order-id {
            font-size: 13px;
            font-weight: bold;
        }

        .order-info {
            border-bottom: 1px dashed #000;
            padding-bottom: 4px;
            margin-bottom: 4px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1px;
            font-size: 10px;
        }

        .info-row .label {
            font-weight: bold;
        }

        .section-title {
            font-weight: bold;
            font-size: 11px;
            margin: 4px 0 2px;
            text-align: center;
            background: #000;
            color: #fff;
            padding: 2px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }

        .items-table th,
        .items-table td {
            padding: 2px 1px;
            text-align: right;
            border-bottom: 1px dotted #000;
            font-size: 10px;
        }

        .items-table th {
            font-weight: bold;
            border-bottom: 1px solid #000;
        }

        .items-table .qty {
            text-align: center;
            width: 45px;
        }

        .items-table .price {
            text-align: left;
            width: 50px;
        }

        .totals {
            border-top: 1px dashed #000;
            padding-top: 4px;
            margin-top: 4px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
            font-size: 11px;
            padding: 2px 0;
        }

        .total-row.grand {
            font-size: 13px;
            font-weight: bold;
            border: 1px solid #000;
            padding: 4px;
            margin: 2px 0;
        }

        .total-row.paid {
            font-weight: bold;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
        }

        .total-row.remaining {
            font-size: 12px;
            font-weight: bold;
            border: 1px dashed #000;
            padding: 3px;
            margin-top: 2px;
        }

        .payments {
            margin-top: 4px;
            border-top: 1px dashed #000;
            padding-top: 4px;
        }

        .payment-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1px;
            font-size: 10px;
        }

        .payment-date {
            font-size: 9px;
            color: #666;
        }

        .status-box {
            text-align: center;
            margin: 4px 0;
            padding: 2px;
            border: 1px solid #000;
            font-weight: bold;
            font-size: 11px;
        }

        .footer {
            margin-top: 6px;
            padding-top: 4px;
            border-top: 1px dashed #000;
            text-align: center;
            font-size: 10px;
        }

        .footer .thanks {
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 3px;
        }

        .notes {
            margin-top: 4px;
            padding: 3px;
            border: 1px dashed #000;
            font-size: 10px;
        }

        .notes-title {
            font-weight: bold;
            margin-bottom: 1px;
        }

        @media print {
            body {
                padding: 3mm 3mm;
                width: 72mm;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <img src="{{ asset('logo-dark.png') }}" alt="تاج السلطان" class="logo">
            <div class="subtitle">طلبية خاصة</div>
        </div>

        <div class="barcode-section">
            <svg class="barcode-svg barcode1"></svg>
            <div class="order-id">#{{ $order->id }}</div>
        </div>

        <div class="order-info">
            <div class="info-row">
                <span class="label">العميل:</span>
                <span>{{ $order->display_name }}</span>
            </div>
            @if($order->display_phone)
            <div class="info-row">
                <span class="label">الهاتف:</span>
                <span>{{ $order->display_phone }}</span>
            </div>
            @endif
            <div class="info-row">
                <span class="label">المناسبة:</span>
                <span>{{ $order->event_type_name }}</span>
            </div>
            <div class="info-row">
                <span class="label">تاريخ التسليم:</span>
                <span>{{ $order->delivery_date->format('Y-m-d') }}</span>
            </div>
            <div class="info-row">
                <span class="label">تاريخ الطلب:</span>
                <span>{{ $order->created_at->format('Y-m-d H:i') }}</span>
            </div>
        </div>

        <div class="status-box">{{ $order->status_name }}</div>

        @if($order->description)
        <div class="notes">
            <div class="notes-title">الوصف:</div>
            {{ $order->description }}
        </div>
        @endif

        <div class="section-title">الأصناف</div>
        <table class="items-table">
            <thead>
                <tr>
                    <th>الصنف</th>
                    <th class="qty">الكمية</th>
                    <th class="price">السعر</th>
                    <th class="price">الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td class="qty">
                        @if($item->is_weight)
                            {{ number_format($item->quantity, 3) }}
                        @else
                            {{ number_format($item->quantity) }}
                        @endif
                    </td>
                    <td class="price">{{ number_format($item->unit_price, 2) }}</td>
                    <td class="price">{{ number_format($item->total_price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <div class="total-row grand">
                <span>الإجمالي:</span>
                <span>{{ number_format($order->total_amount, 3) }} د.ل</span>
            </div>
            <div class="total-row paid">
                <span>المدفوع:</span>
                <span>{{ number_format($order->paid_amount, 3) }} د.ل</span>
            </div>
            @if($order->remaining_amount > 0)
            <div class="total-row remaining">
                <span>المتبقي:</span>
                <span>{{ number_format($order->remaining_amount, 3) }} د.ل</span>
            </div>
            @endif
        </div>

        @if($order->payments->count() > 0)
        <div class="payments">
            <div class="section-title">سجل المدفوعات</div>
            @foreach($order->payments as $payment)
            <div class="payment-item">
                <span>{{ $payment->paymentMethod->name ?? '-' }}</span>
                <span>{{ number_format($payment->amount, 3) }} د.ل</span>
            </div>
            @endforeach
        </div>
        @endif

        @if($order->notes)
        <div class="notes">
            <div class="notes-title">ملاحظات:</div>
            {{ $order->notes }}
        </div>
        @endif

        <div class="footer">
            <div class="thanks">شكراً لتعاملكم معنا</div>
            <div style="display: flex; align-items: center; justify-content: center; gap: 5px; font-size: 9px; color: #333;">
                <img src="{{ asset('hulul.jpg') }}" alt="Hulul" style="height: 20px; width: auto; filter: grayscale(100%);">
                <span>حلول لتقنية المعلومات</span>
            </div>
        </div>
    </div>

    <div class="receipt">
        <div class="header">
            <img src="{{ asset('logo-dark.png') }}" alt="تاج السلطان" class="logo">
            <div class="subtitle">طلبية خاصة</div>
        </div>

        <div class="barcode-section">
            <svg class="barcode-svg barcode2"></svg>
            <div class="order-id">#{{ $order->id }}</div>
        </div>

        <div class="order-info">
            <div class="info-row">
                <span class="label">العميل:</span>
                <span>{{ $order->display_name }}</span>
            </div>
            @if($order->display_phone)
            <div class="info-row">
                <span class="label">الهاتف:</span>
                <span>{{ $order->display_phone }}</span>
            </div>
            @endif
            <div class="info-row">
                <span class="label">المناسبة:</span>
                <span>{{ $order->event_type_name }}</span>
            </div>
            <div class="info-row">
                <span class="label">تاريخ التسليم:</span>
                <span>{{ $order->delivery_date->format('Y-m-d') }}</span>
            </div>
            <div class="info-row">
                <span class="label">تاريخ الطلب:</span>
                <span>{{ $order->created_at->format('Y-m-d H:i') }}</span>
            </div>
        </div>

        <div class="status-box">{{ $order->status_name }}</div>

        @if($order->description)
        <div class="notes">
            <div class="notes-title">الوصف:</div>
            {{ $order->description }}
        </div>
        @endif

        <div class="section-title">الأصناف</div>
        <table class="items-table">
            <thead>
                <tr>
                    <th>الصنف</th>
                    <th class="qty">الكمية</th>
                    <th class="price">السعر</th>
                    <th class="price">الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td class="qty">
                        @if($item->is_weight)
                            {{ number_format($item->quantity, 3) }}
                        @else
                            {{ number_format($item->quantity) }}
                        @endif
                    </td>
                    <td class="price">{{ number_format($item->unit_price, 2) }}</td>
                    <td class="price">{{ number_format($item->total_price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <div class="total-row grand">
                <span>الإجمالي:</span>
                <span>{{ number_format($order->total_amount, 3) }} د.ل</span>
            </div>
            <div class="total-row paid">
                <span>المدفوع:</span>
                <span>{{ number_format($order->paid_amount, 3) }} د.ل</span>
            </div>
            @if($order->remaining_amount > 0)
            <div class="total-row remaining">
                <span>المتبقي:</span>
                <span>{{ number_format($order->remaining_amount, 3) }} د.ل</span>
            </div>
            @endif
        </div>

        @if($order->payments->count() > 0)
        <div class="payments">
            <div class="section-title">سجل المدفوعات</div>
            @foreach($order->payments as $payment)
            <div class="payment-item">
                <span>{{ $payment->paymentMethod->name ?? '-' }}</span>
                <span>{{ number_format($payment->amount, 3) }} د.ل</span>
            </div>
            @endforeach
        </div>
        @endif

        @if($order->notes)
        <div class="notes">
            <div class="notes-title">ملاحظات:</div>
            {{ $order->notes }}
        </div>
        @endif

        <div class="footer">
            <div class="thanks">شكراً لتعاملكم معنا</div>
            <div style="display: flex; align-items: center; justify-content: center; gap: 5px; font-size: 9px; color: #333;">
                <img src="{{ asset('hulul.jpg') }}" alt="Hulul" style="height: 20px; width: auto; filter: grayscale(100%);">
                <span>حلول لتقنية المعلومات</span>
            </div>
        </div>
    </div>

    <script>
        var barcodeValue = "{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}";
        var barcodeOptions = {
            format: "CODE128",
            width: 1.5,
            height: 35,
            displayValue: false,
            margin: 5,
            background: "#ffffff",
            lineColor: "#000000"
        };
        JsBarcode(".barcode1", barcodeValue, barcodeOptions);
        JsBarcode(".barcode2", barcodeValue, barcodeOptions);
        window.onload = function() {
            if (window.printer && window.printer.print) {
                window.printer.print();
            } else {
                window.print();
            }
        };
    </script>
</body>
</html>
