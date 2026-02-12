<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>الكاشير - {{ config('app.name') }}</title>
    <link href="{{ asset('assets/fonts/cairo/cairo.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/plugins/tabler-icons/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}">
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            overflow: hidden;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background: #f1f5f9;
            color: #1e293b;
        }

        .app-container {
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
        }

        .header {
            background: #fff;
            padding: 12px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e2e8f0;
            flex-shrink: 0;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            height: 100px;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: #f8fafc;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 6px 12px;
            background: #fee2e2;
            border: none;
            border-radius: 6px;
            color: #dc2626;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            font-size: 13px;
        }

        .special-order-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            border: none;
            border-radius: 8px;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .special-order-btn:hover {
            background: linear-gradient(135deg, #7c3aed, #6d28d9);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
        }

        .special-order-btn i {
            font-size: 18px;
        }

        .main-content {
            flex: 1;
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 16px;
            padding: 16px;
            overflow: hidden;
            min-height: 0;
        }

        .left-panel {
            display: flex;
            flex-direction: column;
            gap: 16px;
            overflow: hidden;
            min-height: 0;
        }

        .invoice-section {
            background: #fff;
            border-radius: 12px;
            padding: 16px;
            flex-shrink: 0;
        }

        .invoice-input {
            width: 100%;
            padding: 14px 18px;
            font-size: 20px;
            font-weight: 700;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-family: inherit;
            text-align: center;
        }

        .invoice-input:focus {
            outline: none;
            border-color: #3b82f6;
        }

        .items-section {
            background: #fff;
            border-radius: 12px;
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            min-height: 0;
            position: relative;
        }

        .items-section::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 400px;
            height: 400px;
            background: url('{{ asset("logo-dark.png") }}') no-repeat center center;
            background-size: contain;
            opacity: 0.06;
            pointer-events: none;
            z-index: 0;
        }

        .items-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 16px;
            border-bottom: 1px solid #e2e8f0;
            flex-shrink: 0;
            position: relative;
            z-index: 1;
            background: #fff;
        }

        .items-title {
            font-size: 16px;
            font-weight: 700;
        }

        .items-body {
            flex: 1;
            overflow-y: auto;
            min-height: 0;
            position: relative;
            z-index: 1;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
        }

        .items-table th {
            background: #f8fafc;
            padding: 10px 12px;
            text-align: right;
            font-weight: 700;
            font-size: 13px;
            color: #64748b;
            position: sticky;
            top: 0;
        }

        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
        }

        .items-table tr:hover {
            background: #f8fafc;
        }

        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px;
            color: #94a3b8;
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 12px;
        }

        .right-panel {
            display: flex;
            flex-direction: column;
            gap: 16px;
            overflow: hidden;
            min-height: 0;
        }

        .order-box {
            background: #fff;
            border-radius: 12px;
            padding: 16px;
            flex-shrink: 0;
        }

        .order-num {
            font-size: 13px;
            color: #64748b;
        }

        .order-num span {
            font-size: 20px;
            font-weight: 800;
            color: #1e293b;
        }

        .payment-box {
            background: #fff;
            border-radius: 12px;
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            min-height: 0;
        }

        .payment-header {
            padding: 10px 12px;
            border-bottom: 1px solid #e2e8f0;
            flex-shrink: 0;
        }

        .payment-title {
            font-size: 13px;
            font-weight: 700;
            color: #64748b;
        }

        .payment-methods-scroll {
            overflow-y: auto;
            padding: 10px;
            max-height: 140px;
        }

        .payment-methods {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 6px;
        }

        .pm-btn {
            padding: 8px;
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            text-align: center;
            transition: all 0.15s;
        }

        .pm-btn:hover {
            border-color: #3b82f6;
            background: #eff6ff;
        }

        .pm-btn.selected {
            border-color: #3b82f6;
            background: #3b82f6;
            color: #fff;
        }

        .pm-btn.credit-btn {
            background: linear-gradient(135deg, #f97316, #ea580c);
            border-color: #ea580c;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }

        .pm-btn.credit-btn i {
            font-size: 14px;
        }

        .pm-btn.credit-btn:hover {
            background: linear-gradient(135deg, #ea580c, #c2410c);
            border-color: #c2410c;
        }

        .pm-btn.credit-btn.selected {
            background: linear-gradient(135deg, #c2410c, #9a3412);
            border-color: #9a3412;
        }

        .added-payments {
            padding: 8px 10px;
            border-top: 1px solid #e2e8f0;
            flex-shrink: 0;
        }

        .added-title {
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 4px;
        }

        .payment-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 8px;
            background: #f0fdf4;
            border-radius: 4px;
            margin-bottom: 4px;
        }

        .payment-row .method {
            font-weight: 600;
            font-size: 11px;
        }

        .payment-row .amount {
            font-weight: 700;
            font-size: 12px;
            color: #059669;
        }

        .payment-row .remove-btn {
            background: none;
            border: none;
            color: #ef4444;
            cursor: pointer;
            padding: 2px;
            font-size: 14px;
        }

        .amount-input-section {
            padding: 10px;
            border-top: 1px solid #e2e8f0;
            flex-shrink: 0;
        }

        .amount-label {
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 4px;
        }

        .amount-row {
            display: flex;
            gap: 6px;
        }

        .amount-input {
            flex: 1;
            padding: 8px 10px;
            font-size: 16px;
            font-weight: 700;
            text-align: center;
            border: 2px solid #e2e8f0;
            border-radius: 6px;
            font-family: inherit;
        }

        .amount-input:focus {
            outline: none;
            border-color: #3b82f6;
        }

        .add-payment-btn {
            padding: 8px 12px;
            background: #3b82f6;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 12px;
            cursor: pointer;
            font-family: inherit;
        }

        .add-payment-btn:hover {
            background: #2563eb;
        }

        .add-payment-btn:disabled {
            background: #cbd5e1;
            cursor: not-allowed;
        }

        .discount-row {
            color: #f59e0b;
            font-weight: 600;
            font-size: 11px;
        }

        .remove-discount-btn {
            background: none;
            border: none;
            color: #ef4444;
            cursor: pointer;
            padding: 0 2px;
            font-size: 10px;
            vertical-align: middle;
        }

        .discount-btn {
            background: none;
            border: none;
            color: #f59e0b;
            cursor: pointer;
            font-size: 11px;
            font-weight: 600;
            font-family: inherit;
            display: inline-flex;
            align-items: center;
            gap: 2px;
            padding: 0;
        }

        .discount-btn i {
            font-size: 12px;
        }

        .discount-btn:hover {
            color: #d97706;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            width: 100%;
            max-width: 360px;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 700;
        }

        .modal-close {
            width: 32px;
            height: 32px;
            border: none;
            background: #f1f5f9;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #64748b;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 6px;
        }

        .form-input {
            width: 100%;
            padding: 12px;
            font-size: 18px;
            font-weight: 700;
            text-align: center;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-family: inherit;
        }

        .form-input:focus {
            outline: none;
            border-color: #f59e0b;
        }

        .modal-actions {
            display: flex;
            gap: 10px;
        }

        .modal-btn {
            flex: 1;
            padding: 12px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            border: none;
        }

        .modal-btn-cancel {
            background: #f1f5f9;
            color: #64748b;
        }

        .modal-btn-confirm {
            background: #f59e0b;
            color: #fff;
        }

        .summary-section {
            padding: 10px;
            border-top: 1px solid #e2e8f0;
            flex-shrink: 0;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
            font-size: 12px;
        }

        .summary-row.total {
            font-size: 14px;
            font-weight: 800;
            padding-top: 6px;
            border-top: 1px dashed #e2e8f0;
            margin-top: 3px;
        }

        .summary-row.diff {
            color: #dc2626;
            font-weight: 700;
        }

        .summary-row.diff.ok {
            color: #059669;
        }

        .pay-section {
            padding: 10px;
            border-top: 1px solid #e2e8f0;
            flex-shrink: 0;
        }

        .pay-btn {
            width: 100%;
            padding: 14px;
            background: #10b981;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .pay-btn:hover {
            background: #059669;
        }

        .pay-btn:disabled {
            background: #cbd5e1;
            cursor: not-allowed;
        }

        .hidden {
            display: none !important;
        }

        .swal2-popup.swal-rtl {
            font-family: 'Cairo', sans-serif !important;
            direction: rtl !important;
            border-radius: 16px !important;
            padding: 24px !important;
        }

        .swal2-popup .swal-title-rtl {
            font-family: 'Cairo', sans-serif !important;
            font-size: 20px !important;
            font-weight: 700 !important;
            line-height: 1.6 !important;
        }

        .swal2-popup .swal2-html-container {
            font-family: 'Cairo', sans-serif !important;
            margin: 16px 0 !important;
        }

        .swal2-actions {
            flex-direction: row-reverse !important;
            gap: 12px !important;
            margin-top: 16px !important;
        }

        .swal2-confirm, .swal2-cancel {
            font-family: 'Cairo', sans-serif !important;
            font-weight: 600 !important;
            font-size: 15px !important;
            padding: 10px 24px !important;
            border-radius: 8px !important;
            min-width: 100px !important;
        }

        .swal2-icon {
            margin: 0 auto 16px !important;
        }

        .swal2-container {
            z-index: 99999 !important;
        }

        .swal2-backdrop-show {
            background: transparent !important;
        }

        .weight-tag {
            display: inline-flex;
            align-items: center;
            gap: 2px;
            color: #8b5cf6;
            font-size: 11px;
            margin-left: 4px;
        }

        .item-remove-btn {
            background: none;
            border: none;
            color: #ef4444;
            cursor: pointer;
            padding: 4px;
            font-size: 16px;
            opacity: 0.6;
            transition: opacity 0.15s;
        }

        .item-remove-btn:hover {
            opacity: 1;
        }

        .footer-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 16px;
            background: #fff;
            border-radius: 12px;
            flex-shrink: 0;
            gap: 16px;
        }

        .footer-hints {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .hint {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            color: #64748b;
        }

        .hint kbd {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 2px 6px;
            font-size: 10px;
            font-weight: 700;
            font-family: inherit;
            color: #475569;
        }

        .footer-credit {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .footer-credit img {
            height: 24px;
            filter: grayscale(100%);
        }

        .footer-credit span {
            font-size: 11px;
            color: #64748b;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <div class="header">
            <div class="logo"><img src="{{ asset('logo-dark.png') }}" alt="Taj Alsultan"></div>
            <div class="header-left">
                <a href="{{ route('cashier.customers') }}" class="special-order-btn" style="background:linear-gradient(135deg,#f97316,#ea580c);">
                    <i class="ti ti-users"></i>
                    الزبائن والديون
                </a>
                <a href="{{ route('cashier.special-orders') }}" class="special-order-btn">
                    <i class="ti ti-cake"></i>
                    طلبيات خاصة
                </a>
                <div class="user-info">
                    <i class="ti ti-user"></i>
                    {{ auth()->user()->name }}
                </div>
                <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="ti ti-logout"></i>
                        خروج
                    </button>
                </form>
            </div>
        </div>

        <div class="main-content">
            <div class="left-panel">
                <div class="invoice-section">
                    <input type="text" class="invoice-input" id="invoiceInput" placeholder="رقم الفاتورة أو الباركود" autofocus>
                </div>

                <div class="items-section">
                    <div class="items-header">
                        <div class="items-title">الأصناف</div>
                    </div>
                    <div class="items-body">
                        <div class="empty-state" id="emptyState">
                            <i class="ti ti-receipt-off"></i>
                            <span>لا توجد أصناف</span>
                        </div>
                        <table class="items-table hidden" id="itemsTable">
                            <thead>
                                <tr>
                                    <th style="width:30px"></th>
                                    <th>الصنف</th>
                                    <th style="text-align:center">الكمية</th>
                                    <th style="text-align:center">السعر</th>
                                    <th style="text-align:left">الإجمالي</th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody"></tbody>
                        </table>
                    </div>
                </div>

                <div class="footer-section">
                    <div class="footer-hints">
                        <div class="hint"><kbd>F2</kbd> الباركود</div>
                        <div class="hint"><kbd>F4</kbd> خصم</div>
                        <div class="hint"><kbd>F8</kbd> دفع</div>
                        <div class="hint"><kbd>Esc</kbd> إلغاء</div>
                    </div>
                    <div class="footer-credit">
                        <img src="{{ asset('hulul.jpg') }}" alt="Hulul">
                        <span>حلول لتقنية المعلومات</span>
                    </div>
                </div>
            </div>

            <div class="right-panel">
                <div class="payment-box">
                    <div class="payment-header">
                        <div class="payment-title">طرق الدفع</div>
                    </div>
                    <div class="payment-methods-scroll">
                        <div class="payment-methods" id="paymentMethods">
                            @foreach($paymentMethods as $method)
                            <button class="pm-btn" data-id="{{ $method->id }}" data-name="{{ $method->name }}">
                                {{ $method->name }}
                            </button>
                            @endforeach
                            <button class="pm-btn credit-btn" id="creditBtn">
                                <i class="ti ti-clock-dollar"></i>
                                آجل
                            </button>
                        </div>
                    </div>

                    <div class="amount-input-section hidden" id="amountSection">
                        <div class="amount-label">المبلغ (<span id="selectedMethodName">-</span>)</div>
                        <div class="amount-row">
                            <input type="number" class="amount-input" id="amountInput" step="0.001" placeholder="0.000">
                            <button class="add-payment-btn" id="addPaymentBtn">إضافة</button>
                        </div>
                    </div>

                    <div class="added-payments hidden" id="addedPayments">
                        <div class="added-title">المدفوعات</div>
                        <div id="paymentsList"></div>
                    </div>

                    <div class="summary-section">
                        <div class="summary-row">
                            <span>إجمالي الفاتورة <button class="discount-btn" id="discountBtn"><i class="ti ti-discount-2"></i> خصم</button></span>
                            <span id="invoiceTotal">0.000</span>
                        </div>
                        <div class="summary-row discount-row hidden" id="discountRow">
                            <span>الخصم <button class="remove-discount-btn" id="removeDiscountBtn"><i class="ti ti-x"></i></button></span>
                            <span id="discountAmount">0.000</span>
                        </div>
                        <div class="summary-row total">
                            <span>الصافي</span>
                            <span id="netTotal">0.000</span>
                        </div>
                        <div class="summary-row">
                            <span>المدفوع</span>
                            <span id="paidTotal">0.000</span>
                        </div>
                        <div class="summary-row diff" id="diffRow">
                            <span>المتبقي</span>
                            <span id="diffAmount">0.000</span>
                        </div>
                    </div>

                    <div class="pay-section">
                        <button class="pay-btn" id="payBtn" disabled>
                            <i class="ti ti-check"></i>
                            تأكيد الدفع
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="discountModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">إضافة خصم</div>
                <button class="modal-close" id="closeDiscountModal"><i class="ti ti-x"></i></button>
            </div>
            <div class="form-group">
                <label class="form-label">مبلغ الخصم (حد أقصى 5 د.ل)</label>
                <input type="number" class="form-input" id="discountInput" step="0.001" min="0" max="5" placeholder="0.000">
            </div>
            <div class="modal-actions">
                <button class="modal-btn modal-btn-cancel" id="cancelDiscountModal">إلغاء</button>
                <button class="modal-btn modal-btn-confirm" id="confirmDiscount">تطبيق</button>
            </div>
        </div>
    </div>

    <div class="modal" id="creditModal">
        <div class="modal-content" style="max-width:450px;">
            <div class="modal-header">
                <div class="modal-title"><i class="ti ti-clock-dollar" style="color:#f97316;"></i> البيع بالآجل</div>
                <button class="modal-close" id="closeCreditModal"><i class="ti ti-x"></i></button>
            </div>

            <div class="form-group">
                <label class="form-label">البحث عن زبون</label>
                <input type="text" class="form-input" id="customerSearch" placeholder="اسم أو رقم هاتف..." style="font-size:14px;text-align:right;">
            </div>

            <div id="customerResults" style="max-height:150px;overflow-y:auto;margin-bottom:12px;"></div>

            <div id="selectedCustomerBox" class="hidden" style="background:#f0fdf4;border:2px solid #22c55e;border-radius:8px;padding:12px;margin-bottom:12px;">
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <div>
                        <div style="font-weight:700;color:#15803d;" id="selectedCustomerName">-</div>
                        <div style="font-size:12px;color:#64748b;" id="selectedCustomerPhone">-</div>
                        <div style="font-size:12px;color:#dc2626;" id="selectedCustomerBalance">-</div>
                    </div>
                    <button type="button" class="modal-close" id="clearCustomer" style="width:28px;height:28px;"><i class="ti ti-x"></i></button>
                </div>
            </div>

            <div id="newCustomerForm" class="hidden" style="background:#fef3c7;border:2px solid #f59e0b;border-radius:8px;padding:12px;margin-bottom:12px;">
                <div style="font-weight:700;color:#92400e;margin-bottom:8px;"><i class="ti ti-user-plus"></i> إضافة زبون جديد</div>
                <div class="form-group" style="margin-bottom:8px;">
                    <label class="form-label">الاسم *</label>
                    <input type="text" class="form-input" id="newCustomerName" placeholder="اسم الزبون" style="font-size:14px;text-align:right;">
                </div>
                <div class="form-group" style="margin-bottom:8px;">
                    <label class="form-label">الهاتف</label>
                    <input type="text" class="form-input" id="newCustomerPhone" placeholder="رقم الهاتف" style="font-size:14px;text-align:right;">
                </div>
                <div style="display:flex;gap:8px;">
                    <button type="button" class="modal-btn" id="saveNewCustomer" style="flex:1;background:#22c55e;color:#fff;">حفظ الزبون</button>
                    <button type="button" class="modal-btn modal-btn-cancel" id="cancelNewCustomer" style="flex:0;">إلغاء</button>
                </div>
            </div>

            <button type="button" class="modal-btn" id="showNewCustomerForm" style="width:100%;background:#fef3c7;color:#92400e;border:2px dashed #f59e0b;margin-bottom:12px;">
                <i class="ti ti-user-plus"></i> إضافة زبون جديد
            </button>

            <div style="border-top:1px solid #e2e8f0;padding-top:12px;">
                <div class="form-group">
                    <label class="form-label">المبلغ المدفوع</label>
                    <input type="number" class="form-input" id="creditPaidAmount" step="0.001" min="0" placeholder="0.000">
                </div>
                <div style="display:flex;justify-content:space-between;padding:8px;background:#fef2f2;border-radius:6px;margin-bottom:12px;">
                    <span style="color:#991b1b;font-weight:600;">المبلغ الآجل:</span>
                    <span style="color:#dc2626;font-weight:800;" id="creditRemainingAmount">0.000</span>
                </div>
            </div>

            <div class="modal-actions">
                <button class="modal-btn modal-btn-cancel" id="cancelCreditModal">إلغاء</button>
                <button class="modal-btn" id="confirmCredit" style="background:#f97316;color:#fff;" disabled>
                    <i class="ti ti-check"></i> حفظ البيع بالآجل
                </button>
            </div>
        </div>
    </div>

    <script>
        const BASE_URL = "{{ url('/') }}";
        const WEIGHT_PREFIX = '99';
        const MAX_DISCOUNT = 5;
        let currentOrder = null;
        let directItems = [];
        let isDirectMode = false;
        let payments = [];
        let selectedMethod = null;
        let discount = 0;
        let selectedCustomer = null;
        let isCredit = false;

        document.addEventListener('DOMContentLoaded', init);

        function init() {
            document.querySelectorAll('.pm-btn').forEach(btn => {
                btn.addEventListener('click', () => selectPaymentMethod(btn));
            });

            document.getElementById('invoiceInput').addEventListener('keydown', handleInvoiceInput);
            document.getElementById('addPaymentBtn').addEventListener('click', addPayment);
            document.getElementById('discountBtn').addEventListener('click', openDiscountModal);
            document.getElementById('closeDiscountModal').addEventListener('click', closeDiscountModal);
            document.getElementById('cancelDiscountModal').addEventListener('click', closeDiscountModal);
            document.getElementById('confirmDiscount').addEventListener('click', applyDiscount);
            document.getElementById('removeDiscountBtn').addEventListener('click', removeDiscount);
            document.getElementById('discountInput').addEventListener('keydown', e => {
                if (e.key === 'Enter') applyDiscount();
            });
            document.getElementById('payBtn').addEventListener('click', processPayment);
            document.getElementById('amountInput').addEventListener('keydown', e => {
                if (e.key === 'Enter') addPayment();
            });

            document.addEventListener('keydown', handleGlobalKeys);

            document.getElementById('creditBtn').addEventListener('click', openCreditModal);
            document.getElementById('closeCreditModal').addEventListener('click', closeCreditModal);
            document.getElementById('cancelCreditModal').addEventListener('click', closeCreditModal);
            document.getElementById('customerSearch').addEventListener('input', debounce(searchCustomers, 300));
            document.getElementById('clearCustomer').addEventListener('click', clearSelectedCustomer);
            document.getElementById('showNewCustomerForm').addEventListener('click', showNewCustomerForm);
            document.getElementById('cancelNewCustomer').addEventListener('click', hideNewCustomerForm);
            document.getElementById('saveNewCustomer').addEventListener('click', saveNewCustomer);
            document.getElementById('creditPaidAmount').addEventListener('input', updateCreditRemaining);
            document.getElementById('confirmCredit').addEventListener('click', processCreditPayment);
        }

        function debounce(func, wait) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }

        function handleGlobalKeys(e) {
            if (e.key === 'F2') {
                e.preventDefault();
                document.getElementById('invoiceInput').focus();
                document.getElementById('invoiceInput').select();
            }
            if (e.key === 'F4') {
                e.preventDefault();
                openDiscountModal();
            }
            if (e.key === 'F8') {
                e.preventDefault();
                if (!document.getElementById('payBtn').disabled) processPayment();
            }
            if (e.key === 'Escape') {
                if (document.getElementById('discountModal').classList.contains('active')) {
                    closeDiscountModal();
                } else if (isDirectMode || currentOrder) {
                    cancelAllItems();
                }
            }
        }

        async function cancelAllItems() {
            const hasItems = isDirectMode ? directItems.length > 0 : (currentOrder && currentOrder.items.length > 0);
            if (!hasItems) return;

            const confirm = await Swal.fire({
                title: '<i class="ti ti-trash" style="color:#ef4444;font-size:28px;"></i><br>إلغاء الأصناف',
                html: '<div style="font-family:Cairo,sans-serif;direction:rtl;">هل تريد إلغاء جميع الأصناف؟</div>',
                showCancelButton: true,
                confirmButtonText: '<i class="ti ti-x"></i> نعم، إلغاء',
                cancelButtonText: 'لا',
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                customClass: {
                    popup: 'swal-rtl',
                    title: 'swal-title-rtl'
                }
            });

            if (!confirm.isConfirmed) return;

            resetAll();
            toast('تم إلغاء الأصناف', 'success');
        }

        async function handleInvoiceInput(e) {
            if (e.key !== 'Enter') return;
            const val = e.target.value.trim();
            if (!val) return;

            document.getElementById('invoiceInput').value = '';

            if (val.length === 13 && val.startsWith(WEIGHT_PREFIX)) {
                await handleWeightBarcode(val);
            } else {
                await fetchOrder(val);
            }
        }

        async function handleWeightBarcode(barcode) {
            try {
                const res = await fetch(BASE_URL + '/cashier/add-weight-barcode', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ barcode })
                });
                const data = await res.json();
                if (data.success) {
                    if (currentOrder) {
                        const addRes = await fetch(BASE_URL + '/cashier/add-weight-item', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                order_id: currentOrder.id,
                                product_id: data.data.product_id,
                                quantity: data.data.quantity
                            })
                        });
                        const addData = await addRes.json();
                        if (addData.success) {
                            currentOrder = addData.data.order;
                            renderOrder();
                            toast('تم إضافة الصنف', 'success');
                        } else {
                            toast(addData.message, 'error');
                        }
                    } else {
                        if (!isDirectMode) {
                            startDirectMode();
                        }
                        directItems.push(data.data);
                        renderItems();
                        toast('تم إضافة الصنف', 'success');
                    }
                } else {
                    toast(data.message, 'error');
                }
            } catch (err) {
                console.error('handleWeightBarcode error:', err);
                toast('خطأ في الاتصال: ' + err.message, 'error');
            }
        }

        async function fetchOrder(num) {
            try {
                const res = await fetch(BASE_URL + '/cashier/fetch-order', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ order_number: num })
                });
                const data = await res.json();
                if (data.success) {
                    currentOrder = data.data;

                    if (directItems.length > 0) {
                        for (const item of directItems) {
                            const addRes = await fetch(BASE_URL + '/cashier/add-weight-item', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                },
                                body: JSON.stringify({
                                    order_id: currentOrder.id,
                                    product_id: item.product_id,
                                    quantity: item.quantity
                                })
                            });
                            const addData = await addRes.json();
                            if (addData.success) {
                                currentOrder = addData.data.order;
                            }
                        }
                    }

                    isDirectMode = false;
                    directItems = [];
                    document.getElementById('invoiceInput').disabled = false;
                    renderOrder();
                    toast('تم جلب الطلب', 'success');
                } else {
                    toast(data.message, 'error');
                }
            } catch (err) {
                console.error('fetchOrder error:', err);
                toast('خطأ في الاتصال: ' + err.message, 'error');
            }
        }

        function renderOrder() {
            document.getElementById('orderBox').classList.remove('hidden');
            document.getElementById('orderNum').textContent = currentOrder.order_number;
            document.getElementById('emptyState').classList.add('hidden');
            document.getElementById('itemsTable').classList.remove('hidden');

            const tbody = document.getElementById('itemsBody');
            tbody.innerHTML = '';
            currentOrder.items.forEach(item => {
                const qty = item.is_weight ? parseFloat(item.quantity).toFixed(3) + ' كجم' : item.quantity;
                tbody.innerHTML += `<tr>
                    <td><button class="item-remove-btn" onclick="removeOrderItem(${item.id})"><i class="ti ti-x"></i></button></td>
                    <td>${item.product_name}${item.is_weight ? '<span class="weight-tag"><i class="ti ti-scale"></i></span>' : ''}</td>
                    <td style="text-align:center">${qty}</td>
                    <td style="text-align:center">${parseFloat(item.price).toFixed(3)}</td>
                    <td style="text-align:left">${parseFloat(item.total).toFixed(3)}</td>
                </tr>`;
            });

            document.getElementById('invoiceTotal').textContent = parseFloat(currentOrder.total).toFixed(3);
            updateSummary();
        }

        async function removeOrderItem(itemId) {
            try {
                const res = await fetch(BASE_URL + '/cashier/remove-item', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        order_id: currentOrder.id,
                        item_id: itemId
                    })
                });
                const data = await res.json();
                if (data.success) {
                    currentOrder = data.data.order;
                    if (currentOrder.items.length === 0) {
                        resetAll();
                    } else {
                        renderOrder();
                    }
                    toast('تم حذف الصنف', 'success');
                } else {
                    toast(data.message, 'error');
                }
            } catch (err) {
                console.error('removeOrderItem error:', err);
                toast('خطأ في الاتصال: ' + err.message, 'error');
            }
        }

        function startDirectMode() {
            isDirectMode = true;
            directItems = [];
            currentOrder = null;
            payments = [];

            document.getElementById('invoiceInput').value = '';
            document.getElementById('orderBox').classList.add('hidden');
            document.getElementById('emptyState').classList.remove('hidden');
            document.getElementById('itemsTable').classList.add('hidden');
            document.getElementById('itemsBody').innerHTML = '';
            document.getElementById('invoiceTotal').textContent = '0.000';
            clearPayments();
            updateSummary();
        }

        function renderItems() {
            if (directItems.length === 0) {
                document.getElementById('emptyState').classList.remove('hidden');
                document.getElementById('itemsTable').classList.add('hidden');
                document.getElementById('invoiceTotal').textContent = '0.000';
                isDirectMode = false;
            } else {
                document.getElementById('emptyState').classList.add('hidden');
                document.getElementById('itemsTable').classList.remove('hidden');

                let total = 0;
                const tbody = document.getElementById('itemsBody');
                tbody.innerHTML = '';
                directItems.forEach((item, index) => {
                    total += parseFloat(item.total);
                    const qty = item.is_weight ? parseFloat(item.quantity).toFixed(3) + ' كجم' : item.quantity;
                    tbody.innerHTML += `<tr>
                        <td><button class="item-remove-btn" onclick="removeDirectItem(${index})"><i class="ti ti-x"></i></button></td>
                        <td>${item.product_name}<span class="weight-tag"><i class="ti ti-scale"></i></span></td>
                        <td style="text-align:center">${qty}</td>
                        <td style="text-align:center">${parseFloat(item.price).toFixed(3)}</td>
                        <td style="text-align:left">${parseFloat(item.total).toFixed(3)}</td>
                    </tr>`;
                });
                document.getElementById('invoiceTotal').textContent = total.toFixed(3);
            }
            updateSummary();
        }

        function removeDirectItem(index) {
            directItems.splice(index, 1);
            renderItems();
            toast('تم حذف الصنف', 'success');
        }

        function selectPaymentMethod(btn) {
            document.querySelectorAll('.pm-btn').forEach(b => b.classList.remove('selected'));
            btn.classList.add('selected');
            selectedMethod = { id: btn.dataset.id, name: btn.dataset.name };
            document.getElementById('selectedMethodName').textContent = btn.dataset.name;
            document.getElementById('amountSection').classList.remove('hidden');
            document.getElementById('amountInput').value = '';
            document.getElementById('amountInput').focus();

            const remaining = getRemaining();
            if (remaining > 0) {
                document.getElementById('amountInput').value = remaining.toFixed(3);
            }
        }

        function addPayment() {
            if (!selectedMethod) return toast('اختر طريقة دفع', 'error');
            const amount = parseFloat(document.getElementById('amountInput').value);
            if (!amount || amount <= 0) return toast('أدخل المبلغ', 'error');

            const remaining = getRemaining();
            if (amount > remaining + 0.001) {
                return toast('المبلغ أكبر من المتبقي', 'error');
            }

            payments.push({
                payment_method_id: selectedMethod.id,
                method_name: selectedMethod.name,
                amount: amount
            });

            renderPayments();
            document.getElementById('amountSection').classList.add('hidden');
            document.querySelectorAll('.pm-btn').forEach(b => b.classList.remove('selected'));
            selectedMethod = null;
            updateSummary();
        }

        function renderPayments() {
            const container = document.getElementById('paymentsList');
            if (payments.length === 0) {
                document.getElementById('addedPayments').classList.add('hidden');
                container.innerHTML = '';
                return;
            }

            document.getElementById('addedPayments').classList.remove('hidden');
            container.innerHTML = '';
            payments.forEach((p, i) => {
                container.innerHTML += `
                    <div class="payment-row">
                        <span class="method">${p.method_name}</span>
                        <span class="amount">${parseFloat(p.amount).toFixed(3)}</span>
                        <button class="remove-btn" onclick="removePayment(${i})"><i class="ti ti-x"></i></button>
                    </div>
                `;
            });
        }

        function removePayment(index) {
            payments.splice(index, 1);
            renderPayments();
            updateSummary();
        }

        function clearPayments() {
            payments = [];
            renderPayments();
            document.getElementById('amountSection').classList.add('hidden');
            document.querySelectorAll('.pm-btn').forEach(b => b.classList.remove('selected'));
            selectedMethod = null;
        }

        function openDiscountModal() {
            document.getElementById('discountModal').classList.add('active');
            document.getElementById('discountInput').value = discount > 0 ? discount : '';
            document.getElementById('discountInput').focus();
        }

        function closeDiscountModal() {
            document.getElementById('discountModal').classList.remove('active');
        }

        function applyDiscount() {
            let val = parseFloat(document.getElementById('discountInput').value) || 0;
            if (val < 0) val = 0;
            if (val > MAX_DISCOUNT) {
                toast('الحد الأقصى للخصم 5 د.ل', 'error');
                return;
            }
            const grossTotal = getGrossTotal();
            if (val > grossTotal) {
                toast('الخصم لا يمكن أن يتجاوز الإجمالي', 'error');
                return;
            }
            discount = val;
            closeDiscountModal();
            updateSummary();
            if (val > 0) {
                toast('تم تطبيق الخصم', 'success');
            }
        }

        function removeDiscount() {
            discount = 0;
            updateSummary();
        }

        function getGrossTotal() {
            if (isDirectMode) {
                return directItems.reduce((s, i) => s + parseFloat(i.total), 0);
            }
            return currentOrder ? parseFloat(currentOrder.total) : 0;
        }

        function getTotal() {
            return Math.max(0, getGrossTotal() - discount);
        }

        function getPaid() {
            return payments.reduce((s, p) => s + parseFloat(p.amount), 0);
        }

        function getRemaining() {
            return Math.max(0, getTotal() - getPaid());
        }

        function updateSummary() {
            const grossTotal = getGrossTotal();
            const netTotal = getTotal();
            const paid = getPaid();
            const diff = netTotal - paid;

            document.getElementById('invoiceTotal').textContent = grossTotal.toFixed(3);
            document.getElementById('netTotal').textContent = netTotal.toFixed(3);
            document.getElementById('paidTotal').textContent = paid.toFixed(3);

            const discountRow = document.getElementById('discountRow');
            if (discount > 0) {
                discountRow.classList.remove('hidden');
                document.getElementById('discountAmount').textContent = '-' + discount.toFixed(3);
            } else {
                discountRow.classList.add('hidden');
            }

            const diffRow = document.getElementById('diffRow');
            const diffAmount = document.getElementById('diffAmount');

            if (Math.abs(diff) < 0.001) {
                diffRow.classList.add('ok');
                diffRow.classList.remove('diff');
                diffAmount.textContent = '0.000';
                document.getElementById('payBtn').disabled = netTotal <= 0;
            } else {
                diffRow.classList.remove('ok');
                diffRow.classList.add('diff');
                diffAmount.textContent = diff.toFixed(3);
                document.getElementById('payBtn').disabled = true;
            }
        }

        async function processPayment() {
            const total = getTotal();
            if (total <= 0) return toast('لا يوجد مبلغ', 'error');
            if (payments.length === 0) return toast('أضف طريقة دفع', 'error');

            const paid = getPaid();
            if (Math.abs(paid - total) > 0.001) {
                return toast('المدفوعات لا تساوي الإجمالي', 'error');
            }

            let paymentsHtml = '';
            payments.forEach(p => {
                paymentsHtml += `<div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px dashed #e2e8f0;"><span style="color:#64748b;">${p.method_name}</span><span style="font-weight:700;">${parseFloat(p.amount).toFixed(3)} د.ل</span></div>`;
            });

            const grossTotal = getGrossTotal();
            let discountHtml = '';
            if (discount > 0) {
                discountHtml = `
                    <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px dashed #e2e8f0;">
                        <span style="color:#f59e0b;">الخصم</span>
                        <span style="color:#f59e0b;font-weight:700;">-${discount.toFixed(3)} د.ل</span>
                    </div>`;
            }

            const confirm = await Swal.fire({
                title: '<i class="ti ti-cash" style="color:#10b981;font-size:28px;"></i><br>تأكيد الدفع',
                html: `
                    <div style="text-align:right;font-size:15px;direction:rtl;font-family:'Cairo',sans-serif;">
                        <div style="background:#f8fafc;border-radius:8px;padding:12px;margin-bottom:12px;">
                            <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px dashed #e2e8f0;">
                                <span style="color:#64748b;">إجمالي الفاتورة</span>
                                <span style="font-weight:700;">${grossTotal.toFixed(3)} د.ل</span>
                            </div>
                            ${discountHtml}
                            <div style="display:flex;justify-content:space-between;padding:8px 0;margin-top:4px;">
                                <span style="font-weight:700;">الصافي</span>
                                <span style="font-weight:800;font-size:16px;color:#10b981;">${total.toFixed(3)} د.ل</span>
                            </div>
                        </div>
                        <div style="background:#f0fdf4;border-radius:8px;padding:12px;margin-bottom:12px;">
                            <div style="font-weight:700;margin-bottom:8px;color:#15803d;font-size:13px;">المدفوعات</div>
                            ${paymentsHtml}
                        </div>
                        <div style="background:#fff;border:2px solid #e2e8f0;border-radius:8px;padding:12px;">
                            <div style="font-weight:700;margin-bottom:10px;color:#475569;font-size:13px;">نوع الاستلام</div>
                            <div style="display:flex;gap:10px;margin-bottom:10px;">
                                <label style="flex:1;display:flex;align-items:center;justify-content:center;gap:6px;padding:10px;background:#f0fdf4;border:2px solid #22c55e;border-radius:8px;cursor:pointer;font-weight:600;">
                                    <input type="radio" name="deliveryType" value="pickup" checked style="width:18px;height:18px;">
                                    <i class="ti ti-building-store" style="font-size:18px;"></i> استلام
                                </label>
                                <label style="flex:1;display:flex;align-items:center;justify-content:center;gap:6px;padding:10px;background:#f8fafc;border:2px solid #e2e8f0;border-radius:8px;cursor:pointer;font-weight:600;" id="deliveryLabel">
                                    <input type="radio" name="deliveryType" value="delivery" style="width:18px;height:18px;">
                                    <i class="ti ti-truck-delivery" style="font-size:18px;"></i> توصيل
                                </label>
                            </div>
                            <div id="deliveryPhoneBox" style="display:none;">
                                <input type="text" id="deliveryPhoneInput" placeholder="رقم الهاتف للتوصيل" style="width:100%;padding:10px 12px;border:2px solid #e2e8f0;border-radius:8px;font-size:14px;font-family:inherit;text-align:center;">
                            </div>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: '<i class="ti ti-check"></i> تأكيد الدفع',
                cancelButtonText: 'إلغاء',
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#64748b',
                customClass: {
                    popup: 'swal-rtl',
                    title: 'swal-title-rtl'
                },
                didOpen: () => {
                    const radios = document.querySelectorAll('input[name="deliveryType"]');
                    const phoneBox = document.getElementById('deliveryPhoneBox');
                    const pickupLabel = radios[0].closest('label');
                    const deliveryLabel = radios[1].closest('label');
                    radios.forEach(radio => {
                        radio.addEventListener('change', () => {
                            if (radio.value === 'delivery') {
                                phoneBox.style.display = 'block';
                                deliveryLabel.style.background = '#fef3c7';
                                deliveryLabel.style.borderColor = '#f59e0b';
                                pickupLabel.style.background = '#f8fafc';
                                pickupLabel.style.borderColor = '#e2e8f0';
                                document.getElementById('deliveryPhoneInput').focus();
                            } else {
                                phoneBox.style.display = 'none';
                                pickupLabel.style.background = '#f0fdf4';
                                pickupLabel.style.borderColor = '#22c55e';
                                deliveryLabel.style.background = '#f8fafc';
                                deliveryLabel.style.borderColor = '#e2e8f0';
                            }
                        });
                    });
                },
                preConfirm: () => {
                    const deliveryType = document.querySelector('input[name="deliveryType"]:checked').value;
                    const deliveryPhone = document.getElementById('deliveryPhoneInput').value.trim();
                    if (deliveryType === 'delivery' && !deliveryPhone) {
                        Swal.showValidationMessage('أدخل رقم الهاتف للتوصيل');
                        return false;
                    }
                    return { deliveryType, deliveryPhone };
                }
            });

            if (!confirm.isConfirmed) return;

            const deliveryType = confirm.value.deliveryType;
            const deliveryPhone = confirm.value.deliveryPhone;

            try {
                let orderId;

                if (isDirectMode) {
                    const createRes = await fetch(BASE_URL + '/cashier/new-invoice', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            items: directItems.map(i => ({ product_id: i.product_id, quantity: i.quantity })),
                            gross_total: grossTotal,
                            discount: discount,
                            total: total
                        })
                    });
                    const createData = await createRes.json();
                    if (!createData.success) return toast(createData.message, 'error');
                    orderId = createData.data.id;
                } else {
                    orderId = currentOrder.id;
                }

                const payRes = await fetch(BASE_URL + '/cashier/pay', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        order_id: orderId,
                        discount: discount,
                        payments: payments.map(p => ({
                            payment_method_id: p.payment_method_id,
                            amount: p.amount
                        })),
                        delivery_type: deliveryType,
                        delivery_phone: deliveryPhone
                    })
                });

                const payData = await payRes.json();
                if (payData.success) {
                    printReceipt(payData.data);
                    toast('تم الدفع بنجاح', 'success');
                    resetAll();
                } else {
                    toast(payData.message, 'error');
                }
            } catch (err) {
                console.error('processPayment error:', err);
                toast('خطأ في الاتصال: ' + err.message, 'error');
            }
        }

        function printReceipt(data) {
            const items = isDirectMode ? directItems : (currentOrder?.items || data.items);
            let itemsHtml = '';
            items.forEach(i => {
                const qty = i.is_weight ? parseFloat(i.quantity).toFixed(3) + ' كجم' : i.quantity;
                itemsHtml += `<tr><td>${i.product_name}</td><td style="text-align:center">${qty}</td><td style="text-align:center">${parseFloat(i.price).toFixed(3)}</td><td style="text-align:left">${parseFloat(i.total).toFixed(3)}</td></tr>`;
            });

            let paymentsHtml = '';
            data.payments.forEach(p => {
                paymentsHtml += `<tr><td>${p.method}</td><td style="text-align:left">${parseFloat(p.amount).toFixed(3)}</td></tr>`;
            });

            const discountVal = parseFloat(data.discount) || 0;
            const grossTotal = parseFloat(data.gross_total) || parseFloat(data.total);
            let totalsHtml = '';
            if (discountVal > 0) {
                totalsHtml = `
                    <div class="subtotal"><span>المجموع</span><span>${grossTotal.toFixed(3)} د.ل</span></div>
                    <div class="discount-box"><span>الخصم</span><span>- ${discountVal.toFixed(3)} د.ل</span></div>
                    <div class="total"><span>الصافي</span><span>${parseFloat(data.total).toFixed(3)} د.ل</span></div>
                `;
            } else {
                totalsHtml = `<div class="total"><span>الإجمالي</span><span>${parseFloat(data.total).toFixed(3)} د.ل</span></div>`;
            }

            let deliveryHtml = '';
            if (data.delivery_type === 'delivery') {
                deliveryHtml = `<div class="delivery-box"><div class="delivery-icon">🚚</div><div class="delivery-title">توصيل</div><div class="delivery-phone">${data.delivery_phone || '-'}</div></div>`;
            }

            const html = `<!DOCTYPE html><html dir="rtl"><head><meta charset="UTF-8"><link href="{{ asset('assets/fonts/cairo/cairo.css') }}" rel="stylesheet"><style>@page{margin:0;size:80mm auto}*{margin:0;padding:0;box-sizing:border-box}body{font-family:'Cairo',sans-serif;font-size:13px;padding:10px;width:80mm}.header{text-align:center;padding:10px 0;border-bottom:2px dashed #000;margin-bottom:10px}.logo{max-width:200px;margin-bottom:8px;filter:grayscale(100%) contrast(1.5)}table{width:100%;border-collapse:collapse;margin:8px 0}th{background:#f0f0f0;padding:6px;font-size:11px;border-bottom:1px solid #000}td{padding:6px;font-size:11px;border-bottom:1px dashed #ccc}.subtotal{padding:8px 10px;display:flex;justify-content:space-between;font-size:13px;font-weight:600;border-top:1px dashed #000}.discount-box{padding:10px;margin:6px 0;display:flex;justify-content:space-between;font-size:15px;font-weight:800;border:3px dashed #000;background:#f5f5f5}.total{background:#000;color:#fff;padding:10px;margin:10px 0;display:flex;justify-content:space-between;font-size:16px;font-weight:800}.delivery-box{border:3px solid #000;padding:12px;margin:10px 0;text-align:center;background:#fff}.delivery-icon{font-size:24px;margin-bottom:4px}.delivery-title{font-size:16px;font-weight:800;margin-bottom:4px}.delivery-phone{font-size:18px;font-weight:700;direction:ltr}.section{margin:10px 0;padding:10px 0;border-top:1px dashed #000}.section-title{font-size:12px;font-weight:700;margin-bottom:6px}.info{font-size:11px;display:flex;justify-content:space-between;padding:2px 0}.thanks{text-align:center;font-size:14px;font-weight:700;padding:12px 0;border-top:2px dashed #000}.hulul-footer{display:flex;align-items:center;justify-content:center;gap:10px;padding:12px 0;border-top:2px solid #000;margin-top:10px}.hulul-footer img{height:35px;filter:grayscale(100%) contrast(1.3)}.hulul-footer p{font-size:12px;font-weight:700;color:#000}</style></head><body><div class="header"><img src="{{ asset('logo-dark.png') }}" alt="Taj Alsultan" class="logo"></div><div class="info"><span>رقم الفاتورة:</span><span>#${data.order_number}</span></div><div class="info"><span>التاريخ:</span><span>${data.paid_at}</span></div><div class="info"><span>الكاشير:</span><span>${data.cashier_name}</span></div>${deliveryHtml}<table><thead><tr><th>الصنف</th><th>الكمية</th><th>السعر</th><th>الإجمالي</th></tr></thead><tbody>${itemsHtml}</tbody></table>${totalsHtml}<div class="section"><div class="section-title">طرق الدفع</div><table><thead><tr><th>الطريقة</th><th>المبلغ</th></tr></thead><tbody>${paymentsHtml}</tbody></table></div><div class="thanks">شكراً لزيارتكم</div><div class="hulul-footer"><img src="{{ asset('hulul.jpg') }}" alt="Hulul"><p>حلول لتقنية المعلومات</p></div></body></html>`;

            const win = window.open('', '_blank', 'width=400,height=600');
            if (win) {
                win.document.write(html);
                win.document.close();
                setTimeout(() => {
                    win.focus();
                    if (window.printer && window.printer.print) {
                        window.printer.print();
                    } else {
                        win.print();
                    }
                    win.close();
                }, 250);
            }
        }

        function openCreditModal() {
            const total = getTotal();
            if (total <= 0) return toast('لا يوجد مبلغ للبيع', 'error');

            document.getElementById('creditModal').classList.add('active');
            document.getElementById('customerSearch').value = '';
            document.getElementById('customerResults').innerHTML = '';
            document.getElementById('selectedCustomerBox').classList.add('hidden');
            document.getElementById('newCustomerForm').classList.add('hidden');
            document.getElementById('showNewCustomerForm').classList.remove('hidden');
            document.getElementById('creditPaidAmount').value = '0';
            selectedCustomer = null;
            updateCreditRemaining();
            document.getElementById('customerSearch').focus();
        }

        function closeCreditModal() {
            document.getElementById('creditModal').classList.remove('active');
            selectedCustomer = null;
        }

        async function searchCustomers() {
            const q = document.getElementById('customerSearch').value.trim();
            if (!q) {
                document.getElementById('customerResults').innerHTML = '';
                return;
            }

            try {
                const res = await fetch(BASE_URL + `/cashier/search-customers?q=${encodeURIComponent(q)}`);
                const data = await res.json();
                if (data.success) {
                    renderCustomerResults(data.data);
                }
            } catch (err) {
                console.error(err);
            }
        }

        function renderCustomerResults(customers) {
            const container = document.getElementById('customerResults');
            if (customers.length === 0) {
                container.innerHTML = '<div style="text-align:center;color:#64748b;padding:12px;">لا توجد نتائج</div>';
                return;
            }

            container.innerHTML = customers.map(c => `
                <div class="customer-result-item" onclick="selectCustomer(${c.id}, '${c.name}', '${c.phone || ''}', ${c.balance})" style="padding:10px;border:1px solid #e2e8f0;border-radius:6px;margin-bottom:6px;cursor:pointer;transition:background 0.15s;">
                    <div style="font-weight:600;">${c.name}</div>
                    <div style="font-size:12px;color:#64748b;">${c.phone || '-'}</div>
                    <div style="font-size:12px;color:${c.balance < 0 ? '#dc2626' : '#22c55e'};">الرصيد: ${parseFloat(c.balance).toFixed(3)}</div>
                </div>
            `).join('');
        }

        function selectCustomer(id, name, phone, balance) {
            selectedCustomer = { id, name, phone, balance };
            document.getElementById('selectedCustomerName').textContent = name;
            document.getElementById('selectedCustomerPhone').textContent = phone || '-';
            document.getElementById('selectedCustomerBalance').textContent = 'الرصيد: ' + parseFloat(balance).toFixed(3);
            document.getElementById('selectedCustomerBox').classList.remove('hidden');
            document.getElementById('customerResults').innerHTML = '';
            document.getElementById('customerSearch').value = '';
            document.getElementById('newCustomerForm').classList.add('hidden');
            document.getElementById('showNewCustomerForm').classList.add('hidden');
            updateCreditConfirmBtn();
        }

        function clearSelectedCustomer() {
            selectedCustomer = null;
            document.getElementById('selectedCustomerBox').classList.add('hidden');
            document.getElementById('showNewCustomerForm').classList.remove('hidden');
            updateCreditConfirmBtn();
        }

        function showNewCustomerForm() {
            document.getElementById('newCustomerForm').classList.remove('hidden');
            document.getElementById('showNewCustomerForm').classList.add('hidden');
            document.getElementById('newCustomerName').value = '';
            document.getElementById('newCustomerPhone').value = '';
            document.getElementById('newCustomerName').focus();
        }

        function hideNewCustomerForm() {
            document.getElementById('newCustomerForm').classList.add('hidden');
            document.getElementById('showNewCustomerForm').classList.remove('hidden');
        }

        async function saveNewCustomer() {
            const name = document.getElementById('newCustomerName').value.trim();
            const phone = document.getElementById('newCustomerPhone').value.trim();

            if (!name) return toast('أدخل اسم الزبون', 'error');

            try {
                const res = await fetch(BASE_URL + '/cashier/quick-customer', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ name, phone })
                });
                const data = await res.json();
                if (data.success) {
                    selectCustomer(data.data.id, data.data.name, data.data.phone, data.data.balance);
                    toast('تم إضافة الزبون', 'success');
                } else {
                    toast(data.message || 'خطأ في الحفظ', 'error');
                }
            } catch (err) {
                console.error('saveNewCustomer error:', err);
                toast('خطأ في الاتصال: ' + err.message, 'error');
            }
        }

        function updateCreditRemaining() {
            const total = getTotal();
            const paid = parseFloat(document.getElementById('creditPaidAmount').value) || 0;
            const remaining = Math.max(0, total - paid);
            document.getElementById('creditRemainingAmount').textContent = remaining.toFixed(3);
            updateCreditConfirmBtn();
        }

        function updateCreditConfirmBtn() {
            const btn = document.getElementById('confirmCredit');
            const total = getTotal();
            const paid = parseFloat(document.getElementById('creditPaidAmount').value) || 0;
            btn.disabled = !selectedCustomer || paid > total;
        }

        async function processCreditPayment() {
            if (!selectedCustomer) return toast('اختر زبون', 'error');

            const total = getTotal();
            const paidAmount = parseFloat(document.getElementById('creditPaidAmount').value) || 0;
            const creditAmount = total - paidAmount;

            if (paidAmount > total) return toast('المبلغ المدفوع أكبر من الإجمالي', 'error');

            const confirm = await Swal.fire({
                title: '<i class="ti ti-clock-dollar" style="color:#f97316;font-size:28px;"></i><br>تأكيد البيع بالآجل',
                html: `
                    <div style="text-align:right;font-size:15px;direction:rtl;font-family:'Cairo',sans-serif;">
                        <div style="background:#f8fafc;border-radius:8px;padding:12px;margin-bottom:12px;">
                            <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px dashed #e2e8f0;">
                                <span style="color:#64748b;">الزبون</span>
                                <span style="font-weight:700;">${selectedCustomer.name}</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;padding:6px 0;">
                                <span style="color:#64748b;">إجمالي الفاتورة</span>
                                <span style="font-weight:700;">${total.toFixed(3)} د.ل</span>
                            </div>
                        </div>
                        <div style="display:flex;justify-content:space-between;padding:10px;background:#f0fdf4;border-radius:6px;margin-bottom:8px;">
                            <span style="color:#15803d;font-weight:600;">المدفوع</span>
                            <span style="color:#22c55e;font-weight:800;font-size:16px;">${paidAmount.toFixed(3)} د.ل</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;padding:12px;background:#fef2f2;border:2px solid #fecaca;border-radius:8px;margin-bottom:12px;">
                            <span style="color:#991b1b;font-weight:700;">المبلغ الآجل</span>
                            <span style="color:#dc2626;font-weight:800;font-size:18px;">${creditAmount.toFixed(3)} د.ل</span>
                        </div>
                        <div style="background:#fff;border:2px solid #e2e8f0;border-radius:8px;padding:12px;">
                            <div style="font-weight:700;margin-bottom:10px;color:#475569;font-size:13px;">نوع الاستلام</div>
                            <div style="display:flex;gap:10px;margin-bottom:10px;">
                                <label style="flex:1;display:flex;align-items:center;justify-content:center;gap:6px;padding:10px;background:#f0fdf4;border:2px solid #22c55e;border-radius:8px;cursor:pointer;font-weight:600;">
                                    <input type="radio" name="creditDeliveryType" value="pickup" checked style="width:18px;height:18px;">
                                    <i class="ti ti-building-store" style="font-size:18px;"></i> استلام
                                </label>
                                <label style="flex:1;display:flex;align-items:center;justify-content:center;gap:6px;padding:10px;background:#f8fafc;border:2px solid #e2e8f0;border-radius:8px;cursor:pointer;font-weight:600;" id="creditDeliveryLabel">
                                    <input type="radio" name="creditDeliveryType" value="delivery" style="width:18px;height:18px;">
                                    <i class="ti ti-truck-delivery" style="font-size:18px;"></i> توصيل
                                </label>
                            </div>
                            <div id="creditDeliveryPhoneBox" style="display:none;">
                                <input type="text" id="creditDeliveryPhoneInput" placeholder="رقم الهاتف للتوصيل" style="width:100%;padding:10px 12px;border:2px solid #e2e8f0;border-radius:8px;font-size:14px;font-family:inherit;text-align:center;">
                            </div>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: '<i class="ti ti-check"></i> تأكيد البيع',
                cancelButtonText: 'إلغاء',
                confirmButtonColor: '#f97316',
                cancelButtonColor: '#64748b',
                customClass: {
                    popup: 'swal-rtl',
                    title: 'swal-title-rtl'
                },
                didOpen: () => {
                    const radios = document.querySelectorAll('input[name="creditDeliveryType"]');
                    const phoneBox = document.getElementById('creditDeliveryPhoneBox');
                    const pickupLabel = radios[0].closest('label');
                    const deliveryLabel = radios[1].closest('label');
                    radios.forEach(radio => {
                        radio.addEventListener('change', () => {
                            if (radio.value === 'delivery') {
                                phoneBox.style.display = 'block';
                                deliveryLabel.style.background = '#fef3c7';
                                deliveryLabel.style.borderColor = '#f59e0b';
                                pickupLabel.style.background = '#f8fafc';
                                pickupLabel.style.borderColor = '#e2e8f0';
                                document.getElementById('creditDeliveryPhoneInput').focus();
                            } else {
                                phoneBox.style.display = 'none';
                                pickupLabel.style.background = '#f0fdf4';
                                pickupLabel.style.borderColor = '#22c55e';
                                deliveryLabel.style.background = '#f8fafc';
                                deliveryLabel.style.borderColor = '#e2e8f0';
                            }
                        });
                    });
                },
                preConfirm: () => {
                    const deliveryType = document.querySelector('input[name="creditDeliveryType"]:checked').value;
                    const deliveryPhone = document.getElementById('creditDeliveryPhoneInput').value.trim();
                    if (deliveryType === 'delivery' && !deliveryPhone) {
                        Swal.showValidationMessage('أدخل رقم الهاتف للتوصيل');
                        return false;
                    }
                    return { deliveryType, deliveryPhone };
                }
            });

            if (!confirm.isConfirmed) return;

            const creditDeliveryType = confirm.value.deliveryType;
            const creditDeliveryPhone = confirm.value.deliveryPhone;

            try {
                let orderId;
                const grossTotal = getGrossTotal();

                if (isDirectMode) {
                    const createRes = await fetch(BASE_URL + '/cashier/new-invoice', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            items: directItems.map(i => ({ product_id: i.product_id, quantity: i.quantity })),
                            gross_total: grossTotal,
                            discount: discount,
                            total: total
                        })
                    });
                    const createData = await createRes.json();
                    if (!createData.success) return toast(createData.message, 'error');
                    orderId = createData.data.id;
                } else {
                    orderId = currentOrder.id;
                }

                const firstPaymentMethod = document.querySelector('.pm-btn:not(.credit-btn)');
                const defaultPaymentMethodId = firstPaymentMethod ? firstPaymentMethod.dataset.id : 1;

                const creditPayments = paidAmount > 0 ? [{ payment_method_id: defaultPaymentMethodId, amount: paidAmount }] : [];

                const payRes = await fetch(BASE_URL + '/cashier/pay', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        order_id: orderId,
                        discount: discount,
                        payments: creditPayments,
                        is_credit: true,
                        customer_id: selectedCustomer.id,
                        paid_amount: paidAmount,
                        delivery_type: creditDeliveryType,
                        delivery_phone: creditDeliveryPhone
                    })
                });

                const payData = await payRes.json();
                if (payData.success) {
                    closeCreditModal();
                    printCreditReceipt(payData.data);
                    toast('تم حفظ البيع بالآجل', 'success');
                    resetAll();
                } else {
                    toast(payData.message, 'error');
                }
            } catch (err) {
                console.error('processCreditPayment error:', err);
                toast('خطأ في الاتصال: ' + err.message, 'error');
            }
        }

        function printCreditReceipt(data) {
            const items = isDirectMode ? directItems : (currentOrder?.items || data.items);
            let itemsHtml = '';
            items.forEach(i => {
                const qty = i.is_weight ? parseFloat(i.quantity).toFixed(3) + ' كجم' : i.quantity;
                itemsHtml += `<tr><td>${i.product_name}</td><td style="text-align:center">${qty}</td><td style="text-align:center">${parseFloat(i.price).toFixed(3)}</td><td style="text-align:left">${parseFloat(i.total).toFixed(3)}</td></tr>`;
            });

            const discountVal = parseFloat(data.discount) || 0;
            const grossTotal = parseFloat(data.gross_total) || parseFloat(data.total);
            const creditAmount = parseFloat(data.credit_amount) || 0;
            const paidAmount = parseFloat(data.total) - creditAmount;

            let totalsHtml = '';
            if (discountVal > 0) {
                totalsHtml = `
                    <div class="subtotal"><span>المجموع</span><span>${grossTotal.toFixed(3)} د.ل</span></div>
                    <div class="discount-box"><span>الخصم</span><span>- ${discountVal.toFixed(3)} د.ل</span></div>
                    <div class="total"><span>الصافي</span><span>${parseFloat(data.total).toFixed(3)} د.ل</span></div>
                `;
            } else {
                totalsHtml = `<div class="total"><span>الإجمالي</span><span>${parseFloat(data.total).toFixed(3)} د.ل</span></div>`;
            }

            let creditHtml = '';
            if (creditAmount > 0) {
                creditHtml = `
                    <div class="credit-section">
                        <div class="credit-title"><i class="ti ti-clock-dollar"></i> بيع آجل</div>
                        <div class="credit-customer">${data.customer_name || '-'}</div>
                        <div class="credit-row"><span>المدفوع</span><span>${paidAmount.toFixed(3)} د.ل</span></div>
                        <div class="credit-row credit-amount"><span>المتبقي (آجل)</span><span>${creditAmount.toFixed(3)} د.ل</span></div>
                    </div>
                `;
            }

            let deliveryHtml = '';
            if (data.delivery_type === 'delivery') {
                deliveryHtml = `<div class="delivery-box"><div class="delivery-icon">🚚</div><div class="delivery-title">توصيل</div><div class="delivery-phone">${data.delivery_phone || '-'}</div></div>`;
            }

            const html = `<!DOCTYPE html><html dir="rtl"><head><meta charset="UTF-8"><link href="{{ asset('assets/fonts/cairo/cairo.css') }}" rel="stylesheet"><style>@page{margin:0;size:80mm auto}*{margin:0;padding:0;box-sizing:border-box}body{font-family:'Cairo',sans-serif;font-size:13px;padding:10px;width:80mm}.header{text-align:center;padding:10px 0;border-bottom:2px dashed #000;margin-bottom:10px}.logo{max-width:200px;margin-bottom:8px;filter:grayscale(100%) contrast(1.5)}table{width:100%;border-collapse:collapse;margin:8px 0}th{background:#f0f0f0;padding:6px;font-size:11px;border-bottom:1px solid #000}td{padding:6px;font-size:11px;border-bottom:1px dashed #ccc}.subtotal{padding:8px 10px;display:flex;justify-content:space-between;font-size:13px;font-weight:600;border-top:1px dashed #000}.discount-box{padding:10px;margin:6px 0;display:flex;justify-content:space-between;font-size:15px;font-weight:800;border:3px dashed #000;background:#f5f5f5}.total{background:#000;color:#fff;padding:10px;margin:10px 0;display:flex;justify-content:space-between;font-size:16px;font-weight:800}.delivery-box{border:3px solid #000;padding:12px;margin:10px 0;text-align:center;background:#fff}.delivery-icon{font-size:24px;margin-bottom:4px}.delivery-title{font-size:16px;font-weight:800;margin-bottom:4px}.delivery-phone{font-size:18px;font-weight:700;direction:ltr}.credit-section{border:3px solid #000;padding:12px;margin:12px 0;background:#fff5f5}.credit-title{font-size:14px;font-weight:800;text-align:center;margin-bottom:8px}.credit-customer{text-align:center;font-weight:700;margin-bottom:8px;font-size:13px}.credit-row{display:flex;justify-content:space-between;padding:4px 0;font-size:12px}.credit-amount{font-weight:800;font-size:14px;border-top:1px dashed #000;padding-top:8px;margin-top:4px}.info{font-size:11px;display:flex;justify-content:space-between;padding:2px 0}.thanks{text-align:center;font-size:14px;font-weight:700;padding:12px 0;border-top:2px dashed #000}.hulul-footer{display:flex;align-items:center;justify-content:center;gap:10px;padding:12px 0;border-top:2px solid #000;margin-top:10px}.hulul-footer img{height:35px;filter:grayscale(100%) contrast(1.3)}.hulul-footer p{font-size:12px;font-weight:700;color:#000}</style></head><body><div class="header"><img src="{{ asset('logo-dark.png') }}" alt="Taj Alsultan" class="logo"></div><div class="info"><span>رقم الفاتورة:</span><span>#${data.order_number}</span></div><div class="info"><span>التاريخ:</span><span>${data.paid_at}</span></div><div class="info"><span>الكاشير:</span><span>${data.cashier_name}</span></div>${deliveryHtml}<table><thead><tr><th>الصنف</th><th>الكمية</th><th>السعر</th><th>الإجمالي</th></tr></thead><tbody>${itemsHtml}</tbody></table>${totalsHtml}${creditHtml}<div class="thanks">شكراً لزيارتكم</div><div class="hulul-footer"><img src="{{ asset('hulul.jpg') }}" alt="Hulul"><p>حلول لتقنية المعلومات</p></div></body></html>`;

            const win = window.open('', '_blank', 'width=400,height=600');
            if (win) {
                win.document.write(html);
                win.document.close();
                setTimeout(() => {
                    win.focus();
                    if (window.printer && window.printer.print) {
                        window.printer.print();
                    } else {
                        win.print();
                    }
                    win.close();
                }, 250);
            }
        }

        function resetAll() {
            currentOrder = null;
            directItems = [];
            isDirectMode = false;
            payments = [];
            discount = 0;
            selectedCustomer = null;
            isCredit = false;

            document.getElementById('invoiceInput').value = '';
            document.getElementById('invoiceInput').disabled = false;
            document.getElementById('orderBox').classList.add('hidden');
            document.getElementById('emptyState').classList.remove('hidden');
            document.getElementById('itemsTable').classList.add('hidden');
            document.getElementById('itemsBody').innerHTML = '';
            document.getElementById('invoiceTotal').textContent = '0.000';
            clearPayments();
            updateSummary();
            document.getElementById('invoiceInput').focus();
        }

        function toast(msg, type = 'info') {
            Swal.fire({
                toast: true,
                position: 'top',
                icon: type,
                title: msg,
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                customClass: {
                    popup: 'swal-rtl'
                }
            });
        }
    </script>
</body>
</html>
