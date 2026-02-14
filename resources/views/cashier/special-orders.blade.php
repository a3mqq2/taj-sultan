<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>الطلبيات الخاصة - {{ config('app.name') }}</title>
    <link href="{{ asset('assets/fonts/cairo/cairo.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/plugins/tabler-icons/tabler-icons.min.css') }}">
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; overflow: hidden; }
        body { font-family: 'Cairo', sans-serif; background: #f1f5f9; color: #1e293b; }
        .app-container { display: flex; flex-direction: column; height: 100vh; overflow: hidden; }
        .header { background: #1e293b; padding: 12px 24px; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0; }
        .header .logo { height: 80px; }
        .header .logo img { height: 100%; width: auto; }
        .header-left { display: flex; align-items: center; gap: 12px; }
        .back-btn { display: flex; align-items: center; gap: 6px; padding: 8px 16px; background: #3b82f6; border: none; border-radius: 8px; color: #fff; font-weight: 600; cursor: pointer; font-family: inherit; font-size: 14px; text-decoration: none; transition: all 0.2s; }
        .back-btn:hover { background: #2563eb; color: #fff; }
        .user-info { display: flex; align-items: center; gap: 6px; padding: 6px 12px; background: rgba(255,255,255,0.1); border-radius: 6px; font-weight: 600; font-size: 14px; color: #fff; }
        .main-content { flex: 1; display: grid; grid-template-columns: 1fr 420px; gap: 16px; padding: 16px; overflow: hidden; min-height: 0; }
        .left-panel { display: flex; flex-direction: column; gap: 16px; overflow: hidden; min-height: 0; }
        .search-section { background: #fff; border-radius: 12px; padding: 16px; flex-shrink: 0; }
        .search-row { display: flex; gap: 12px; }
        .search-input { flex: 1; padding: 14px 18px; font-size: 16px; font-weight: 600; border: 2px solid #e2e8f0; border-radius: 10px; font-family: inherit; }
        .search-input:focus { outline: none; border-color: #8b5cf6; }
        .search-btn { padding: 14px 24px; background: #8b5cf6; color: #fff; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; font-family: inherit; font-size: 14px; }
        .search-btn:hover { background: #7c3aed; }
        .new-order-btn { padding: 14px 24px; background: #10b981; color: #fff; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; font-family: inherit; font-size: 14px; display: flex; align-items: center; gap: 6px; }
        .new-order-btn:hover { background: #059669; }
        .order-section { background: #fff; border-radius: 12px; flex: 1; display: flex; flex-direction: column; overflow: hidden; min-height: 0; }
        .section-header { display: flex; justify-content: space-between; align-items: center; padding: 14px 16px; border-bottom: 1px solid #e2e8f0; flex-shrink: 0; background: #fff; }
        .section-title { font-size: 16px; font-weight: 700; display: flex; align-items: center; gap: 8px; }
        .order-content { flex: 1; overflow-y: auto; padding: 16px; min-height: 0; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px; }
        .form-group { display: flex; flex-direction: column; gap: 6px; }
        .form-group.full { grid-column: span 2; }
        .form-label { font-size: 13px; font-weight: 600; color: #64748b; }
        .form-control { padding: 10px 14px; font-size: 14px; border: 2px solid #e2e8f0; border-radius: 8px; font-family: inherit; }
        .form-control:focus { outline: none; border-color: #8b5cf6; }
        .products-section { margin-top: 20px; border-top: 1px solid #e2e8f0; padding-top: 20px; }
        .products-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
        .add-product-btn { padding: 8px 16px; background: #3b82f6; color: #fff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-family: inherit; font-size: 13px; display: flex; align-items: center; gap: 4px; }
        .add-product-btn:hover { background: #2563eb; }
        .items-table { width: 100%; border-collapse: collapse; }
        .items-table th { background: #f8fafc; padding: 10px 12px; text-align: right; font-weight: 700; font-size: 13px; color: #64748b; }
        .items-table td { padding: 12px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
        .items-table tr:hover { background: #f8fafc; }
        .remove-btn { background: none; border: none; color: #ef4444; cursor: pointer; padding: 4px; font-size: 16px; }
        .weight-tag { display: inline-flex; align-items: center; gap: 2px; color: #8b5cf6; font-size: 11px; margin-right: 4px; }
        .right-panel { display: flex; flex-direction: column; gap: 16px; overflow: hidden; min-height: 0; }
        .info-box { background: #fff; border-radius: 12px; padding: 16px; flex-shrink: 0; }
        .info-title { font-size: 14px; font-weight: 700; color: #64748b; margin-bottom: 12px; display: flex; align-items: center; gap: 6px; }
        .info-row { display: flex; justify-content: space-between; padding: 8px 0; font-size: 14px; border-bottom: 1px solid #f1f5f9; }
        .info-row:last-child { border-bottom: none; }
        .info-row .label { color: #64748b; }
        .info-row .value { font-weight: 700; }
        .info-row.total { font-size: 18px; padding-top: 12px; border-top: 2px solid #e2e8f0; margin-top: 8px; }
        .info-row.total .value { color: #10b981; }
        .info-row.remaining .value { color: #ef4444; }
        .info-row.paid .value { color: #3b82f6; }
        .payment-box { background: #fff; border-radius: 12px; flex: 1; display: flex; flex-direction: column; overflow: hidden; min-height: 0; }
        .payment-header { padding: 14px 16px; border-bottom: 1px solid #e2e8f0; flex-shrink: 0; }
        .payment-title { font-size: 15px; font-weight: 700; color: #64748b; }
        .payment-methods-scroll { flex: 1; overflow-y: auto; padding: 12px; min-height: 0; }
        .payment-methods { display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px; }
        .pm-btn { padding: 12px; background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; font-family: inherit; text-align: center; transition: all 0.15s; }
        .pm-btn:hover { border-color: #8b5cf6; background: #faf5ff; }
        .pm-btn.selected { border-color: #8b5cf6; background: #8b5cf6; color: #fff; }
        .amount-section { padding: 12px; border-top: 1px solid #e2e8f0; flex-shrink: 0; }
        .amount-label { font-size: 12px; font-weight: 600; color: #64748b; margin-bottom: 6px; }
        .amount-row { display: flex; gap: 8px; }
        .amount-input { flex: 1; padding: 10px 12px; font-size: 18px; font-weight: 700; text-align: center; border: 2px solid #e2e8f0; border-radius: 8px; font-family: inherit; }
        .amount-input:focus { outline: none; border-color: #8b5cf6; }
        .pay-section { padding: 12px; border-top: 1px solid #e2e8f0; flex-shrink: 0; }
        .pay-btn { width: 100%; padding: 16px; background: #10b981; color: #fff; border: none; border-radius: 10px; font-size: 18px; font-weight: 700; cursor: pointer; font-family: inherit; display: flex; align-items: center; justify-content: center; gap: 8px; }
        .pay-btn:hover { background: #059669; }
        .pay-btn:disabled { background: #cbd5e1; cursor: not-allowed; }
        .pay-btn.save { background: #8b5cf6; }
        .pay-btn.save:hover { background: #7c3aed; }
        .status-badge { display: inline-flex; align-items: center; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .status-badge.pending { background: #fef3c7; color: #d97706; }
        .status-badge.in_progress { background: #dbeafe; color: #2563eb; }
        .status-badge.ready { background: #e0e7ff; color: #4f46e5; }
        .status-badge.delivered { background: #d1fae5; color: #059669; }
        .status-badge.cancelled { background: #fee2e2; color: #dc2626; }
        .empty-state { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 60px; color: #94a3b8; }
        .empty-state i { font-size: 64px; margin-bottom: 16px; }
        .empty-state h4 { font-size: 18px; margin-bottom: 8px; color: #64748b; }
        .hidden { display: none !important; }
        .validation-error { border-color: #ef4444 !important; background: #fef2f2 !important; }
        .validation-error:focus { border-color: #ef4444 !important; box-shadow: 0 0 0 3px rgba(239,68,68,0.2); }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; }
        .modal.active { display: flex; }
        .modal-content { background: #fff; border-radius: 16px; padding: 24px; width: 100%; max-width: 500px; max-height: 80vh; overflow-y: auto; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .modal-title { font-size: 18px; font-weight: 700; }
        .modal-close { width: 32px; height: 32px; border: none; background: #f1f5f9; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 18px; color: #64748b; }
        .product-list { max-height: 300px; overflow-y: auto; }
        .product-item { display: flex; justify-content: space-between; align-items: center; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; margin-bottom: 8px; cursor: pointer; transition: all 0.15s; }
        .product-item:hover { border-color: #8b5cf6; background: #faf5ff; }
        .product-item.selected { border-color: #8b5cf6; background: #8b5cf6; color: #fff; }
        .product-name { font-weight: 600; }
        .product-price { font-size: 14px; color: #64748b; }
        .product-item.selected .product-price { color: rgba(255,255,255,0.8); }
        .qty-input-group { display: flex; gap: 8px; margin-top: 16px; }
        .qty-input { flex: 1; padding: 12px; font-size: 18px; font-weight: 700; text-align: center; border: 2px solid #e2e8f0; border-radius: 8px; font-family: inherit; }
        .qty-input:focus { outline: none; border-color: #8b5cf6; }
        .modal-actions { display: flex; gap: 10px; margin-top: 20px; }
        .modal-btn { flex: 1; padding: 12px; border-radius: 8px; font-size: 15px; font-weight: 600; cursor: pointer; font-family: inherit; border: none; }
        .modal-btn-cancel { background: #f1f5f9; color: #64748b; }
        .modal-btn-confirm { background: #8b5cf6; color: #fff; }
        .payments-list { margin-top: 12px; }
        .payment-item { display: flex; justify-content: space-between; align-items: center; padding: 10px 12px; background: #f0fdf4; border-radius: 8px; margin-bottom: 6px; }
        .payment-item .method { font-weight: 600; font-size: 13px; }
        .payment-item .amount { font-weight: 700; color: #059669; }
        .customer-search { position: relative; }
        .customer-results { position: absolute; top: 100%; left: 0; right: 0; background: #fff; border: 2px solid #e2e8f0; border-top: none; border-radius: 0 0 8px 8px; max-height: 200px; overflow-y: auto; z-index: 100; display: none; }
        .customer-results.show { display: block; }
        .customer-item { padding: 10px 14px; cursor: pointer; border-bottom: 1px solid #f1f5f9; }
        .customer-item:hover { background: #f8fafc; }
        .customer-item:last-child { border-bottom: none; }
        .customer-item .name { font-weight: 600; }
        .customer-item .phone { font-size: 12px; color: #64748b; }
        .order-section { position: relative; }
        .order-watermark { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); opacity: 0.04; pointer-events: none; z-index: 0; }
        .order-watermark img { width: 300px; height: auto; }
        .order-content { position: relative; z-index: 1; }
        .app-footer { background: #fff; border-top: 1px solid #e2e8f0; padding: 10px 24px; flex-shrink: 0; display: flex; justify-content: space-between; align-items: center; }
        .footer-tips { display: flex; gap: 20px; font-size: 12px; color: #64748b; }
        .footer-tip { display: flex; align-items: center; gap: 4px; }
        .footer-tip kbd { background: #f1f5f9; padding: 2px 6px; border-radius: 4px; font-size: 11px; font-family: inherit; border: 1px solid #e2e8f0; }
        .footer-brand { display: flex; align-items: center; gap: 8px; font-size: 11px; color: #94a3b8; }
        .footer-brand img { height: 24px; width: auto; opacity: 0.7; }
    </style>
</head>
<body>
    <div class="app-container">
        <div class="header">
            <div class="logo"><img src="{{ asset('logo-dark.png') }}" alt="تاج السلطان"></div>
            <div class="header-left">
                <div class="user-info">
                    <i class="ti ti-user"></i>
                    {{ auth()->user()->name }}
                </div>
                <a href="{{ route('cashier.index') }}" class="back-btn">
                    <i class="ti ti-arrow-right"></i>
                    العودة للكاشير
                </a>
            </div>
        </div>

        <div class="main-content">
            <div class="left-panel">
                <div class="search-section">
                    <div class="search-row">
                        <input type="text" class="search-input" id="searchInput" placeholder="رقم الطلبية أو اسم الزبون أو رقم الهاتف">
                        <button class="search-btn" id="searchBtn">
                            <i class="ti ti-search"></i>
                            بحث
                        </button>
                        <button class="new-order-btn" id="newOrderBtn">
                            <i class="ti ti-plus"></i>
                            طلبية جديدة
                        </button>
                    </div>
                </div>

                <div class="order-section">
                    <div class="order-watermark">
                        <img src="{{ asset('logo-dark.png') }}" alt="">
                    </div>
                    <div class="section-header">
                        <div class="section-title" id="sectionTitle">
                            <i class="ti ti-cake"></i>
                            <span>تفاصيل الطلبية</span>
                        </div>
                        <span class="status-badge hidden" id="statusBadge"></span>
                    </div>

                    <div class="order-content" id="orderContent">
                        <div class="empty-state" id="emptyState">
                            <i class="ti ti-cake"></i>
                            <h4>لا توجد طلبية</h4>
                            <p>ابحث عن طلبية أو أنشئ طلبية جديدة</p>
                        </div>

                        <div class="order-form hidden" id="orderForm">
                            <div class="form-grid">
                                <div class="form-group customer-search">
                                    <label class="form-label">اسم الزبون *</label>
                                    <input type="text" class="form-control" id="customerName" placeholder="ابحث أو أدخل اسم جديد" autocomplete="off">
                                    <input type="hidden" id="customerId">
                                    <div class="customer-results" id="customerResults"></div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">رقم الهاتف</label>
                                    <input type="text" class="form-control" id="customerPhone" placeholder="رقم الهاتف">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">نوع المناسبة *</label>
                                    <select class="form-control" id="eventType">
                                        <option value="">اختر المناسبة</option>
                                        @foreach($eventTypes as $type)
                                        <option value="{{ $type->name }}">{{ $type->name }}</option>
                                        @endforeach
                                        <option value="other">أخرى</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">تاريخ التسليم *</label>
                                    <input type="date" class="form-control" id="deliveryDate">
                                </div>
                            </div>

                            <div class="products-section">
                                <div class="products-header">
                                    <div class="section-title">
                                        <i class="ti ti-box"></i>
                                        الأصناف
                                    </div>
                                    <button class="add-product-btn" id="addProductBtn">
                                        <i class="ti ti-plus"></i>
                                        إضافة صنف
                                    </button>
                                </div>

                                <table class="items-table" id="itemsTable">
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

                        <div class="order-view hidden" id="orderView">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">اسم الزبون</label>
                                    <div style="font-weight: 700; font-size: 16px;" id="viewCustomerName">-</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">رقم الهاتف</label>
                                    <div id="viewCustomerPhone">-</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">نوع المناسبة</label>
                                    <div id="viewEventType">-</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">تاريخ التسليم</label>
                                    <div id="viewDeliveryDate">-</div>
                                </div>
                            </div>

                            <div class="products-section">
                                <div class="products-header">
                                    <div class="section-title">
                                        <i class="ti ti-box"></i>
                                        الأصناف
                                    </div>
                                </div>

                                <table class="items-table">
                                    <thead>
                                        <tr>
                                            <th>الصنف</th>
                                            <th style="text-align:center">الكمية</th>
                                            <th style="text-align:center">السعر</th>
                                            <th style="text-align:left">الإجمالي</th>
                                        </tr>
                                    </thead>
                                    <tbody id="viewItemsBody"></tbody>
                                </table>
                            </div>

                            <div class="payments-list" id="paymentsList">
                                <div class="section-title" style="margin-bottom: 12px;">
                                    <i class="ti ti-cash"></i>
                                    المدفوعات
                                </div>
                                <div id="paymentsListBody"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="right-panel">
                <div class="info-box">
                    <div class="info-title">
                        <i class="ti ti-receipt"></i>
                        ملخص الطلبية
                    </div>
                    <div class="info-row total">
                        <span class="label">الإجمالي</span>
                        <span class="value" id="totalAmount">0.000 د.ل</span>
                    </div>
                    <div class="info-row paid">
                        <span class="label">المدفوع</span>
                        <span class="value" id="paidAmount">0.000 د.ل</span>
                    </div>
                    <div class="info-row remaining">
                        <span class="label">المتبقي</span>
                        <span class="value" id="remainingAmount">0.000 د.ل</span>
                    </div>
                </div>

                <div class="payment-box">
                    <div class="payment-header">
                        <div class="payment-title">إضافة دفعة</div>
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

                    <div class="amount-section hidden" id="amountSection">
                        <div class="amount-label">المبلغ (<span id="selectedMethodName">-</span>)</div>
                        <div class="amount-row">
                            <input type="number" class="amount-input" id="amountInput" step="0.001" placeholder="0.000">
                        </div>
                    </div>

                    <div class="pay-section">
                        <button class="pay-btn save hidden" id="saveBtn">
                            <i class="ti ti-device-floppy"></i>
                            حفظ الطلبية
                        </button>
                        <button class="pay-btn hidden" id="addPaymentBtn">
                            <i class="ti ti-cash"></i>
                            إضافة الدفعة
                        </button>
                        <button class="pay-btn hidden" id="printBtn">
                            <i class="ti ti-printer"></i>
                            طباعة الإيصال
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-footer">
            <div class="footer-tips">
                <div class="footer-tip"><kbd>Enter</kbd> بحث / تأكيد</div>
                <div class="footer-tip"><kbd>F4</kbd> طباعة</div>
                <div class="footer-tip"><kbd>Esc</kbd> إغلاق</div>
            </div>
            <div class="footer-brand">
                <img src="{{ asset('hulul.jpg') }}" alt="Hulul">
                <span>حلول لتقنية المعلومات</span>
            </div>
        </div>
    </div>

    <div class="modal" id="productModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">إضافة صنف</div>
                <button class="modal-close" id="closeProductModal"><i class="ti ti-x"></i></button>
            </div>
            <input type="text" class="form-control" id="productSearch" placeholder="ابحث عن صنف..." style="margin-bottom: 12px;">
            <div class="product-list" id="productList"></div>
            <div id="productSearchHint" style="text-align: center; padding: 20px; color: #94a3b8;">
                <i class="ti ti-search" style="font-size: 24px; display: block; margin-bottom: 8px;"></i>
                اكتب للبحث عن صنف
            </div>
            <div class="qty-input-group hidden" id="qtySection">
                <input type="number" class="qty-input" id="qtyInput" step="0.001" placeholder="الكمية">
            </div>
            <div class="modal-actions">
                <button class="modal-btn modal-btn-cancel" id="cancelProductModal">إلغاء</button>
                <button class="modal-btn modal-btn-confirm" id="confirmProduct">إضافة</button>
            </div>
        </div>
    </div>

    <script>
        const BASE_URL = "{{ url('/') }}";
        let currentOrder = null;
        let isNewOrder = false;
        let orderItems = [];
        let selectedProduct = null;
        let selectedPaymentMethod = null;
        let initialPayment = 0;
        let allProducts = [];

        document.addEventListener('DOMContentLoaded', init);

        async function init() {
            document.getElementById('searchBtn').addEventListener('click', searchOrder);
            document.getElementById('searchInput').addEventListener('keydown', e => {
                if (e.key === 'Enter') searchOrder();
            });
            document.getElementById('newOrderBtn').addEventListener('click', startNewOrder);
            document.getElementById('addProductBtn').addEventListener('click', openProductModal);
            document.getElementById('closeProductModal').addEventListener('click', closeProductModal);
            document.getElementById('cancelProductModal').addEventListener('click', closeProductModal);
            document.getElementById('confirmProduct').addEventListener('click', confirmProduct);
            document.getElementById('productSearch').addEventListener('input', searchProducts);
            document.getElementById('saveBtn').addEventListener('click', saveOrder);
            document.getElementById('addPaymentBtn').addEventListener('click', addPayment);
            document.getElementById('printBtn').addEventListener('click', printOrder);

            document.querySelectorAll('.pm-btn').forEach(btn => {
                btn.addEventListener('click', () => selectPaymentMethod(btn));
            });

            document.getElementById('customerName').addEventListener('input', function() {
                searchCustomers();
                this.classList.remove('validation-error');
            });
            document.getElementById('eventType').addEventListener('change', function() {
                this.classList.remove('validation-error');
            });
            document.getElementById('deliveryDate').addEventListener('change', function() {
                this.classList.remove('validation-error');
            });
            document.getElementById('customerName').addEventListener('focus', () => {
                if (document.getElementById('customerResults').innerHTML) {
                    document.getElementById('customerResults').classList.add('show');
                }
            });
            document.addEventListener('click', e => {
                if (!e.target.closest('.customer-search')) {
                    document.getElementById('customerResults').classList.remove('show');
                }
            });

            document.getElementById('qtyInput').addEventListener('keydown', e => {
                if (e.key === 'Enter') confirmProduct();
            });

            document.getElementById('amountInput').addEventListener('keydown', e => {
                if (e.key === 'Enter') {
                    if (isNewOrder) {
                        saveOrder();
                    } else {
                        addPayment();
                    }
                }
            });

            const res = await fetch(BASE_URL + '/cashier/special-orders/products');
            const data = await res.json();
            if (data.success) {
                allProducts = data.data;
            }

            document.addEventListener('keydown', e => {
                if (e.key === 'F4') {
                    e.preventDefault();
                    if (currentOrder && !isNewOrder) printOrder();
                }
                if (e.key === 'Escape') {
                    closeProductModal();
                }
            });
        }

        async function searchCustomers() {
            const query = document.getElementById('customerName').value;
            if (query.length < 2) {
                document.getElementById('customerResults').classList.remove('show');
                return;
            }

            try {
                const res = await fetch(BASE_URL + `/cashier/special-orders/customers?q=${encodeURIComponent(query)}`);
                const data = await res.json();

                if (data.success && data.data.length > 0) {
                    const html = data.data.map(c => `
                        <div class="customer-item" onclick="selectCustomer(${c.id}, '${c.name}', '${c.phone || ''}')">
                            <div class="name">${c.name}</div>
                            <div class="phone">${c.phone || '-'}</div>
                        </div>
                    `).join('');
                    document.getElementById('customerResults').innerHTML = html;
                    document.getElementById('customerResults').classList.add('show');
                } else {
                    document.getElementById('customerResults').classList.remove('show');
                }
            } catch (err) {
                console.error(err);
            }
        }

        function selectCustomer(id, name, phone) {
            document.getElementById('customerId').value = id;
            document.getElementById('customerName').value = name;
            document.getElementById('customerPhone').value = phone;
            document.getElementById('customerResults').classList.remove('show');
        }

        async function searchOrder() {
            const search = document.getElementById('searchInput').value.trim();
            if (!search) return toast('أدخل رقم الطلبية أو اسم الزبون', 'error');

            try {
                const res = await fetch(BASE_URL + '/cashier/special-orders/fetch', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ search })
                });
                const data = await res.json();

                if (data.success) {
                    currentOrder = data.data;
                    isNewOrder = false;
                    renderOrderView();
                    toast('تم جلب الطلبية', 'success');
                } else {
                    toast(data.message, 'error');
                }
            } catch (err) {
                toast('خطأ في الاتصال', 'error');
            }
        }

        function startNewOrder() {
            currentOrder = null;
            isNewOrder = true;
            orderItems = [];
            initialPayment = 0;
            selectedPaymentMethod = null;

            document.getElementById('emptyState').classList.add('hidden');
            document.getElementById('orderView').classList.add('hidden');
            document.getElementById('orderForm').classList.remove('hidden');
            document.getElementById('statusBadge').classList.add('hidden');
            document.getElementById('sectionTitle').querySelector('span').textContent = 'طلبية جديدة';

            document.getElementById('customerId').value = '';
            document.getElementById('customerName').value = '';
            document.getElementById('customerPhone').value = '';
            document.getElementById('eventType').value = '';
            document.getElementById('deliveryDate').value = '';
            document.getElementById('itemsBody').innerHTML = '';

            document.getElementById('saveBtn').classList.remove('hidden');
            document.getElementById('addPaymentBtn').classList.add('hidden');
            document.getElementById('printBtn').classList.add('hidden');
            document.getElementById('amountSection').classList.remove('hidden');

            updateSummary();
            document.getElementById('customerName').focus();
        }

        function renderOrderView() {
            document.getElementById('emptyState').classList.add('hidden');
            document.getElementById('orderForm').classList.add('hidden');
            document.getElementById('orderView').classList.remove('hidden');

            document.getElementById('sectionTitle').querySelector('span').textContent = `طلبية #${currentOrder.id}`;

            const badge = document.getElementById('statusBadge');
            badge.classList.remove('hidden', 'pending', 'in_progress', 'ready', 'delivered', 'cancelled');
            badge.classList.add(currentOrder.status);
            badge.textContent = currentOrder.status_name;

            document.getElementById('viewCustomerName').textContent = currentOrder.customer_name;
            document.getElementById('viewCustomerPhone').textContent = currentOrder.phone || '-';
            document.getElementById('viewEventType').textContent = currentOrder.event_type;
            document.getElementById('viewDeliveryDate').textContent = currentOrder.delivery_date;

            const itemsHtml = currentOrder.items.map(item => {
                const qty = item.is_weight ? parseFloat(item.quantity).toFixed(3) + ' كجم' : item.quantity;
                return `<tr>
                    <td>${item.product_name}${item.is_weight ? '<span class="weight-tag"><i class="ti ti-scale"></i></span>' : ''}</td>
                    <td style="text-align:center">${qty}</td>
                    <td style="text-align:center">${parseFloat(item.price).toFixed(3)}</td>
                    <td style="text-align:left">${parseFloat(item.total).toFixed(3)}</td>
                </tr>`;
            }).join('');
            document.getElementById('viewItemsBody').innerHTML = itemsHtml;

            const paymentsHtml = currentOrder.payments.map(p => `
                <div class="payment-item">
                    <span class="method">${p.method} ${p.notes ? '(' + p.notes + ')' : ''}</span>
                    <span class="amount">${parseFloat(p.amount).toFixed(3)} د.ل</span>
                </div>
            `).join('');
            document.getElementById('paymentsListBody').innerHTML = paymentsHtml || '<div style="color:#94a3b8;text-align:center;padding:12px;">لا توجد مدفوعات</div>';

            document.getElementById('totalAmount').textContent = parseFloat(currentOrder.total_amount).toFixed(3) + ' د.ل';
            document.getElementById('paidAmount').textContent = parseFloat(currentOrder.paid_amount).toFixed(3) + ' د.ل';
            document.getElementById('remainingAmount').textContent = parseFloat(currentOrder.remaining_amount).toFixed(3) + ' د.ل';

            document.getElementById('saveBtn').classList.add('hidden');
            document.getElementById('printBtn').classList.remove('hidden');

            if (currentOrder.status !== 'delivered' && currentOrder.status !== 'cancelled' && currentOrder.remaining_amount > 0) {
                document.getElementById('addPaymentBtn').classList.remove('hidden');
                document.getElementById('amountSection').classList.remove('hidden');
                document.getElementById('amountInput').value = parseFloat(currentOrder.remaining_amount).toFixed(3);
            } else {
                document.getElementById('addPaymentBtn').classList.add('hidden');
                document.getElementById('amountSection').classList.add('hidden');
            }
        }

        function openProductModal() {
            document.getElementById('productModal').classList.add('active');
            document.getElementById('productSearch').value = '';
            document.getElementById('qtyInput').value = '';
            document.getElementById('productList').innerHTML = '';
            document.getElementById('productSearchHint').classList.remove('hidden');
            document.getElementById('qtySection').classList.add('hidden');
            selectedProduct = null;
            document.getElementById('productSearch').focus();
        }

        function closeProductModal() {
            document.getElementById('productModal').classList.remove('active');
        }

        function searchProducts() {
            const search = document.getElementById('productSearch').value.toLowerCase().trim();
            const productList = document.getElementById('productList');
            const hint = document.getElementById('productSearchHint');

            if (search.length < 1) {
                productList.innerHTML = '';
                hint.classList.remove('hidden');
                document.getElementById('qtySection').classList.add('hidden');
                selectedProduct = null;
                return;
            }

            hint.classList.add('hidden');
            const filtered = allProducts.filter(p => p.name.toLowerCase().includes(search));

            if (filtered.length === 0) {
                productList.innerHTML = '<div style="text-align:center;padding:20px;color:#94a3b8;">لا توجد نتائج</div>';
                return;
            }

            productList.innerHTML = '';
            filtered.forEach((p, idx) => {
                const div = document.createElement('div');
                div.className = 'product-item';
                div.dataset.idx = idx;
                div.innerHTML = `
                    <div>
                        <div class="product-name">
                            ${p.type === 'weight' ? '<span class="weight-tag"><i class="ti ti-scale"></i></span>' : ''}
                            ${p.name}
                        </div>
                    </div>
                    <div class="product-price">${parseFloat(p.price).toFixed(3)} د.ل</div>
                `;
                div.addEventListener('click', () => selectProductFromList(p));
                productList.appendChild(div);
            });
        }

        function selectProductFromList(product) {
            document.querySelectorAll('.product-item').forEach(i => i.classList.remove('selected'));
            event.currentTarget.classList.add('selected');
            selectedProduct = {
                id: product.id,
                name: product.name,
                price: parseFloat(product.price),
                type: product.type
            };
            document.getElementById('qtySection').classList.remove('hidden');
            document.getElementById('qtyInput').focus();
            document.getElementById('qtyInput').placeholder = selectedProduct.type === 'weight' ? 'الوزن بالكيلو' : 'العدد';
        }

        function selectProduct(item) {
            document.querySelectorAll('.product-item').forEach(i => i.classList.remove('selected'));
            item.classList.add('selected');
            selectedProduct = {
                id: item.dataset.id,
                name: item.dataset.name,
                price: parseFloat(item.dataset.price),
                type: item.dataset.type
            };
            document.getElementById('qtySection').classList.remove('hidden');
            document.getElementById('qtyInput').focus();
            document.getElementById('qtyInput').placeholder = selectedProduct.type === 'weight' ? 'الوزن بالكيلو' : 'العدد';
        }

        function confirmProduct() {
            if (!selectedProduct) return toast('اختر صنف', 'error');
            const qty = parseFloat(document.getElementById('qtyInput').value);
            if (!qty || qty <= 0) return toast('أدخل الكمية', 'error');

            const total = selectedProduct.price * qty;
            orderItems.push({
                product_id: selectedProduct.id,
                product_name: selectedProduct.name,
                price: selectedProduct.price,
                quantity: qty,
                total: total,
                is_weight: selectedProduct.type === 'weight'
            });

            renderItems();
            updateSummary();
            closeProductModal();
            toast('تم إضافة الصنف', 'success');
        }

        function renderItems() {
            const html = orderItems.map((item, index) => {
                const qty = item.is_weight ? parseFloat(item.quantity).toFixed(3) + ' كجم' : item.quantity;
                return `<tr>
                    <td><button class="remove-btn" onclick="removeItem(${index})"><i class="ti ti-x"></i></button></td>
                    <td>${item.product_name}${item.is_weight ? '<span class="weight-tag"><i class="ti ti-scale"></i></span>' : ''}</td>
                    <td style="text-align:center">${qty}</td>
                    <td style="text-align:center">${parseFloat(item.price).toFixed(3)}</td>
                    <td style="text-align:left">${parseFloat(item.total).toFixed(3)}</td>
                </tr>`;
            }).join('');
            document.getElementById('itemsBody').innerHTML = html;
        }

        function removeItem(index) {
            orderItems.splice(index, 1);
            renderItems();
            updateSummary();
        }

        function selectPaymentMethod(btn) {
            document.querySelectorAll('.pm-btn').forEach(b => b.classList.remove('selected'));
            btn.classList.add('selected');
            selectedPaymentMethod = { id: btn.dataset.id, name: btn.dataset.name };
            document.getElementById('selectedMethodName').textContent = btn.dataset.name;
            document.getElementById('amountSection').classList.remove('hidden');
            document.getElementById('amountInput').focus();
        }

        function updateSummary() {
            const total = orderItems.reduce((sum, item) => sum + item.total, 0);
            const payment = parseFloat(document.getElementById('amountInput').value) || 0;
            const remaining = total - payment;

            document.getElementById('totalAmount').textContent = total.toFixed(3) + ' د.ل';
            document.getElementById('paidAmount').textContent = payment.toFixed(3) + ' د.ل';
            document.getElementById('remainingAmount').textContent = remaining.toFixed(3) + ' د.ل';
        }

        document.getElementById('amountInput').addEventListener('input', updateSummary);

        function clearValidation() {
            document.querySelectorAll('.validation-error').forEach(el => el.classList.remove('validation-error'));
        }

        function markInvalid(elementId) {
            const el = document.getElementById(elementId);
            if (el) {
                el.classList.add('validation-error');
                el.focus();
            }
        }

        async function saveOrder() {
            clearValidation();

            const customerNameEl = document.getElementById('customerName');
            const eventTypeEl = document.getElementById('eventType');
            const deliveryDateEl = document.getElementById('deliveryDate');

            const customerName = customerNameEl.value.trim();
            const eventType = eventTypeEl.value;
            const deliveryDate = deliveryDateEl.value;

            let errors = [];

            if (!customerName) {
                errors.push('اسم الزبون');
                customerNameEl.classList.add('validation-error');
            }
            if (!eventType) {
                errors.push('نوع المناسبة');
                eventTypeEl.classList.add('validation-error');
            }
            if (!deliveryDate) {
                errors.push('تاريخ التسليم');
                deliveryDateEl.classList.add('validation-error');
            }
            if (orderItems.length === 0) {
                errors.push('أضف صنف واحد على الأقل');
            }

            if (errors.length > 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'بيانات ناقصة',
                    html: '<ul style="text-align:right;margin:0;padding-right:20px">' + errors.map(e => '<li>' + e + '</li>').join('') + '</ul>',
                    confirmButtonText: 'حسناً'
                });
                const firstInvalid = document.querySelector('.validation-error');
                if (firstInvalid) firstInvalid.focus();
                return;
            }

            const total = orderItems.reduce((sum, item) => sum + item.total, 0);
            const payment = parseFloat(document.getElementById('amountInput').value) || 0;

            if (payment > total) return toast('الدفعة أكبر من الإجمالي', 'error');
            if (payment > 0 && !selectedPaymentMethod) return toast('اختر طريقة الدفع', 'error');

            const payload = {
                customer_id: document.getElementById('customerId').value || null,
                customer_name: customerName,
                phone: document.getElementById('customerPhone').value,
                event_type: eventType,
                delivery_date: deliveryDate,
                description: null,
                notes: null,
                items: orderItems.map(i => ({
                    product_id: i.product_id,
                    quantity: i.quantity,
                    price: i.price,
                    total: i.total
                })),
                total_amount: total,
                initial_payment: payment > 0 ? payment : null,
                payment_method_id: payment > 0 ? selectedPaymentMethod.id : null
            };

            try {
                const res = await fetch(BASE_URL + '/cashier/special-orders/store', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();

                if (data.success) {
                    toast('تم حفظ الطلبية بنجاح', 'success');
                    printNewOrder(data.data);
                    resetForm();
                } else {
                    toast(data.message, 'error');
                }
            } catch (err) {
                toast('خطأ في الاتصال', 'error');
            }
        }

        async function addPayment() {
            if (!currentOrder) return;
            if (!selectedPaymentMethod) return toast('اختر طريقة الدفع', 'error');

            const amount = parseFloat(document.getElementById('amountInput').value);
            if (!amount || amount <= 0) return toast('أدخل المبلغ', 'error');
            if (amount > currentOrder.remaining_amount + 0.001) return toast('المبلغ أكبر من المتبقي', 'error');

            try {
                const res = await fetch(BASE_URL + '/cashier/special-orders/payment', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        special_order_id: currentOrder.id,
                        payment_method_id: selectedPaymentMethod.id,
                        amount: amount
                    })
                });
                const data = await res.json();

                if (data.success) {
                    currentOrder = data.data;
                    renderOrderView();
                    toast('تم إضافة الدفعة', 'success');

                    if (currentOrder.remaining_amount <= 0) {
                        toast('تم سداد الطلبية بالكامل!', 'success');
                    }
                } else {
                    toast(data.message, 'error');
                }
            } catch (err) {
                toast('خطأ في الاتصال', 'error');
            }
        }

        function printOrder() {
            if (!currentOrder) return;
            window.open(`/taj-sultan/public/cashier/special-orders/${currentOrder.id}/print`, '_blank');
        }

        function printNewOrder(data) {
            let itemsHtml = '';
            data.items.forEach(i => {
                const qty = i.is_weight ? parseFloat(i.quantity).toFixed(3) + ' كجم' : i.quantity;
                itemsHtml += `<tr><td>${i.product_name}</td><td style="text-align:center">${qty}</td><td style="text-align:center">${parseFloat(i.price).toFixed(3)}</td><td style="text-align:left">${parseFloat(i.total).toFixed(3)}</td></tr>`;
            });

            let paymentsHtml = '';
            if (data.payments && data.payments.length > 0) {
                paymentsHtml = '<div class="payments-section"><div class="section-title">المدفوعات</div>';
                data.payments.forEach(p => {
                    paymentsHtml += `<div class="payment-row"><span>${p.method} ${p.notes ? '(' + p.notes + ')' : ''}</span><span>${parseFloat(p.amount).toFixed(3)} د.ل</span></div>`;
                });
                paymentsHtml += '</div>';
            }

            const barcodeValue = String(data.id).padStart(8, '0');

            const receiptContent = `
            <div class="receipt">
            <div class="header"><div class="title">تاج السلطان - طلبية خاصة</div></div>
            <div class="barcode-section"><svg class="barcode-svg"></svg><div class="order-id">#${data.id}</div></div>
            <div class="section">
                <div class="info"><span class="label">التاريخ:</span><span>${data.created_at}</span></div>
                <div class="info"><span class="label">الكاشير:</span><span>${data.cashier_name}</span></div>
                <div class="info"><span class="label">الزبون:</span><span><strong>${data.customer_name}</strong></span></div>
                <div class="info"><span class="label">الهاتف:</span><span>${data.phone || '-'}</span></div>
                <div class="info"><span class="label">المناسبة:</span><span>${data.event_type}</span></div>
                <div class="info"><span class="label">التسليم:</span><span>${data.delivery_date}</span></div>
            </div>
            <div class="status-box">${data.status_name || 'قيد الانتظار'}</div>
            <table><thead><tr><th>الصنف</th><th>الكمية</th><th>السعر</th><th>الإجمالي</th></tr></thead><tbody>${itemsHtml}</tbody></table>
            <div class="total-box"><span>الإجمالي</span><span>${parseFloat(data.total_amount).toFixed(3)} د.ل</span></div>
            <div class="paid-box"><span>المدفوع</span><span>${parseFloat(data.paid_amount).toFixed(3)} د.ل</span></div>
            <div class="remaining-box"><span>المتبقي</span><span>${parseFloat(data.remaining_amount).toFixed(3)} د.ل</span></div>
            ${paymentsHtml}
            <div class="thanks">شكراً لتعاملكم معنا</div>
            <div class="footer"><img src="/hulul.jpg"><span>حلول لتقنية المعلومات</span></div>
            </div>`;

            const html = `<!DOCTYPE html><html dir="rtl"><head><meta charset="UTF-8">
            <link href="{{ asset('assets/fonts/cairo/cairo.css') }}" rel="stylesheet">
            <script src="{{ asset('js/barcode/jsbarcode.min.js') }}"><\/script>
            <style>
                @page{margin:0;size:72mm auto}
                *{margin:0;padding:0;box-sizing:border-box}
                body{font-family:'Cairo',sans-serif;font-size:11px;line-height:1.3;padding:3mm;width:72mm;color:#000}
                .receipt{page-break-after:always}
                .receipt:last-child{page-break-after:auto}
                .header{text-align:center;border-bottom:1px dashed #000;padding-bottom:6px;margin-bottom:6px}
                .header .title{font-size:13px;font-weight:700}
                .barcode-section{text-align:center;border-bottom:1px dashed #000;padding:5px 0}
                .barcode-svg{display:block;margin:0 auto}
                .order-id{font-size:12px;font-weight:700;margin-top:3px}
                .info{font-size:11px;display:flex;justify-content:space-between;padding:2px 0}
                .info .label{font-weight:700}
                .section{border-bottom:1px dashed #000;padding-bottom:6px;margin-bottom:6px}
                .status-box{text-align:center;padding:5px;border:1px solid #000;font-weight:700;font-size:12px;margin:6px 0}
                table{width:100%;border-collapse:collapse;margin:6px 0}
                th{background:#000;color:#fff;padding:4px;font-size:10px}
                td{padding:4px;font-size:10px;border-bottom:1px dotted #ccc}
                .total-box{border:1px solid #000;padding:6px;margin:6px 0;display:flex;justify-content:space-between;font-size:14px;font-weight:800}
                .paid-box{display:flex;justify-content:space-between;font-size:11px;font-weight:700;padding:3px 0}
                .remaining-box{border:1px dashed #000;padding:5px;margin-top:4px;display:flex;justify-content:space-between;font-size:12px;font-weight:700}
                .payments-section{border-top:1px dashed #000;padding-top:6px;margin-top:6px}
                .section-title{background:#000;color:#fff;text-align:center;font-weight:700;font-size:10px;padding:3px}
                .payment-row{display:flex;justify-content:space-between;font-size:10px;padding:2px 0}
                .thanks{text-align:center;font-size:11px;font-weight:700;border-top:1px dashed #000;padding:6px 0}
                .footer{text-align:center;display:flex;align-items:center;justify-content:center;gap:5px;font-size:9px;color:#333;padding-top:4px}
                .footer img{height:22px;width:auto;filter:grayscale(100%)}
            </style></head><body>
            ${receiptContent}
            ${receiptContent}
            <script>
                JsBarcode(".barcode-svg", "${barcodeValue}", {format:"CODE128",width:1.8,height:40,displayValue:false,margin:5,background:"#ffffff",lineColor:"#000000"});
            <\/script>
            </body></html>`;

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
                    setTimeout(() => win.close(), 250);
                }, 250);
            }
        }

        function resetForm() {
            currentOrder = null;
            isNewOrder = false;
            orderItems = [];
            selectedProduct = null;
            selectedPaymentMethod = null;

            document.getElementById('orderForm').classList.add('hidden');
            document.getElementById('orderView').classList.add('hidden');
            document.getElementById('emptyState').classList.remove('hidden');
            document.getElementById('statusBadge').classList.add('hidden');
            document.getElementById('sectionTitle').querySelector('span').textContent = 'تفاصيل الطلبية';

            document.getElementById('saveBtn').classList.add('hidden');
            document.getElementById('addPaymentBtn').classList.add('hidden');
            document.getElementById('printBtn').classList.add('hidden');
            document.getElementById('amountSection').classList.add('hidden');
            document.getElementById('amountInput').value = '';

            document.querySelectorAll('.pm-btn').forEach(b => b.classList.remove('selected'));

            document.getElementById('totalAmount').textContent = '0.000 د.ل';
            document.getElementById('paidAmount').textContent = '0.000 د.ل';
            document.getElementById('remainingAmount').textContent = '0.000 د.ل';

            document.getElementById('searchInput').value = '';
            document.getElementById('searchInput').focus();
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
