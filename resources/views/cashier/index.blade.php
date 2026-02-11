<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>الكاشير - {{ config('app.name') }}</title>
    <link href="{{ asset('assets/fonts/cairo/cairo.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/plugins/tabler-icons/tabler-icons.min.css') }}">
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
            padding: 14px 16px;
            border-bottom: 1px solid #e2e8f0;
            flex-shrink: 0;
        }

        .payment-title {
            font-size: 15px;
            font-weight: 700;
            color: #64748b;
        }

        .payment-methods-scroll {
            flex: 1;
            overflow-y: auto;
            padding: 12px;
            min-height: 0;
        }

        .payment-methods {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
        }

        .pm-btn {
            padding: 12px;
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
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

        .added-payments {
            padding: 12px;
            border-top: 1px solid #e2e8f0;
            flex-shrink: 0;
        }

        .added-title {
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 8px;
        }

        .payment-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 10px;
            background: #f0fdf4;
            border-radius: 6px;
            margin-bottom: 6px;
        }

        .payment-row .method {
            font-weight: 600;
            font-size: 13px;
        }

        .payment-row .amount {
            font-weight: 700;
            color: #059669;
        }

        .payment-row .remove-btn {
            background: none;
            border: none;
            color: #ef4444;
            cursor: pointer;
            padding: 2px;
            font-size: 16px;
        }

        .amount-input-section {
            padding: 12px;
            border-top: 1px solid #e2e8f0;
            flex-shrink: 0;
        }

        .amount-label {
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 6px;
        }

        .amount-row {
            display: flex;
            gap: 8px;
        }

        .amount-input {
            flex: 1;
            padding: 10px 12px;
            font-size: 18px;
            font-weight: 700;
            text-align: center;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-family: inherit;
        }

        .amount-input:focus {
            outline: none;
            border-color: #3b82f6;
        }

        .add-payment-btn {
            padding: 10px 16px;
            background: #3b82f6;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: 600;
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
        }

        .remove-discount-btn {
            background: none;
            border: none;
            color: #ef4444;
            cursor: pointer;
            padding: 0 4px;
            font-size: 12px;
            vertical-align: middle;
        }

        .discount-btn {
            background: none;
            border: none;
            color: #f59e0b;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            font-family: inherit;
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 0;
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
            padding: 12px;
            border-top: 1px solid #e2e8f0;
            flex-shrink: 0;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            font-size: 13px;
        }

        .summary-row.total {
            font-size: 15px;
            font-weight: 800;
            padding-top: 8px;
            border-top: 1px dashed #e2e8f0;
            margin-top: 4px;
        }

        .summary-row.diff {
            color: #dc2626;
            font-weight: 700;
        }

        .summary-row.diff.ok {
            color: #059669;
        }

        .pay-section {
            padding: 12px;
            border-top: 1px solid #e2e8f0;
            flex-shrink: 0;
        }

        .pay-btn {
            width: 100%;
            padding: 16px;
            background: #10b981;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
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
                <div class="order-box hidden" id="orderBox">
                    <div class="order-num">رقم الطلب: <span id="orderNum">-</span></div>
                </div>

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

    <script>
        const WEIGHT_PREFIX = '99';
        const MAX_DISCOUNT = 5;
        let currentOrder = null;
        let directItems = [];
        let isDirectMode = false;
        let payments = [];
        let selectedMethod = null;
        let discount = 0;

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
                title: 'إلغاء الأصناف',
                text: 'هل تريد إلغاء جميع الأصناف؟',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'نعم، إلغاء',
                cancelButtonText: 'لا',
                confirmButtonColor: '#ef4444'
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
                const res = await fetch('/cashier/add-weight-barcode', {
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
                        const addRes = await fetch('/cashier/add-weight-item', {
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
                toast('خطأ في الاتصال', 'error');
            }
        }

        async function fetchOrder(num) {
            try {
                const res = await fetch('/cashier/fetch-order', {
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
                            const addRes = await fetch('/cashier/add-weight-item', {
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
                toast('خطأ في الاتصال', 'error');
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
                const res = await fetch('/cashier/remove-item', {
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
                toast('خطأ في الاتصال', 'error');
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
                paymentsHtml += `<div style="display:flex;justify-content:space-between;padding:4px 0;"><span>${p.method_name}</span><span>${parseFloat(p.amount).toFixed(3)}</span></div>`;
            });

            const grossTotal = getGrossTotal();
            let discountHtml = '';
            if (discount > 0) {
                discountHtml = `<div style="color:#f59e0b;"><strong>الخصم:</strong> -${discount.toFixed(3)} د.ل</div>`;
            }

            const confirm = await Swal.fire({
                title: 'تأكيد الدفع',
                html: `
                    <div style="text-align:right;font-size:14px;">
                        <div style="margin-bottom:8px;padding-bottom:8px;border-bottom:1px solid #eee;">
                            <strong>إجمالي الفاتورة:</strong> ${grossTotal.toFixed(3)} د.ل
                            ${discountHtml}
                            <strong>الصافي:</strong> ${total.toFixed(3)} د.ل
                        </div>
                        <div style="font-weight:600;margin-bottom:4px;">المدفوعات:</div>
                        ${paymentsHtml}
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'تأكيد',
                cancelButtonText: 'إلغاء',
                confirmButtonColor: '#10b981'
            });

            if (!confirm.isConfirmed) return;

            try {
                let orderId;

                if (isDirectMode) {
                    const createRes = await fetch('/cashier/new-invoice', {
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

                const payRes = await fetch('/cashier/pay', {
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
                        }))
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
                toast('خطأ في الاتصال', 'error');
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

            const html = `<!DOCTYPE html><html dir="rtl"><head><meta charset="UTF-8"><link href="{{ asset('assets/fonts/cairo/cairo.css') }}" rel="stylesheet"><style>@page{margin:0;size:80mm auto}*{margin:0;padding:0;box-sizing:border-box}body{font-family:'Cairo',sans-serif;font-size:13px;padding:10px;width:80mm}.header{text-align:center;padding:10px 0;border-bottom:2px dashed #000;margin-bottom:10px}.logo{max-width:200px;margin-bottom:8px;filter:grayscale(100%) contrast(1.5)}table{width:100%;border-collapse:collapse;margin:8px 0}th{background:#f0f0f0;padding:6px;font-size:11px;border-bottom:1px solid #000}td{padding:6px;font-size:11px;border-bottom:1px dashed #ccc}.subtotal{padding:8px 10px;display:flex;justify-content:space-between;font-size:13px;font-weight:600;border-top:1px dashed #000}.discount-box{padding:10px;margin:6px 0;display:flex;justify-content:space-between;font-size:15px;font-weight:800;border:3px dashed #000;background:#f5f5f5}.total{background:#000;color:#fff;padding:10px;margin:10px 0;display:flex;justify-content:space-between;font-size:16px;font-weight:800}.section{margin:10px 0;padding:10px 0;border-top:1px dashed #000}.section-title{font-size:12px;font-weight:700;margin-bottom:6px}.info{font-size:11px;display:flex;justify-content:space-between;padding:2px 0}.thanks{text-align:center;font-size:14px;font-weight:700;padding:12px 0;border-top:2px dashed #000}.hulul-footer{display:flex;align-items:center;justify-content:center;gap:10px;padding:12px 0;border-top:2px solid #000;margin-top:10px}.hulul-footer img{height:35px;filter:grayscale(100%) contrast(1.3)}.hulul-footer p{font-size:12px;font-weight:700;color:#000}</style></head><body><div class="header"><img src="{{ asset('logo-dark.png') }}" alt="Taj Alsultan" class="logo"></div><div class="info"><span>رقم الفاتورة:</span><span>#${data.order_number}</span></div><div class="info"><span>التاريخ:</span><span>${data.paid_at}</span></div><div class="info"><span>الكاشير:</span><span>${data.cashier_name}</span></div><table><thead><tr><th>الصنف</th><th>الكمية</th><th>السعر</th><th>الإجمالي</th></tr></thead><tbody>${itemsHtml}</tbody></table>${totalsHtml}<div class="section"><div class="section-title">طرق الدفع</div><table><thead><tr><th>الطريقة</th><th>المبلغ</th></tr></thead><tbody>${paymentsHtml}</tbody></table></div><div class="thanks">شكراً لزيارتكم</div><div class="hulul-footer"><img src="{{ asset('hulul.jpg') }}" alt="Hulul"><p>حلول لتقنية المعلومات</p></div></body></html>`;

            const win = window.open('', '_blank', 'width=400,height=600');
            if (win) {
                win.document.write(html);
                win.document.close();
                setTimeout(() => { win.focus(); win.print(); win.close(); }, 250);
            }
        }

        function resetAll() {
            currentOrder = null;
            directItems = [];
            isDirectMode = false;
            payments = [];
            discount = 0;

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
                timerProgressBar: true
            });
        }
    </script>
</body>
</html>
