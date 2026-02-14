<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلبية خاصة #{{ $order->id }}</title>
    <link href="{{ asset('assets/fonts/cairo/cairo.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/fonts/libre-barcode-128/libre-barcode-128.css') }}" rel="stylesheet">
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
            font-size: 12px;
            line-height: 1.4;
            background: #fff;
            color: #000;
            padding: 6mm 4mm;
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
            border-bottom: 2px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .header .logo {
            max-width: 180px;
            height: auto;
            margin: 0 auto 10px;
            display: block;
            background: #000;
            padding: 10px 15px;
            filter: invert(1) brightness(2) contrast(1.5);
        }

        .header .subtitle {
            font-size: 14px;
            font-weight: bold;
            border: 2px solid #000;
            display: inline-block;
            padding: 2px 12px;
            margin-top: 5px;
        }

        .barcode-section {
            text-align: center;
            margin: 15px 0;
            padding: 10px 0;
            border-bottom: 1px dashed #000;
        }

        .barcode {
            font-family: 'Libre Barcode 128', cursive;
            font-size: 48px;
            line-height: 1;
        }

        .order-id {
            font-size: 16px;
            font-weight: bold;
            margin-top: 5px;
        }

        .order-info {
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .info-row .label {
            font-weight: bold;
        }

        .section-title {
            font-weight: bold;
            font-size: 13px;
            margin: 10px 0 5px;
            text-align: center;
            background: #000;
            color: #fff;
            padding: 3px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .items-table th,
        .items-table td {
            padding: 4px 2px;
            text-align: right;
            border-bottom: 1px dotted #000;
        }

        .items-table th {
            font-weight: bold;
            border-bottom: 2px solid #000;
        }

        .items-table .qty {
            text-align: center;
            width: 50px;
        }

        .items-table .price {
            text-align: left;
            width: 60px;
        }

        .totals {
            border-top: 2px dashed #000;
            padding-top: 10px;
            margin-top: 10px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 12px;
            padding: 4px 0;
        }

        .total-row.grand {
            font-size: 16px;
            font-weight: bold;
            border: 2px solid #000;
            padding: 8px;
            margin: 5px 0;
        }

        .total-row.paid {
            font-weight: bold;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }

        .total-row.remaining {
            font-size: 14px;
            font-weight: bold;
            border: 2px dashed #000;
            padding: 6px;
            margin-top: 5px;
        }

        .payments {
            margin-top: 10px;
            border-top: 1px dashed #000;
            padding-top: 10px;
        }

        .payment-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
            font-size: 11px;
        }

        .payment-date {
            font-size: 10px;
            color: #666;
        }

        .status-box {
            text-align: center;
            margin: 10px 0;
            padding: 5px;
            border: 2px solid #000;
            font-weight: bold;
        }

        .footer {
            margin-top: 15px;
            padding-top: 10px;
            border-top: 2px dashed #000;
            text-align: center;
            font-size: 11px;
        }

        .footer .thanks {
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 5px;
        }

        .notes {
            margin-top: 10px;
            padding: 5px;
            border: 1px dashed #000;
            font-size: 11px;
        }

        .notes-title {
            font-weight: bold;
            margin-bottom: 3px;
        }

        @media print {
            body {
                padding: 6mm 4mm;
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
            <div class="barcode">{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}</div>
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
            <div class="info-row">
                <span class="label">الكاشير:</span>
                <span>{{ $order->user->name ?? '-' }}</span>
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
                            {{ number_format($item->quantity, 3) }} كجم
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
                <span>{{ $payment->paymentMethod->name ?? '-' }} {{ $payment->notes ? "({$payment->notes})" : '' }}</span>
                <span>{{ number_format($payment->amount, 3) }} د.ل</span>
            </div>
            <div class="payment-date">{{ $payment->created_at->format('Y-m-d H:i') }}</div>
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
            <div style="margin-top: 10px; display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 11px; color: #333;">
                <img src="{{ asset('hulul.jpg') }}" alt="Hulul" style="height: 30px; width: auto; filter: grayscale(100%);">
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
            <div class="barcode">{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}</div>
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
            <div class="info-row">
                <span class="label">الكاشير:</span>
                <span>{{ $order->user->name ?? '-' }}</span>
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
                            {{ number_format($item->quantity, 3) }} كجم
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
                <span>{{ $payment->paymentMethod->name ?? '-' }} {{ $payment->notes ? "({$payment->notes})" : '' }}</span>
                <span>{{ number_format($payment->amount, 3) }} د.ل</span>
            </div>
            <div class="payment-date">{{ $payment->created_at->format('Y-m-d H:i') }}</div>
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
            <div style="margin-top: 10px; display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 11px; color: #333;">
                <img src="{{ asset('hulul.jpg') }}" alt="Hulul" style="height: 30px; width: auto; filter: grayscale(100%);">
                <span>حلول لتقنية المعلومات</span>
            </div>
        </div>
    </div>

    <script>
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
