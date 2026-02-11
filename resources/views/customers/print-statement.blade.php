<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>كشف حساب - {{ $customer->name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4;
            margin: 15mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            font-size: 12px;
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
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #000;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo {
            width: 200px;
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

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 8px;
            padding-bottom: 5px;
            border-bottom: 1px solid #000;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .info-table td {
            padding: 8px 10px;
            border: 1px solid #ccc;
            vertical-align: top;
        }

        .info-table .label {
            background: #f5f5f5;
            font-weight: 600;
            width: 20%;
        }

        .balance-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .balance-table th,
        .balance-table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #000;
        }

        .balance-table th {
            background: #f0f0f0;
            font-weight: 600;
        }

        .balance-table td {
            font-size: 16px;
            font-weight: 700;
        }

        .balance-positive {
            color: #10b981;
        }

        .balance-negative {
            color: #ef4444;
        }

        .transactions-table {
            width: 100%;
            border-collapse: collapse;
        }

        .transactions-table th,
        .transactions-table td {
            padding: 8px 10px;
            border: 1px solid #ccc;
            text-align: right;
        }

        .transactions-table th {
            background: #f5f5f5;
            font-weight: 600;
            font-size: 11px;
        }

        .transactions-table td {
            font-size: 11px;
        }

        .transactions-table .amount-cell {
            text-align: center;
            font-weight: 600;
        }

        .credit { color: #10b981; }
        .debit { color: #ef4444; }

        .footer {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            color: #666;
        }

        .signature-area {
            width: 180px;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #000;
            margin-top: 40px;
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

        .empty-message {
            text-align: center;
            padding: 30px;
            color: #666;
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
                <div class="doc-title">كشف حساب</div>
                <div>{{ now()->format('Y/m/d H:i') }}</div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">بيانات العميل</div>
            <table class="info-table">
                <tr>
                    <td class="label">اسم العميل</td>
                    <td>{{ $customer->name }}</td>
                    <td class="label">الهاتف</td>
                    <td>{{ $customer->phone ?: '-' }}</td>
                </tr>
                @if($customer->address)
                <tr>
                    <td class="label">العنوان</td>
                    <td colspan="3">{{ $customer->address }}</td>
                </tr>
                @endif
            </table>
        </div>

        @php
            $balance = $customer->balance;
            $balanceClass = $balance > 0 ? 'balance-positive' : ($balance < 0 ? 'balance-negative' : '');
            $balanceLabel = $balance > 0 ? 'رصيد لصالح العميل' : ($balance < 0 ? 'رصيد على العميل' : 'الحساب مسدد');
        @endphp

        <div class="section">
            <table class="balance-table">
                <tr>
                    <th>حالة الحساب</th>
                    <th>الرصيد</th>
                </tr>
                <tr>
                    <td>{{ $balanceLabel }}</td>
                    <td class="{{ $balanceClass }}">{{ number_format(abs($balance), 2) }} د.ل</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">سجل الحركات</div>
            @if($transactions->count() > 0)
            <table class="transactions-table">
                <thead>
                    <tr>
                        <th style="width: 15%">التاريخ</th>
                        <th style="width: 12%">النوع</th>
                        <th>الوصف</th>
                        <th style="width: 12%">مدين</th>
                        <th style="width: 12%">دائن</th>
                        <th style="width: 12%">الرصيد</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->created_at->format('Y/m/d H:i') }}</td>
                        <td>{{ $transaction->type_name }}</td>
                        <td>{{ $transaction->description ?: '-' }}</td>
                        <td class="amount-cell">
                            @if($transaction->isDebit())
                                <span class="debit">{{ number_format($transaction->amount, 2) }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="amount-cell">
                            @if($transaction->isCredit())
                                <span class="credit">{{ number_format($transaction->amount, 2) }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="amount-cell">
                            @php
                                $bal = $transaction->balance_after;
                                $balClass = $bal > 0 ? 'credit' : ($bal < 0 ? 'debit' : '');
                            @endphp
                            <span class="{{ $balClass }}">{{ number_format($bal, 2) }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="empty-message">
                لا توجد حركات مسجلة لهذا العميل
            </div>
            @endif
        </div>

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
